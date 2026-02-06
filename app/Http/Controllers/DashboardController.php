<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Dashboard Controller
 * 
 * Provides a single optimized endpoint for all dashboard KPI data.
 * Supports both monthly and yearly views with comparison metrics.
 */
class DashboardController extends Controller
{
    private DashboardService $dashboardService;

    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display the KPI dashboard view
     * 
     * @return View
     */
    public function index(): View
    {
        $availableYears = $this->dashboardService->getAvailableYears();
        
        return view('dashboard-kpi', [
            'availableYears' => $availableYears,
            'currentYear' => (int) date('Y'),
            'currentMonth' => (int) date('m'),
        ]);
    }

    /**
     * Get all dashboard data in a single optimized API call
     * 
     * This endpoint returns all necessary data for the KPI dashboard
     * including summaries, trends, category breakdowns, and comparisons.
     * 
     * Request Parameters:
     * - type: 'monthly' | 'yearly' (required)
     * - year: integer (required)
     * - month: integer (required for monthly type, 1-12)
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function getData(Request $request): JsonResponse
    {
        // Validate request parameters
        $validated = $request->validate([
            'type' => 'required|in:monthly,yearly',
            'year' => 'required|integer|min:2000|max:2100',
            'month' => 'required_if:type,monthly|integer|min:1|max:12',
        ]);

        $type = $validated['type'];
        $year = (int) $validated['year'];
        $month = $type === 'monthly' ? (int) $validated['month'] : null;

        try {
            $data = $this->dashboardService->getDashboardData($type, $year, $month);
            
            return response()->json([
                'success' => true,
                'data' => $data,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve dashboard data',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }

    /**
     * Get list of available years for the year selector
     * 
     * @return JsonResponse
     */
    public function getAvailableYears(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'years' => $this->dashboardService->getAvailableYears(),
        ]);
    }
}
