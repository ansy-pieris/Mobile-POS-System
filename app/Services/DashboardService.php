<?php

namespace App\Services;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * Dashboard Service
 * 
 * Provides optimized queries for KPI dashboard data.
 * All queries are designed to minimize database round-trips
 * and use proper indexing for performance.
 */
class DashboardService
{
    /**
     * Get complete dashboard data in a single call
     * 
     * @param string $type 'monthly' or 'yearly'
     * @param int $year Target year
     * @param int|null $month Target month (required for monthly view)
     * @return array Complete dashboard data structure
     */
    public function getDashboardData(string $type, int $year, ?int $month = null): array
    {
        // Determine the date range based on view type
        $dateRange = $this->getDateRange($type, $year, $month);
        $previousRange = $this->getPreviousDateRange($type, $year, $month);

        return [
            'kpi_summary' => $this->getKpiSummary($dateRange),
            'comparison_metrics' => $this->getComparisonMetrics($dateRange, $previousRange),
            'revenue_trend' => $this->getRevenueTrend($type, $dateRange),
            'category_breakdown' => $this->getCategoryBreakdown($dateRange),
            'cost_breakdown' => $this->getCostBreakdown($dateRange),
            'accumulated_revenue' => $this->getAccumulatedRevenue($type, $dateRange),
            'period' => [
                'type' => $type,
                'year' => $year,
                'month' => $month,
                'start_date' => $dateRange['start']->toDateString(),
                'end_date' => $dateRange['end']->toDateString(),
            ],
        ];
    }

