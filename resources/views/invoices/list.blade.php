<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('All Invoices') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-lg font-semibold">Invoice History</h3>
                    <a href="{{ route('invoice.create') }}" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded">
                        + New Invoice
                    </a>
                </div>

                <!-- Invoice Type Tabs -->
                <div class="mb-6 border-b border-gray-200">
                    <nav class="flex gap-4" aria-label="Tabs">
                        <a href="{{ route('invoices.list', ['type' => 'all']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition-all {{ ($type ?? 'all') === 'all' ? 'border-indigo-500 text-indigo-600 bg-indigo-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            All Invoices
                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ ($type ?? 'all') === 'all' ? 'bg-indigo-100 text-indigo-600' : 'bg-gray-100 text-gray-600' }}">{{ $allCount ?? 0 }}</span>
                        </a>
                        <a href="{{ route('invoices.list', ['type' => 'product']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition-all {{ ($type ?? 'all') === 'product' ? 'border-blue-500 text-blue-600 bg-blue-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Product Invoices
                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ ($type ?? 'all') === 'product' ? 'bg-blue-100 text-blue-600' : 'bg-gray-100 text-gray-600' }}">{{ $productCount ?? 0 }}</span>
                        </a>
                        <a href="{{ route('invoices.list', ['type' => 'service']) }}" 
                           class="px-4 py-2 text-sm font-medium rounded-t-lg border-b-2 transition-all {{ ($type ?? 'all') === 'service' ? 'border-purple-500 text-purple-600 bg-purple-50' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' }}">
                            <svg class="w-4 h-4 inline mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Service Invoices
                            <span class="ml-2 px-2 py-0.5 text-xs rounded-full {{ ($type ?? 'all') === 'service' ? 'bg-purple-100 text-purple-600' : 'bg-gray-100 text-gray-600' }}">{{ $serviceCount ?? 0 }}</span>
                        </a>
                    </nav>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Type</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Invoice #</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Served By</th>
                                <th class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($invoices as $invoice)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-3 text-sm">
                                        @if($invoice->invoice_type === 'service')
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-purple-100 text-purple-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                </svg>
                                                Service
                                            </span>
                                        @else
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                                </svg>
                                                Product
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-4 py-3 text-sm font-medium">{{ $invoice->invoice_number }}</td>
                                    <td class="px-4 py-3 text-sm">{{ \Carbon\Carbon::parse($invoice->issued_date)->format('d M Y') }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        {{ $invoice->customer_name ?? ($invoice->customer->name ?? 'Walk-in') }}<br>
                                        <span class="text-xs text-gray-500">{{ $invoice->customer_phone ?? ($invoice->customer->phone ?? '') }}</span>
                                    </td>
                                    <td class="px-4 py-3 text-sm">
                                        <span class="px-2 py-1 rounded text-xs {{ $invoice->payment_method == 'cash' ? 'bg-green-100 text-green-800' : 'bg-blue-100 text-blue-800' }}">
                                            {{ strtoupper($invoice->payment_method) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-sm font-semibold">Rs.{{ number_format($invoice->total_amount, 2) }}</td>
                                    <td class="px-4 py-3 text-sm">{{ $invoice->user->name ?? 'N/A' }}</td>
                                    <td class="px-4 py-3 text-sm">
                                        <a href="{{ route('invoice.print', $invoice->id) }}" class="text-blue-600 hover:text-blue-900" target="_blank">
                                            Print/View
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="px-4 py-8 text-center text-gray-500">
                                        <svg class="w-12 h-12 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                        <p>No {{ ($type ?? 'all') !== 'all' ? ($type ?? '') . ' ' : '' }}invoices found.</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $invoices->appends(['type' => $type ?? 'all'])->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
