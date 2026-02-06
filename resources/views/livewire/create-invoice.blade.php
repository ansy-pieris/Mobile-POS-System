<div>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Create Invoice') }}
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if (session()->has('message'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('message') }}</span>
                </div>
            @endif

            @if (session()->has('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <!-- Invoice Type Toggle -->
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Select Invoice Type</h3>
                <div class="flex gap-4">
                    <button 
                        type="button"
                        wire:click="setInvoiceType('product')"
                        class="flex-1 p-6 rounded-xl border-2 transition-all {{ $invoice_type === 'product' ? 'border-blue-500 bg-blue-50' : 'border-gray-200 hover:border-gray-300' }}"
                    >
                        <div class="flex flex-col items-center">
                            <div class="p-4 rounded-full {{ $invoice_type === 'product' ? 'bg-blue-100' : 'bg-gray-100' }} mb-3">
                                <svg class="w-10 h-10 {{ $invoice_type === 'product' ? 'text-blue-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <span class="text-lg font-semibold {{ $invoice_type === 'product' ? 'text-blue-700' : 'text-gray-700' }}">Product Invoice</span>
                            <span class="text-sm text-gray-500 mt-1">For selling phones & accessories</span>
                        </div>
                    </button>
                    
                    <button 
                        type="button"
                        wire:click="setInvoiceType('service')"
                        class="flex-1 p-6 rounded-xl border-2 transition-all {{ $invoice_type === 'service' ? 'border-purple-500 bg-purple-50' : 'border-gray-200 hover:border-gray-300' }}"
                    >
                        <div class="flex flex-col items-center">
                            <div class="p-4 rounded-full {{ $invoice_type === 'service' ? 'bg-purple-100' : 'bg-gray-100' }} mb-3">
                                <svg class="w-10 h-10 {{ $invoice_type === 'service' ? 'text-purple-600' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <span class="text-lg font-semibold {{ $invoice_type === 'service' ? 'text-purple-700' : 'text-gray-700' }}">Service Invoice</span>
                            <span class="text-sm text-gray-500 mt-1">For repairs & services</span>
                        </div>
                    </button>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <!-- Left Panel - Add Items -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Customer Details Card -->
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                            </svg>
                            Customer Details
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Customer Name</label>
                                <input type="text" wire:model="customer_name" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Enter customer name">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Phone Number</label>
                                <input type="text" wire:model="customer_phone" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="07X XXX XXXX">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">NIC Number</label>
                                <input type="text" wire:model="customer_nic" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="XXXXXXXXXX">
                            </div>
                        </div>
                        
                        <!-- Invoice Date & Time -->
                        <div class="grid grid-cols-2 gap-4 mt-4 pt-4 border-t border-gray-200">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Date</label>
                                <input type="date" wire:model="invoice_date" class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Invoice Time</label>
                                <input type="text" value="{{ now()->format('H:i:s') }}" class="w-full rounded-md border-gray-300 bg-gray-50 shadow-sm text-gray-600" readonly>
                            </div>
                        </div>
                    </div>

                    <!-- Product Form (shown when invoice_type is 'product') -->
                    @if($invoice_type === 'product')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                            </svg>
                            Add Product
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Product Search -->
                            <div class="md:col-span-2 relative">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Search Product *</label>
                                <input 
                                    type="text" 
                                    wire:model.live="product_search"
                                    class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" 
                                    placeholder="Type product name to search..."
                                    autocomplete="off"
                                >
                                
                                <!-- Product Dropdown -->
                                @if(count($filteredProducts) > 0)
                                <div class="absolute z-50 w-full mt-1 bg-white border border-gray-300 rounded-md shadow-lg max-h-60 overflow-auto">
                                    @foreach($filteredProducts as $product)
                                        <div 
                                            wire:key="product-{{ $product['id'] }}"
                                            wire:click="selectProduct({{ $product['id'] }})"
                                            class="px-4 py-3 hover:bg-indigo-50 cursor-pointer border-b last:border-b-0"
                                        >
                                            <div class="flex justify-between items-center">
                                                <div>
                                                    <span class="font-medium text-gray-800">{{ $product['name'] }}</span>
                                                    <span class="text-gray-500 text-sm ml-2">[{{ $product['product_code'] }}]</span>
                                                </div>
                                                <div class="text-right">
                                                    <span class="text-green-600 font-bold">Rs.{{ number_format($product['price'], 2) }}</span>
                                                    <span class="text-gray-400 text-sm block">Stock: {{ $product['stock_quantity'] }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                @endif

                                @if($selected_product_id)
                                    <div class="mt-2 p-2 bg-green-50 border border-green-200 rounded-md flex items-center justify-between">
                                        <span class="text-green-800 font-medium">
                                            <svg class="w-4 h-4 inline mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            </svg>
                                            {{ $selected_product_name }} [{{ $selected_product_code }}]
                                        </span>
                                        <button type="button" wire:click="resetItemForm" class="text-gray-500 hover:text-red-600">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                @endif
                                @error('selected_product_id') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Quantity -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Quantity *</label>
                                <input type="number" wire:model.live="item_quantity" min="1" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="1">
                                @error('item_quantity') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Unit Price -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Unit Price *</label>
                                <input type="number" step="0.01" wire:model.live="item_unit_price" class="w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0.00">
                                @error('item_unit_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Warranty -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Warranty</label>
                                <div class="flex flex-wrap gap-3">
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" wire:model="item_warranty" value="no" class="form-radio text-indigo-600">
                                        <span class="ml-2 text-sm">No</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" wire:model="item_warranty" value="3" class="form-radio text-indigo-600">
                                        <span class="ml-2 text-sm">3 Months</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" wire:model="item_warranty" value="6" class="form-radio text-indigo-600">
                                        <span class="ml-2 text-sm">6 Months</span>
                                    </label>
                                    <label class="inline-flex items-center cursor-pointer">
                                        <input type="radio" wire:model="item_warranty" value="12" class="form-radio text-indigo-600">
                                        <span class="ml-2 text-sm">12 Months</span>
                                    </label>
                                </div>
                            </div>

                            <!-- Discount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
                                <div class="flex gap-2">
                                    <input type="number" step="0.01" wire:model.live="item_discount" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="0">
                                    <select wire:model.live="item_discount_type" class="rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        <option value="value">Rs.</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Add to Invoice Button -->
                        <div class="mt-6 flex items-center justify-between border-t pt-4">
                            <div class="text-lg">
                                <span class="text-gray-600">Item Total:</span>
                                <span class="font-bold text-green-600 ml-2">Rs.{{ number_format($this->calculateItemTotal(), 2) }}</span>
                            </div>
                            <button 
                                type="button" 
                                wire:click="addItem"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add to Invoice
                            </button>
                        </div>
                    </div>
                    @endif

                    <!-- Service Form (shown when invoice_type is 'service') -->
                    @if($invoice_type === 'service')
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                            </svg>
                            Add Service
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <!-- Service Type -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Service Type *</label>
                                <select wire:model.live="service_type" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                    <option value="">Select Service Type</option>
                                    @foreach($serviceTypes as $key => $label)
                                        <option value="{{ $key }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                                @error('service_type') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Service Charge -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Service Charge (Rs.) *</label>
                                <input type="number" step="0.01" wire:model.live="service_charge" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="0.00">
                                @error('service_charge') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Service Description -->
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-1">Service Description *</label>
                                <textarea wire:model="service_description" rows="3" class="w-full rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="Describe the service performed (e.g., iPhone 13 screen replacement with original parts)"></textarea>
                                @error('service_description') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>

                            <!-- Discount -->
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Discount</label>
                                <div class="flex gap-2">
                                    <input type="number" step="0.01" wire:model.live="service_discount" class="flex-1 rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500" placeholder="0">
                                    <select wire:model.live="service_discount_type" class="rounded-md border-gray-300 shadow-sm focus:border-purple-500 focus:ring-purple-500">
                                        <option value="value">Rs.</option>
                                        <option value="percent">%</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <!-- Add Service Button -->
                        <div class="mt-6 flex items-center justify-between border-t pt-4">
                            <div class="text-lg">
                                <span class="text-gray-600">Service Total:</span>
                                <span class="font-bold text-purple-600 ml-2">Rs.{{ number_format($this->calculateServiceTotal(), 2) }}</span>
                            </div>
                            <button 
                                type="button" 
                                wire:click="addServiceItem"
                                class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-8 rounded-lg shadow-lg transition-all flex items-center gap-2"
                            >
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                </svg>
                                Add Service
                            </button>
                        </div>
                    </div>
                    @endif
                </div>

                <!-- Right Panel - Invoice Summary -->
                <div class="lg:col-span-1">
                    <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 sticky top-6">
                        <h3 class="text-lg font-semibold text-gray-800 mb-4 flex items-center gap-2">
                            <svg class="w-5 h-5 {{ $invoice_type === 'service' ? 'text-purple-600' : 'text-blue-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            {{ $invoice_type === 'service' ? 'Service' : 'Product' }} Invoice ({{ count($items) }})
                        </h3>

                        @if(count($items) > 0)
                            <div class="space-y-3 max-h-[400px] overflow-y-auto mb-4">
                                @foreach($items as $index => $item)
                                    <div class="border rounded-lg p-3 {{ ($item['type'] ?? 'product') === 'service' ? 'bg-purple-50 border-purple-200' : 'bg-gray-50' }} relative group">
                                        <button 
                                            wire:click="removeItem({{ $index }})" 
                                            class="absolute top-2 right-2 text-red-500 hover:text-red-700"
                                        >
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            </svg>
                                        </button>
                                        <div class="pr-6">
                                            @if(($item['type'] ?? 'product') === 'service')
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800">
                                                        {{ $item['service_label'] ?? 'Service' }}
                                                    </span>
                                                </div>
                                            @endif
                                            <div class="font-medium text-gray-800">{{ $item['description'] }}</div>
                                            <div class="text-xs text-gray-500">[{{ $item['product_code'] }}]</div>
                                            <div class="flex justify-between items-center mt-2 text-sm">
                                                <span class="text-gray-600">
                                                    Rs.{{ number_format($item['price'], 2) }} × {{ $item['quantity'] }}
                                                </span>
                                                <span class="font-bold {{ ($item['type'] ?? 'product') === 'service' ? 'text-purple-600' : 'text-green-600' }}">Rs.{{ number_format($item['total'], 2) }}</span>
                                            </div>
                                            @if($item['discount_amount'] > 0)
                                                <div class="text-xs text-red-500 mt-1">
                                                    Discount: -Rs.{{ number_format($item['discount_amount'], 2) }}
                                                </div>
                                            @endif
                                            @if(isset($item['warranty_months']) && $item['warranty_months'] > 0)
                                                <div class="text-xs text-indigo-600 mt-1">
                                                    {{ $item['warranty_months'] }} Months Warranty
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <!-- Payment Method -->
                            <div class="border-t pt-4 mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Payment Method</label>
                                <div class="flex gap-4">
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" wire:model="payment_method" value="cash" class="sr-only peer">
                                        <div class="flex items-center justify-center gap-2 p-3 border-2 rounded-lg peer-checked:border-green-500 peer-checked:bg-green-50 hover:bg-gray-50 transition-all">
                                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                            </svg>
                                            <span class="font-medium">Cash</span>
                                        </div>
                                    </label>
                                    <label class="flex-1 cursor-pointer">
                                        <input type="radio" wire:model="payment_method" value="card" class="sr-only peer">
                                        <div class="flex items-center justify-center gap-2 p-3 border-2 rounded-lg peer-checked:border-blue-500 peer-checked:bg-blue-50 hover:bg-gray-50 transition-all">
                                            <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                            <span class="font-medium">Card</span>
                                        </div>
                                    </label>
                                </div>
                            </div>

                            <!-- Totals -->
                            <div class="border-t pt-4 space-y-2">
                                <div class="flex justify-between text-sm">
                                    <span class="text-gray-600">Subtotal:</span>
                                    <span>Rs.{{ number_format($subtotal, 2) }}</span>
                                </div>
                                @if($totalDiscount > 0)
                                    <div class="flex justify-between text-sm text-red-600">
                                        <span>Total Discount:</span>
                                        <span>-Rs.{{ number_format($totalDiscount, 2) }}</span>
                                    </div>
                                @endif
                                <div class="flex justify-between text-xl font-bold border-t pt-3 mt-2">
                                    <span>Total:</span>
                                    <span class="{{ $invoice_type === 'service' ? 'text-purple-600' : 'text-green-600' }}">Rs.{{ number_format($total, 2) }}</span>
                                </div>
                            </div>

                            <!-- Save & Print Button -->
                            <button 
                                type="button" 
                                wire:click="save" 
                                class="w-full mt-6 {{ $invoice_type === 'service' ? 'bg-purple-500 hover:bg-purple-600' : 'bg-orange-500 hover:bg-orange-600' }} text-white font-bold py-4 px-4 rounded-lg shadow-xl transition-all flex items-center justify-center gap-2 text-lg"
                            >
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                                </svg>
                                SAVE & PRINT {{ strtoupper($invoice_type) }} INVOICE
                            </button>
                        @else
                            <div class="text-center py-12 text-gray-400">
                                <svg class="w-16 h-16 mx-auto mb-4 {{ $invoice_type === 'service' ? 'text-purple-300' : 'text-gray-300' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    @if($invoice_type === 'service')
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    @else
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                    @endif
                                </svg>
                                <p class="text-lg">No {{ $invoice_type === 'service' ? 'services' : 'items' }} added yet</p>
                                <p class="text-sm mt-1">{{ $invoice_type === 'service' ? 'Add a service above' : 'Search and add products above' }}</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Invoice Preview Modal -->
    @if($showPreview && $savedInvoiceId)
        <div class="fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
            <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden flex flex-col">
                <!-- Modal Header -->
                <div class="bg-gradient-to-r {{ $invoice_type === 'service' ? 'from-purple-600 to-purple-700' : 'from-green-600 to-green-700' }} text-white px-6 py-4 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <h3 class="text-2xl font-bold">{{ ucfirst($invoice_type) }} Invoice Created!</h3>
                            <p class="{{ $invoice_type === 'service' ? 'text-purple-100' : 'text-green-100' }} text-sm">Ready to print</p>
                        </div>
                    </div>
                    <button wire:click="closePreview" class="text-white hover:text-red-200 transition-colors">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <!-- Invoice Preview -->
                <div class="flex-1 overflow-auto p-6 bg-gray-50">
                    <iframe 
                        src="{{ route('invoice.print', $savedInvoiceId) }}" 
                        class="w-full h-[500px] bg-white rounded-lg shadow-lg border-2 border-gray-200"
                        frameborder="0"
                    ></iframe>
                </div>

                <!-- Action Buttons -->
                <div class="bg-white border-t border-gray-200 px-6 py-4 flex gap-4">
                    <button 
                        type="button"
                        onclick="var w = window.open('{{ route('invoice.print', $savedInvoiceId) }}', '_blank'); setTimeout(function(){ w.print(); }, 500);"
                        class="flex-1 {{ $invoice_type === 'service' ? 'bg-purple-600 hover:bg-purple-700' : 'bg-blue-600 hover:bg-blue-700' }} text-white font-bold py-4 px-6 rounded-xl shadow-lg transition-all flex items-center justify-center gap-3"
                    >
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                        </svg>
                        <span class="text-lg">PRINT INVOICE</span>
                    </button>
                    
                    <button 
                        type="button"
                        wire:click="closePreview"
                        class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-all flex items-center justify-center gap-2"
                    >
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        <span class="text-lg">NEW INVOICE</span>
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
