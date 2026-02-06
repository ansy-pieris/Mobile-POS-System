<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Sales Dashboard') }}
            </h2>
            <div class="mt-4 sm:mt-0 flex items-center space-x-4">
                <!-- Period Type Toggle -->
                <div class="inline-flex rounded-lg border border-gray-200 bg-white p-1">
                    <button type="button" id="btn-monthly" 
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 bg-indigo-600 text-white">
                        Monthly
                    </button>
                    <button type="button" id="btn-yearly" 
                        class="px-4 py-2 text-sm font-medium rounded-md transition-colors duration-200 text-gray-600 hover:bg-gray-100">
                        Yearly
                    </button>
                </div>
                
                <!-- Year Selector -->
                <select id="select-year" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @foreach($availableYears as $year)
                        <option value="{{ $year }}" {{ $year == $currentYear ? 'selected' : '' }}>{{ $year }}</option>
                    @endforeach
                </select>
                
                <!-- Month Selector (visible only in monthly view) -->
                <select id="select-month" class="rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @php
                        $months = ['January', 'February', 'March', 'April', 'May', 'June', 
                                   'July', 'August', 'September', 'October', 'November', 'December'];
                    @endphp
                    @foreach($months as $index => $month)
                        <option value="{{ $index + 1 }}" {{ ($index + 1) == $currentMonth ? 'selected' : '' }}>{{ $month }}</option>
                    @endforeach
                </select>
            </div>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Loading Overlay -->
            <div id="loading-overlay" class="hidden fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-6 flex items-center space-x-4">
                    <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span class="text-gray-700 font-medium">Loading dashboard...</span>
                </div>
            </div>

            <!-- KPI Cards Row -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <!-- Sales Count Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Number of Sales</p>
                                <p id="kpi-sales-count" class="mt-2 text-3xl font-bold text-gray-900">0</p>
                            </div>
                            <div class="p-3 bg-blue-100 rounded-full">
                                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center" id="kpi-sales-count-change">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                --
                            </span>
                            <span class="ml-2 text-sm text-gray-500">vs previous period</span>
                        </div>
                    </div>
                </div>

                <!-- Revenue Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Revenue</p>
                                <p id="kpi-revenue" class="mt-2 text-3xl font-bold text-gray-900">Rs.0</p>
                            </div>
                            <div class="p-3 bg-green-100 rounded-full">
                                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center" id="kpi-revenue-change">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                --
                            </span>
                            <span class="ml-2 text-sm text-gray-500">vs previous period</span>
                        </div>
                    </div>
                </div>

                <!-- Profit Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Profit</p>
                                <p id="kpi-profit" class="mt-2 text-3xl font-bold text-gray-900">Rs.0</p>
                            </div>
                            <div class="p-3 bg-emerald-100 rounded-full">
                                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center" id="kpi-profit-change">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                --
                            </span>
                            <span class="ml-2 text-sm text-gray-500">vs previous period</span>
                        </div>
                    </div>
                </div>

                <!-- Cost Card -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm font-medium text-gray-500 uppercase tracking-wide">Total Cost</p>
                                <p id="kpi-cost" class="mt-2 text-3xl font-bold text-gray-900">Rs.0</p>
                            </div>
                            <div class="p-3 bg-red-100 rounded-full">
                                <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="mt-4 flex items-center" id="kpi-cost-change">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                --
                            </span>
                            <span class="ml-2 text-sm text-gray-500">vs previous period</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quick Actions Row -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Quick Actions</h3>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <a href="{{ route('invoice.create') }}" class="flex flex-col items-center p-4 bg-blue-50 hover:bg-blue-100 rounded-lg transition duration-200">
                            <svg class="w-8 h-8 text-blue-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            <span class="text-sm font-medium text-blue-700">New Invoice</span>
                        </a>
                        <a href="{{ route('products') }}" class="flex flex-col items-center p-4 bg-green-50 hover:bg-green-100 rounded-lg transition duration-200">
                            <svg class="w-8 h-8 text-green-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            <span class="text-sm font-medium text-green-700">Products</span>
                        </a>
                        <a href="{{ route('customers') }}" class="flex flex-col items-center p-4 bg-purple-50 hover:bg-purple-100 rounded-lg transition duration-200">
                            <svg class="w-8 h-8 text-purple-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-purple-700">Customers</span>
                        </a>
                        <a href="{{ route('invoices.list') }}" class="flex flex-col items-center p-4 bg-orange-50 hover:bg-orange-100 rounded-lg transition duration-200">
                            <svg class="w-8 h-8 text-orange-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span class="text-sm font-medium text-orange-700">Invoices</span>
                        </a>
                        @if(auth()->user()->isAdmin())
                        <a href="{{ route('staff') }}" class="flex flex-col items-center p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg transition duration-200">
                            <svg class="w-8 h-8 text-yellow-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-yellow-700">Staff</span>
                        </a>
                        @endif
                        <a href="{{ route('settings') }}" class="flex flex-col items-center p-4 bg-gray-50 hover:bg-gray-100 rounded-lg transition duration-200">
                            <svg class="w-8 h-8 text-gray-600 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            <span class="text-sm font-medium text-gray-700">Settings</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Charts Row 1: Revenue Trend (Full Width) -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900">Revenue Trend</h3>
                        <span id="trend-period-label" class="text-sm text-gray-500">Loading...</span>
                    </div>
                    <div class="h-80">
                        <canvas id="chart-revenue-trend"></canvas>
                    </div>
                </div>
            </div>

            <!-- Charts Row 2: Donut + Category Bar -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- Cost Breakdown Donut Chart -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cost Breakdown by Category</h3>
                        <div class="h-72 flex items-center justify-center">
                            <canvas id="chart-cost-donut"></canvas>
                        </div>
                        <div id="donut-legend" class="mt-4 flex justify-center space-x-6">
                            <!-- Legend will be populated dynamically -->
                        </div>
                    </div>
                </div>

                <!-- Category Revenue Comparison Bar Chart -->
                <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                    <div class="p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Category Revenue Comparison</h3>
                        <div class="h-72">
                            <canvas id="chart-category-bar"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Charts Row 3: Accumulated Revenue (Waterfall) -->
            <div class="bg-white overflow-hidden shadow-lg rounded-xl mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Accumulated Revenue</h3>
                    <div class="h-80">
                        <canvas id="chart-accumulated"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
    
    <script>
        /**
         * KPI Dashboard JavaScript
         * 
         * Handles:
         * - Data fetching from API
         * - Chart initialization and updates
         * - Period switching (monthly/yearly)
         * - KPI card updates with comparison indicators
         */
        
        // ============================================================
        // CONFIGURATION & STATE
        // ============================================================
        
        const DashboardConfig = {
            apiUrl: '{{ route("dashboard.api.data") }}',
            colors: {
                primary: 'rgb(79, 70, 229)',      // Indigo
                primaryLight: 'rgba(79, 70, 229, 0.1)',
                success: 'rgb(16, 185, 129)',     // Green
                successLight: 'rgba(16, 185, 129, 0.1)',
                danger: 'rgb(239, 68, 68)',       // Red
                dangerLight: 'rgba(239, 68, 68, 0.1)',
                warning: 'rgb(245, 158, 11)',     // Yellow
                warningLight: 'rgba(245, 158, 11, 0.1)',
                // Category colors
                phones: 'rgb(59, 130, 246)',      // Blue
                accessories: 'rgb(16, 185, 129)', // Green
                services: 'rgb(168, 85, 247)',    // Purple
            },
            categoryColors: {
                phones: {
                    bg: 'rgba(59, 130, 246, 0.8)',
                    border: 'rgb(59, 130, 246)'
                },
                accessories: {
                    bg: 'rgba(16, 185, 129, 0.8)',
                    border: 'rgb(16, 185, 129)'
                },
                services: {
                    bg: 'rgba(168, 85, 247, 0.8)',
                    border: 'rgb(168, 85, 247)'
                }
            }
        };

        let DashboardState = {
            type: 'monthly',
            year: {{ $currentYear }},
            month: {{ $currentMonth }},
            charts: {
                revenueTrend: null,
                costDonut: null,
                categoryBar: null,
                accumulated: null
            },
            data: null
        };

        // ============================================================
        // UTILITY FUNCTIONS
        // ============================================================
        
        /**
         * Format number as currency (Rs.)
         */
        function formatCurrency(value) {
            return 'Rs.' + new Intl.NumberFormat('en-IN', {
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }).format(value);
        }

        /**
         * Format number with thousand separators
         */
        function formatNumber(value) {
            return new Intl.NumberFormat('en-IN').format(value);
        }

        /**
         * Show/hide loading overlay
         */
        function setLoading(isLoading) {
            const overlay = document.getElementById('loading-overlay');
            if (isLoading) {
                overlay.classList.remove('hidden');
            } else {
                overlay.classList.add('hidden');
            }
        }

        /**
         * Generate change indicator HTML for KPI cards
         */
        function getChangeIndicatorHtml(changeData, invertColors = false) {
            if (!changeData) {
                return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">--</span>
                        <span class="ml-2 text-sm text-gray-500">vs previous period</span>`;
            }

            const { percentage, direction } = changeData;
            let colorClass, arrowIcon;

            // For cost, "up" is bad (red) and "down" is good (green)
            const isPositive = invertColors ? direction === 'down' : direction === 'up';

            if (direction === 'neutral') {
                colorClass = 'bg-gray-100 text-gray-800';
                arrowIcon = '';
            } else if (isPositive) {
                colorClass = 'bg-green-100 text-green-800';
                arrowIcon = `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.293 9.707a1 1 0 010-1.414l4-4a1 1 0 011.414 0l4 4a1 1 0 01-1.414 1.414L11 7.414V15a1 1 0 11-2 0V7.414L6.707 9.707a1 1 0 01-1.414 0z" clip-rule="evenodd"/>
                </svg>`;
            } else {
                colorClass = 'bg-red-100 text-red-800';
                arrowIcon = `<svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M14.707 10.293a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 111.414-1.414L9 12.586V5a1 1 0 012 0v7.586l2.293-2.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                </svg>`;
            }

            return `<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${colorClass}">
                        ${arrowIcon}${percentage}%
                    </span>
                    <span class="ml-2 text-sm text-gray-500">vs previous period</span>`;
        }

        // ============================================================
        // API & DATA FUNCTIONS
        // ============================================================
        
        /**
         * Fetch dashboard data from API
         */
        async function fetchDashboardData() {
            setLoading(true);
            
            try {
                const params = new URLSearchParams({
                    type: DashboardState.type,
                    year: DashboardState.year,
                    month: DashboardState.month
                });

                const response = await fetch(`${DashboardConfig.apiUrl}?${params}`);
                const result = await response.json();

                if (result.success) {
                    DashboardState.data = result.data;
                    updateDashboard();
                } else {
                    console.error('API Error:', result.message);
                    alert('Failed to load dashboard data. Please try again.');
                }
            } catch (error) {
                console.error('Fetch Error:', error);
                alert('Failed to connect to server. Please check your connection.');
            } finally {
                setLoading(false);
            }
        }

        // ============================================================
        // UI UPDATE FUNCTIONS
        // ============================================================
        
        /**
         * Update all dashboard components with new data
         */
        function updateDashboard() {
            const data = DashboardState.data;
            if (!data) return;

            // Update KPI cards
            updateKpiCards(data.kpi_summary, data.comparison_metrics);
            
            // Update period label
            updatePeriodLabel(data.period);
            
            // Update charts
            updateRevenueTrendChart(data.revenue_trend);
            updateCostDonutChart(data.cost_breakdown);
            updateCategoryBarChart(data.category_breakdown);
            updateAccumulatedChart(data.accumulated_revenue);
        }

        /**
         * Update KPI cards with values and change indicators
         */
        function updateKpiCards(summary, comparison) {
            // Sales Count
            document.getElementById('kpi-sales-count').textContent = formatNumber(summary.total_sales_count);
            document.getElementById('kpi-sales-count-change').innerHTML = getChangeIndicatorHtml(comparison.sales_count);
            
            // Revenue
            document.getElementById('kpi-revenue').textContent = formatCurrency(summary.total_revenue);
            document.getElementById('kpi-revenue-change').innerHTML = getChangeIndicatorHtml(comparison.revenue);
            
            // Profit
            document.getElementById('kpi-profit').textContent = formatCurrency(summary.total_profit);
            document.getElementById('kpi-profit-change').innerHTML = getChangeIndicatorHtml(comparison.profit);
            
            // Cost (inverted colors - lower is better)
            document.getElementById('kpi-cost').textContent = formatCurrency(summary.total_cost);
            document.getElementById('kpi-cost-change').innerHTML = getChangeIndicatorHtml(comparison.cost, true);
        }

        /**
         * Update the period label text
         */
        function updatePeriodLabel(period) {
            const label = document.getElementById('trend-period-label');
            if (period.type === 'monthly') {
                const monthNames = ['January', 'February', 'March', 'April', 'May', 'June',
                                   'July', 'August', 'September', 'October', 'November', 'December'];
                label.textContent = `${monthNames[period.month - 1]} ${period.year}`;
            } else {
                label.textContent = `Year ${period.year}`;
            }
        }

        // ============================================================
        // CHART CONFIGURATIONS & UPDATES
        // ============================================================
        
        /**
         * Chart 1: Revenue Trend Line/Area Chart
         */
        function initRevenueTrendChart() {
            const ctx = document.getElementById('chart-revenue-trend').getContext('2d');
            
            DashboardState.charts.revenueTrend = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: [],
                    datasets: [{
                        label: 'Revenue',
                        data: [],
                        borderColor: DashboardConfig.colors.primary,
                        backgroundColor: DashboardConfig.colors.primaryLight,
                        borderWidth: 2,
                        fill: true,
                        tension: 0.4,
                        pointRadius: 4,
                        pointHoverRadius: 6,
                        pointBackgroundColor: DashboardConfig.colors.primary,
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            displayColors: false,
                            callbacks: {
                                label: function(context) {
                                    return 'Revenue: ' + formatCurrency(context.parsed.y);
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 12 }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: { size: 12 },
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateRevenueTrendChart(trendData) {
            const chart = DashboardState.charts.revenueTrend;
            if (!chart) return;

            chart.data.labels = trendData.labels;
            chart.data.datasets[0].data = trendData.data.map(item => item.revenue);
            chart.update('none');
        }

        /**
         * Chart 2: Cost Breakdown Donut Chart
         */
        function initCostDonutChart() {
            const ctx = document.getElementById('chart-cost-donut').getContext('2d');
            
            DashboardState.charts.costDonut = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Phones', 'Accessories', 'Services'],
                    datasets: [{
                        data: [0, 0, 0],
                        backgroundColor: [
                            DashboardConfig.categoryColors.phones.bg,
                            DashboardConfig.categoryColors.accessories.bg,
                            DashboardConfig.categoryColors.services.bg
                        ],
                        borderColor: [
                            DashboardConfig.categoryColors.phones.border,
                            DashboardConfig.categoryColors.accessories.border,
                            DashboardConfig.categoryColors.services.border
                        ],
                        borderWidth: 2,
                        hoverOffset: 10
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '60%',
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    const value = context.parsed;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                                    return `${context.label}: ${formatCurrency(value)} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateCostDonutChart(costData) {
            const chart = DashboardState.charts.costDonut;
            if (!chart) return;

            chart.data.labels = costData.labels;
            chart.data.datasets[0].data = costData.values;
            chart.update('none');

            // Update custom legend
            updateDonutLegend(costData);
        }

        function updateDonutLegend(costData) {
            const legendContainer = document.getElementById('donut-legend');
            const colors = ['bg-blue-500', 'bg-green-500', 'bg-purple-500'];
            
            let html = '';
            costData.breakdown.forEach((item, index) => {
                html += `
                    <div class="flex items-center">
                        <span class="w-3 h-3 rounded-full ${colors[index]} mr-2"></span>
                        <span class="text-sm text-gray-600">${item.category}: ${item.percentage}%</span>
                    </div>
                `;
            });
            
            legendContainer.innerHTML = html;
        }

        /**
         * Chart 3: Category Revenue Comparison Bar Chart
         */
        function initCategoryBarChart() {
            const ctx = document.getElementById('chart-category-bar').getContext('2d');
            
            DashboardState.charts.categoryBar = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: ['Phones', 'Accessories', 'Services'],
                    datasets: [
                        {
                            label: 'Revenue',
                            data: [0, 0, 0],
                            backgroundColor: DashboardConfig.colors.primary,
                            borderRadius: 6,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Cost',
                            data: [0, 0, 0],
                            backgroundColor: DashboardConfig.colors.danger,
                            borderRadius: 6,
                            barPercentage: 0.6
                        },
                        {
                            label: 'Profit',
                            data: [0, 0, 0],
                            backgroundColor: DashboardConfig.colors.success,
                            borderRadius: 6,
                            barPercentage: 0.6
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${formatCurrency(context.parsed.y)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 12 }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: { size: 12 },
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateCategoryBarChart(categoryData) {
            const chart = DashboardState.charts.categoryBar;
            if (!chart) return;

            const categories = ['phones', 'accessories', 'services'];
            
            chart.data.datasets[0].data = categories.map(cat => categoryData[cat].revenue);
            chart.data.datasets[1].data = categories.map(cat => categoryData[cat].cost);
            chart.data.datasets[2].data = categories.map(cat => categoryData[cat].profit);
            
            chart.update('none');
        }

        /**
         * Chart 4: Accumulated Revenue (Waterfall-style Stacked Bar)
         */
        function initAccumulatedChart() {
            const ctx = document.getElementById('chart-accumulated').getContext('2d');
            
            DashboardState.charts.accumulated = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: [],
                    datasets: [
                        {
                            label: 'Period Revenue',
                            data: [],
                            backgroundColor: DashboardConfig.colors.primary,
                            borderRadius: 4,
                            order: 2
                        },
                        {
                            label: 'Cumulative Total',
                            data: [],
                            type: 'line',
                            borderColor: DashboardConfig.colors.success,
                            backgroundColor: 'transparent',
                            borderWidth: 3,
                            pointRadius: 4,
                            pointBackgroundColor: DashboardConfig.colors.success,
                            pointBorderColor: '#fff',
                            pointBorderWidth: 2,
                            tension: 0.3,
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    interaction: {
                        mode: 'index',
                        intersect: false,
                    },
                    plugins: {
                        legend: {
                            position: 'top',
                            labels: {
                                usePointStyle: true,
                                padding: 20,
                                font: { size: 12 }
                            }
                        },
                        tooltip: {
                            backgroundColor: 'rgba(0, 0, 0, 0.8)',
                            titleFont: { size: 14 },
                            bodyFont: { size: 13 },
                            padding: 12,
                            callbacks: {
                                label: function(context) {
                                    return `${context.dataset.label}: ${formatCurrency(context.parsed.y)}`;
                                }
                            }
                        }
                    },
                    scales: {
                        x: {
                            grid: {
                                display: false
                            },
                            ticks: {
                                font: { size: 12 }
                            }
                        },
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            },
                            ticks: {
                                font: { size: 12 },
                                callback: function(value) {
                                    return formatCurrency(value);
                                }
                            }
                        }
                    }
                }
            });
        }

        function updateAccumulatedChart(accumulatedData) {
            const chart = DashboardState.charts.accumulated;
            if (!chart) return;

            chart.data.labels = accumulatedData.labels;
            chart.data.datasets[0].data = accumulatedData.period_values;
            chart.data.datasets[1].data = accumulatedData.accumulated_values;
            chart.update('none');
        }

        // ============================================================
        // EVENT HANDLERS
        // ============================================================
        
        function initEventHandlers() {
            // Monthly button
            document.getElementById('btn-monthly').addEventListener('click', function() {
                DashboardState.type = 'monthly';
                updateToggleButtons();
                document.getElementById('select-month').classList.remove('hidden');
                fetchDashboardData();
            });

            // Yearly button
            document.getElementById('btn-yearly').addEventListener('click', function() {
                DashboardState.type = 'yearly';
                updateToggleButtons();
                document.getElementById('select-month').classList.add('hidden');
                fetchDashboardData();
            });

            // Year selector
            document.getElementById('select-year').addEventListener('change', function(e) {
                DashboardState.year = parseInt(e.target.value);
                fetchDashboardData();
            });

            // Month selector
            document.getElementById('select-month').addEventListener('change', function(e) {
                DashboardState.month = parseInt(e.target.value);
                fetchDashboardData();
            });
        }

        function updateToggleButtons() {
            const btnMonthly = document.getElementById('btn-monthly');
            const btnYearly = document.getElementById('btn-yearly');

            if (DashboardState.type === 'monthly') {
                btnMonthly.classList.add('bg-indigo-600', 'text-white');
                btnMonthly.classList.remove('text-gray-600', 'hover:bg-gray-100');
                btnYearly.classList.remove('bg-indigo-600', 'text-white');
                btnYearly.classList.add('text-gray-600', 'hover:bg-gray-100');
            } else {
                btnYearly.classList.add('bg-indigo-600', 'text-white');
                btnYearly.classList.remove('text-gray-600', 'hover:bg-gray-100');
                btnMonthly.classList.remove('bg-indigo-600', 'text-white');
                btnMonthly.classList.add('text-gray-600', 'hover:bg-gray-100');
            }
        }

        // ============================================================
        // STAFF MANAGEMENT FUNCTIONS
        // ============================================================

        @if(auth()->user()->isAdmin())
        function openStaffModal() {
            document.getElementById('staff-modal').classList.remove('hidden');
            document.body.style.overflow = 'hidden';
            loadStaffMembers();
        }

        function closeStaffModal() {
            document.getElementById('staff-modal').classList.add('hidden');
            document.body.style.overflow = 'auto';
            resetStaffForm();
        }

        function loadStaffMembers() {
            fetch('/api/staff', {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                renderStaffTable(data);
            })
            .catch(error => {
                console.error('Error loading staff:', error);
                showStaffMessage('Error loading staff members', 'error');
            });
        }

        function renderStaffTable(staffMembers) {
            const tbody = document.getElementById('staff-table-body');
            
            if (staffMembers.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="4" class="px-4 py-8 text-center text-gray-500">
                            <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            <p class="mt-2">No staff members found</p>
                            <p class="text-sm">Add your first staff member using the form above.</p>
                        </td>
                    </tr>
                `;
                return;
            }

            tbody.innerHTML = staffMembers.map(staff => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-3">
                        <div class="flex items-center">
                            <div class="h-8 w-8 rounded-full bg-indigo-100 flex items-center justify-center">
                                <span class="text-sm font-medium text-indigo-600">${staff.name.charAt(0).toUpperCase()}</span>
                            </div>
                            <span class="ml-3 font-medium text-gray-900">${escapeHtml(staff.name)}</span>
                        </div>
                    </td>
                    <td class="px-4 py-3 text-gray-600">${escapeHtml(staff.email)}</td>
                    <td class="px-4 py-3 text-gray-500 text-sm">${formatDate(staff.created_at)}</td>
                    <td class="px-4 py-3">
                        <div class="flex items-center space-x-2">
                            <button onclick="editStaff(${staff.id}, '${escapeHtml(staff.name)}', '${escapeHtml(staff.email)}')" 
                                class="text-blue-600 hover:text-blue-800 p-1 rounded" title="Edit">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="resetStaffPassword(${staff.id}, '${escapeHtml(staff.name)}')" 
                                class="text-yellow-600 hover:text-yellow-800 p-1 rounded" title="Reset Password">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteStaff(${staff.id}, '${escapeHtml(staff.name)}')" 
                                class="text-red-600 hover:text-red-800 p-1 rounded" title="Delete">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-US', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        function saveStaff(event) {
            event.preventDefault();
            clearStaffErrors();

            const mode = document.getElementById('staff-mode').value;
            const id = document.getElementById('staff-id').value;
            const name = document.getElementById('staff-name').value.trim();
            const email = document.getElementById('staff-email').value.trim();
            const password = document.getElementById('staff-password').value;
            const passwordConfirm = document.getElementById('staff-password-confirm').value;

            // Validation
            let hasError = false;

            if (!name) {
                showFieldError('staff-name', 'Name is required');
                hasError = true;
            }

            if (mode === 'create' || mode === 'edit') {
                if (!email) {
                    showFieldError('staff-email', 'Email is required');
                    hasError = true;
                } else if (!isValidEmail(email)) {
                    showFieldError('staff-email', 'Please enter a valid email');
                    hasError = true;
                }
            }

            if (mode === 'create' || mode === 'reset-password') {
                if (!password) {
                    showFieldError('staff-password', 'Password is required');
                    hasError = true;
                } else if (password.length < 8) {
                    showFieldError('staff-password', 'Password must be at least 8 characters');
                    hasError = true;
                }

                if (password !== passwordConfirm) {
                    showFieldError('staff-password', 'Passwords do not match');
                    hasError = true;
                }
            }

            if (hasError) return;

            const url = mode === 'create' ? '/api/staff' : `/api/staff/${id}`;
            const method = mode === 'create' ? 'POST' : 'PUT';

            let bodyData = {};
            if (mode === 'create') {
                bodyData = { name, email, password, password_confirmation: passwordConfirm };
            } else if (mode === 'edit') {
                bodyData = { name, email };
            } else if (mode === 'reset-password') {
                bodyData = { password, password_confirmation: passwordConfirm, reset_password: true };
            }

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin',
                body: JSON.stringify(bodyData)
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStaffMessage(data.message || 'Staff saved successfully!', 'success');
                    resetStaffForm();
                    loadStaffMembers();
                } else if (data.errors) {
                    Object.keys(data.errors).forEach(field => {
                        showFieldError(`staff-${field}`, data.errors[field][0]);
                    });
                } else {
                    showStaffMessage(data.message || 'An error occurred', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStaffMessage('An error occurred while saving', 'error');
            });
        }

        function editStaff(id, name, email) {
            document.getElementById('staff-mode').value = 'edit';
            document.getElementById('staff-id').value = id;
            document.getElementById('staff-name').value = name;
            document.getElementById('staff-email').value = email;
            document.getElementById('staff-form-title').textContent = 'Edit Staff Member';
            document.getElementById('staff-submit-btn').textContent = 'Update Staff Member';
            document.getElementById('staff-cancel-btn').classList.remove('hidden');
            
            // Hide password fields for edit
            document.getElementById('password-field').classList.add('hidden');
            document.getElementById('password-confirm-field').classList.add('hidden');
            
            // Scroll to form
            document.getElementById('staff-form').scrollIntoView({ behavior: 'smooth' });
        }

        function resetStaffPassword(id, name) {
            if (!confirm(`Are you sure you want to reset the password for "${name}"?`)) return;

            document.getElementById('staff-mode').value = 'reset-password';
            document.getElementById('staff-id').value = id;
            document.getElementById('staff-form-title').textContent = `Reset Password for ${name}`;
            document.getElementById('staff-submit-btn').textContent = 'Reset Password';
            document.getElementById('staff-cancel-btn').classList.remove('hidden');
            
            // Hide name and email fields
            document.getElementById('name-field').classList.add('hidden');
            document.getElementById('email-field').classList.add('hidden');
            
            // Show password fields
            document.getElementById('password-field').classList.remove('hidden');
            document.getElementById('password-confirm-field').classList.remove('hidden');
            
            // Clear password fields
            document.getElementById('staff-password').value = '';
            document.getElementById('staff-password-confirm').value = '';
            
            // Scroll to form
            document.getElementById('staff-form').scrollIntoView({ behavior: 'smooth' });
        }

        function deleteStaff(id, name) {
            if (!confirm(`Are you sure you want to delete staff member "${name}"? This action cannot be undone.`)) return;

            fetch(`/api/staff/${id}`, {
                method: 'DELETE',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                credentials: 'same-origin'
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showStaffMessage(data.message || 'Staff member deleted successfully!', 'success');
                    loadStaffMembers();
                } else {
                    showStaffMessage(data.message || 'Error deleting staff member', 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showStaffMessage('An error occurred while deleting', 'error');
            });
        }

        function resetStaffForm() {
            document.getElementById('staff-form').reset();
            document.getElementById('staff-mode').value = 'create';
            document.getElementById('staff-id').value = '';
            document.getElementById('staff-form-title').textContent = 'Add New Staff Member';
            document.getElementById('staff-submit-btn').textContent = 'Create Staff Member';
            document.getElementById('staff-cancel-btn').classList.add('hidden');
            
            // Show all fields
            document.getElementById('name-field').classList.remove('hidden');
            document.getElementById('email-field').classList.remove('hidden');
            document.getElementById('password-field').classList.remove('hidden');
            document.getElementById('password-confirm-field').classList.remove('hidden');
            
            clearStaffErrors();
            hideStaffMessage();
        }

        function showStaffMessage(message, type) {
            const el = document.getElementById('staff-message');
            el.textContent = message;
            el.classList.remove('hidden', 'bg-green-100', 'text-green-800', 'bg-red-100', 'text-red-800');
            
            if (type === 'success') {
                el.classList.add('bg-green-100', 'text-green-800');
            } else {
                el.classList.add('bg-red-100', 'text-red-800');
            }
            
            // Auto-hide after 5 seconds
            setTimeout(() => hideStaffMessage(), 5000);
        }

        function hideStaffMessage() {
            document.getElementById('staff-message').classList.add('hidden');
        }

        function showFieldError(fieldId, message) {
            const errorEl = document.getElementById(`${fieldId}-error`);
            if (errorEl) {
                errorEl.textContent = message;
                errorEl.classList.remove('hidden');
            }
        }

        function clearStaffErrors() {
            ['staff-name-error', 'staff-email-error', 'staff-password-error'].forEach(id => {
                const el = document.getElementById(id);
                if (el) {
                    el.textContent = '';
                    el.classList.add('hidden');
                }
            });
        }

        function isValidEmail(email) {
            return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
        }
        @endif

        // ============================================================
        // INITIALIZATION
        // ============================================================
        
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize all charts
            initRevenueTrendChart();
            initCostDonutChart();
            initCategoryBarChart();
            initAccumulatedChart();
            
            // Initialize event handlers
            initEventHandlers();
            
            // Load initial data
            fetchDashboardData();
        });
    </script>
</x-app-layout>