    /**
     * Calculate date range based on view type
     */
    private function getDateRange(string $type, int $year, ?int $month): array
    {
        if ($type === 'monthly' && $month !== null) {
            $start = Carbon::create($year, $month, 1)->startOfMonth();
            $end = Carbon::create($year, $month, 1)->endOfMonth();
        } else {
            $start = Carbon::create($year, 1, 1)->startOfYear();
            $end = Carbon::create($year, 12, 31)->endOfYear();
        }

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Calculate previous period date range for comparison
     */
    private function getPreviousDateRange(string $type, int $year, ?int $month): array
    {
        if ($type === 'monthly' && $month !== null) {
            // Previous month
            $prevDate = Carbon::create($year, $month, 1)->subMonth();
            $start = $prevDate->copy()->startOfMonth();
            $end = $prevDate->copy()->endOfMonth();
        } else {
            // Previous year
            $start = Carbon::create($year - 1, 1, 1)->startOfYear();
            $end = Carbon::create($year - 1, 12, 31)->endOfYear();
        }

        return ['start' => $start, 'end' => $end];
    }

    /**
     * Get KPI summary metrics
     * 
     * Single optimized query to get all main KPIs:
     * - Total sales count
     * - Total revenue
     * - Total cost
     * - Total profit
     */
    private function getKpiSummary(array $dateRange): array
    {
        $result = Invoice::selectRaw('
            COUNT(*) as total_sales_count,
            COALESCE(SUM(total_amount), 0) as total_revenue,
            COALESCE(SUM(total_cost), 0) as total_cost,
            COALESCE(SUM(total_profit), 0) as total_profit
        ')
        ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
        ->first();

        return [
            'total_sales_count' => (int) $result->total_sales_count,
            'total_revenue' => (float) $result->total_revenue,
            'total_cost' => (float) $result->total_cost,
            'total_profit' => (float) $result->total_profit,
        ];
    }

    /**
     * Get comparison metrics with percentage changes
     * 
     * Compares current period vs previous period and calculates:
     * - Percentage change for each metric
     * - Direction indicator (up/down/neutral)
     */
    private function getComparisonMetrics(array $currentRange, array $previousRange): array
    {
        // Get current period totals
        $current = $this->getKpiSummary($currentRange);
        
        // Get previous period totals
        $previous = $this->getKpiSummary($previousRange);

        return [
            'revenue' => $this->calculateChange($current['total_revenue'], $previous['total_revenue']),
            'profit' => $this->calculateChange($current['total_profit'], $previous['total_profit']),
            'sales_count' => $this->calculateChange($current['total_sales_count'], $previous['total_sales_count']),
            'cost' => $this->calculateChange($current['total_cost'], $previous['total_cost']),
        ];
    }

    /**
     * Calculate percentage change between two values
     * 
     * Formula: ((current - previous) / previous) * 100
     * 
     * @param float $current Current period value
     * @param float $previous Previous period value
     * @return array Contains percentage, direction, and formatted value
     */
    private function calculateChange(float $current, float $previous): array
    {
        if ($previous == 0) {
            // Handle division by zero
            $percentage = $current > 0 ? 100 : 0;
            $direction = $current > 0 ? 'up' : 'neutral';
        } else {
            $percentage = (($current - $previous) / $previous) * 100;
            
            if ($percentage > 0) {
                $direction = 'up';
            } elseif ($percentage < 0) {
                $direction = 'down';
            } else {
                $direction = 'neutral';
            }
        }

        return [
            'percentage' => round(abs($percentage), 2),
            'direction' => $direction,
            'current_value' => $current,
            'previous_value' => $previous,
            'difference' => $current - $previous,
        ];
    }

    /**
     * Get revenue trend data for charts
     * 
     * For monthly view: Groups by DATE (daily breakdown)
     * For yearly view: Groups by MONTH (monthly breakdown)
     */
    private function getRevenueTrend(string $type, array $dateRange): array
    {
        if ($type === 'monthly') {
            // Daily breakdown for monthly view
            $data = Invoice::selectRaw('
                DATE(created_at) as period,
                COUNT(*) as sales_count,
                COALESCE(SUM(total_amount), 0) as revenue,
                COALESCE(SUM(total_cost), 0) as cost,
                COALESCE(SUM(total_profit), 0) as profit
            ')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupByRaw('DATE(created_at)')
            ->orderByRaw('DATE(created_at)')
            ->get();

            // Generate all days in the month for complete data
            $labels = [];
            $values = [];
            $currentDate = $dateRange['start']->copy();
            
            while ($currentDate <= $dateRange['end']) {
                $dateKey = $currentDate->format('Y-m-d');
                $found = $data->firstWhere('period', $dateKey);
                
                $labels[] = $currentDate->format('d');
                $values[] = [
                    'date' => $dateKey,
                    'label' => $currentDate->format('M d'),
                    'revenue' => $found ? (float) $found->revenue : 0,
                    'cost' => $found ? (float) $found->cost : 0,
                    'profit' => $found ? (float) $found->profit : 0,
                    'sales_count' => $found ? (int) $found->sales_count : 0,
                ];
                
                $currentDate->addDay();
            }
        } else {
            // Monthly breakdown for yearly view
            $data = Invoice::selectRaw('
                MONTH(created_at) as period,
                YEAR(created_at) as year,
                COUNT(*) as sales_count,
                COALESCE(SUM(total_amount), 0) as revenue,
                COALESCE(SUM(total_cost), 0) as cost,
                COALESCE(SUM(total_profit), 0) as profit
            ')
            ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
            ->groupByRaw('YEAR(created_at), MONTH(created_at)')
            ->orderByRaw('YEAR(created_at), MONTH(created_at)')
            ->get();

            $monthNames = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            $labels = [];
            $values = [];
            
            for ($m = 1; $m <= 12; $m++) {
                $found = $data->firstWhere('period', $m);
                
                $labels[] = $monthNames[$m - 1];
                $values[] = [
                    'month' => $m,
                    'label' => $monthNames[$m - 1],
                    'revenue' => $found ? (float) $found->revenue : 0,
                    'cost' => $found ? (float) $found->cost : 0,
                    'profit' => $found ? (float) $found->profit : 0,
                    'sales_count' => $found ? (int) $found->sales_count : 0,
                ];
            }
        }

        return [
            'labels' => $labels,
            'data' => $values,
        ];
    }

    /**
     * Get category-wise breakdown (revenue, cost, profit)
     * 
     * Groups invoice items by sale_category (phones, accessories, services)
     */
    private function getCategoryBreakdown(array $dateRange): array
    {
        $data = InvoiceItem::selectRaw('
            sale_category,
            COUNT(*) as items_count,
            COALESCE(SUM(quantity), 0) as total_quantity,
            COALESCE(SUM(total), 0) as revenue,
            COALESCE(SUM(cost_price * quantity), 0) as cost,
            COALESCE(SUM(profit), 0) as profit
        ')
        ->whereBetween('created_at', [$dateRange['start'], $dateRange['end']])
        ->groupBy('sale_category')
        ->get()
        ->keyBy('sale_category');

        // Ensure all categories are present
        $categories = ['phones', 'accessories', 'services'];
        $result = [];

        foreach ($categories as $category) {
            $categoryData = $data->get($category);
            
            $result[$category] = [
                'items_count' => $categoryData ? (int) $categoryData->items_count : 0,
                'total_quantity' => $categoryData ? (int) $categoryData->total_quantity : 0,
                'revenue' => $categoryData ? (float) $categoryData->revenue : 0,
                'cost' => $categoryData ? (float) $categoryData->cost : 0,
                'profit' => $categoryData ? (float) $categoryData->profit : 0,
            ];
        }

        return $result;
    }

    /**
     * Get cost breakdown for donut chart
     * 
     * Returns cost distribution across categories
     */
    private function getCostBreakdown(array $dateRange): array
    {
        $categoryData = $this->getCategoryBreakdown($dateRange);
        
        $totalCost = array_sum(array_column($categoryData, 'cost'));
        
        $breakdown = [];
        foreach ($categoryData as $category => $data) {
            $percentage = $totalCost > 0 ? ($data['cost'] / $totalCost) * 100 : 0;
            
            $breakdown[] = [
                'category' => ucfirst($category),
                'cost' => $data['cost'],
                'percentage' => round($percentage, 2),
            ];
        }

        return [
            'total_cost' => $totalCost,
            'breakdown' => $breakdown,
            'labels' => array_column($breakdown, 'category'),
            'values' => array_column($breakdown, 'cost'),
        ];
    }

    /**
     * Get accumulated revenue (waterfall-style cumulative data)
     * 
     * Returns running total of revenue over the period
     */
    private function getAccumulatedRevenue(string $type, array $dateRange): array
    {
        $trend = $this->getRevenueTrend($type, $dateRange);
        
        $accumulated = [];
        $runningTotal = 0;
        
        foreach ($trend['data'] as $item) {
            $runningTotal += $item['revenue'];
            $accumulated[] = [
                'label' => $item['label'],
                'period_revenue' => $item['revenue'],
                'accumulated_revenue' => $runningTotal,
            ];
        }

        return [
            'labels' => $trend['labels'],
            'period_values' => array_column($trend['data'], 'revenue'),
            'accumulated_values' => array_column($accumulated, 'accumulated_revenue'),
            'data' => $accumulated,
        ];
    }

    /**
     * Get available years for the year selector
     */
    public function getAvailableYears(): array
    {
        $years = Invoice::selectRaw('DISTINCT YEAR(created_at) as year')
            ->orderBy('year', 'desc')
            ->pluck('year')
            ->toArray();

        // Always include current year even if no data
        $currentYear = (int) date('Y');
        if (!in_array($currentYear, $years)) {
            array_unshift($years, $currentYear);
        }

        return $years;
    }
}
