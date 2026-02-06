<?php

namespace App\Livewire;

use App\Models\Invoice;
use App\Models\InvoiceItem;
use App\Models\Customer;
use App\Models\Product;
use App\Models\StockLog;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class CreateInvoice extends Component
{
    // Invoice Type: 'product' or 'service'
    public $invoice_type = 'product';
    
    // Customer Fields
    public $customer_name = '';
    public $customer_phone = '';
    public $customer_nic = '';
    
    // Invoice Fields
    public $invoice_date = '';
    public $invoice_time = '';
    public $payment_method = 'cash';
    
    // Product Search & Selection (for product invoices)
    public $product_search = '';
    public $filteredProducts = [];
    public $showProductDropdown = false;
    
    // Current Product Item Being Added
    public $selected_product_id = '';
    public $selected_product_name = '';
    public $selected_product_code = '';
    public $item_quantity = 1;
    public $item_unit_price = '';
    public $item_warranty = 'no'; // 'no', '3', '6', '12'
    public $item_discount = '';
    public $item_discount_type = 'value'; // 'value' or 'percent'
    
    // Service Fields (for service invoices)
    public $service_type = '';
    public $service_description = '';
    public $service_charge = '';
    public $service_discount = '';
    public $service_discount_type = 'value';
    
    // Service Types Available
    public $serviceTypes = [
        'screen_repair' => 'Screen Repair',
        'battery_replacement' => 'Battery Replacement',
        'charging_port' => 'Charging Port Repair',
        'software_update' => 'Software Update',
        'data_recovery' => 'Data Recovery',
        'water_damage' => 'Water Damage Repair',
        'speaker_repair' => 'Speaker Repair',
        'camera_repair' => 'Camera Repair',
        'button_repair' => 'Button Repair',
        'other' => 'Other Service',
    ];
    
    // Invoice Items
    public $items = [];
    
    // Modal State
    public $savedInvoiceId = null;
    public $showPreview = false;

    protected $rules = [
        'payment_method' => 'required|in:cash,card',
        'items' => 'required|array|min:1',
    ];

    public function mount()
    {
        $this->items = [];
        $this->invoice_date = now()->format('Y-m-d');
        $this->invoice_time = '';
        $this->filteredProducts = [];
    }

    public function render()
    {
        return view('livewire.create-invoice', [
            'subtotal' => $this->calculateSubtotal(),
            'totalDiscount' => $this->calculateTotalDiscount(),
            'total' => $this->calculateTotal(),
        ])->layout('layouts.app');
    }

    public function setInvoiceType($type)
    {
        $this->invoice_type = $type;
        $this->items = []; // Clear items when switching types
        $this->resetItemForm();
        $this->resetServiceForm();
    }

    public function updatedProductSearch($value)
    {
        $this->searchProducts();
    }

    public function searchProducts()
    {
        if (strlen($this->product_search) >= 1) {
            $products = Product::where('stock_quantity', '>', 0)
                ->where(function($query) {
                    $query->where('name', 'like', '%' . $this->product_search . '%')
                          ->orWhere('product_code', 'like', '%' . $this->product_search . '%');
                })
                ->limit(10)
                ->get();
            
            $this->filteredProducts = [];
            foreach ($products as $product) {
                $this->filteredProducts[] = [
                    'id' => $product->id,
                    'name' => $product->name,
                    'product_code' => $product->product_code,
                    'price' => $product->price,
                    'stock_quantity' => $product->stock_quantity,
                ];
            }
        } else {
            $this->filteredProducts = [];
        }
    }

    public function selectProduct($productId)
    {
        $product = Product::find($productId);
        if ($product) {
            $this->selected_product_id = $product->id;
            $this->selected_product_name = $product->name;
            $this->selected_product_code = $product->product_code;
            $this->item_unit_price = $product->price;
            $this->product_search = $product->name;
            $this->filteredProducts = [];
            $this->showProductDropdown = false;
        }
    }

    public function hideProductDropdown()
    {
        $this->showProductDropdown = false;
    }

    public function calculateItemTotal()
    {
        $price = floatval($this->item_unit_price ?? 0);
        $qty = intval($this->item_quantity ?? 1);
        $discount = floatval($this->item_discount ?? 0);
        
        $subtotal = $price * $qty;
        
        if ($this->item_discount_type === 'percent') {
            $discountAmount = ($subtotal * $discount) / 100;
        } else {
            $discountAmount = $discount;
        }
        
        return max(0, $subtotal - $discountAmount);
    }

    public function calculateServiceTotal()
    {
        $charge = floatval($this->service_charge ?? 0);
        $discount = floatval($this->service_discount ?? 0);
        
        if ($this->service_discount_type === 'percent') {
            $discountAmount = ($charge * $discount) / 100;
        } else {
            $discountAmount = $discount;
        }
        
        return max(0, $charge - $discountAmount);
    }

    public function addItem()
    {
        $this->validate([
            'selected_product_id' => 'required|exists:products,id',
            'item_quantity' => 'required|integer|min:1',
            'item_unit_price' => 'required|numeric|min:0',
        ]);

        $product = Product::with('category')->findOrFail($this->selected_product_id);
        
        // Check stock availability
        if ($product->stock_quantity < $this->item_quantity) {
            session()->flash('error', 'Insufficient stock. Only ' . $product->stock_quantity . ' available.');
            return;
        }

        // Determine sale_category based on product's category
        $saleCategory = 'accessories';
        if ($product->category) {
            $categoryName = strtolower($product->category->name);
            if (str_contains($categoryName, 'phone')) {
                $saleCategory = 'phones';
            } elseif (str_contains($categoryName, 'service')) {
                $saleCategory = 'services';
            } elseif (str_contains($categoryName, 'accessor')) {
                $saleCategory = 'accessories';
            }
        }

        $price = floatval($this->item_unit_price);
        $qty = intval($this->item_quantity);
        $discount = floatval($this->item_discount ?? 0);
        
        $subtotal = $price * $qty;
        
        if ($this->item_discount_type === 'percent') {
            $discountAmount = ($subtotal * $discount) / 100;
        } else {
            $discountAmount = $discount;
        }
        
        $total = max(0, $subtotal - $discountAmount);
        $warrantyMonths = $this->item_warranty === 'no' ? 0 : intval($this->item_warranty);

        $this->items[] = [
            'type' => 'product',
            'product_id' => $product->id,
            'product_code' => $product->product_code,
            'description' => $product->name,
            'service_type' => null,
            'price' => $price,
            'quantity' => $qty,
            'discount' => $discount,
            'discount_type' => $this->item_discount_type,
            'discount_amount' => $discountAmount,
            'warranty_months' => $warrantyMonths,
            'total' => $total,
            'cost_price' => $product->cost_price ?? 0,
            'sale_category' => $saleCategory,
        ];

        $this->resetItemForm();
    }

    public function addServiceItem()
    {
        $this->validate([
            'service_type' => 'required',
            'service_description' => 'required|string|min:3',
            'service_charge' => 'required|numeric|min:0',
        ]);

        $charge = floatval($this->service_charge);
        $discount = floatval($this->service_discount ?? 0);
        
        if ($this->service_discount_type === 'percent') {
            $discountAmount = ($charge * $discount) / 100;
        } else {
            $discountAmount = $discount;
        }
        
        $total = max(0, $charge - $discountAmount);
        $serviceLabel = $this->serviceTypes[$this->service_type] ?? $this->service_type;

        $this->items[] = [
            'type' => 'service',
            'product_id' => null,
            'product_code' => 'SVC-' . strtoupper(substr($this->service_type, 0, 3)),
            'description' => $this->service_description,
            'service_type' => $this->service_type,
            'service_label' => $serviceLabel,
            'price' => $charge,
            'quantity' => 1,
            'discount' => $discount,
            'discount_type' => $this->service_discount_type,
            'discount_amount' => $discountAmount,
            'warranty_months' => 0,
            'total' => $total,
            'cost_price' => 0,
            'sale_category' => 'services',
        ];

        $this->resetServiceForm();
    }

    public function resetItemForm()
    {
        $this->selected_product_id = '';
        $this->selected_product_name = '';
        $this->selected_product_code = '';
        $this->product_search = '';
        $this->item_quantity = 1;
        $this->item_unit_price = '';
        $this->item_warranty = 'no';
        $this->item_discount = '';
        $this->item_discount_type = 'value';
        $this->filteredProducts = [];
    }

    public function resetServiceForm()
    {
        $this->service_type = '';
        $this->service_description = '';
        $this->service_charge = '';
        $this->service_discount = '';
        $this->service_discount_type = 'value';
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function calculateSubtotal()
    {
        return collect($this->items)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
    }

    public function calculateTotalDiscount()
    {
        return collect($this->items)->sum('discount_amount');
    }

    public function calculateTotal()
    {
        return collect($this->items)->sum('total');
    }

    public function save()
    {
        if (empty($this->items)) {
            session()->flash('error', 'Please add at least one item to the invoice.');
            return;
        }

        try {
            DB::beginTransaction();

            // Handle customer - create or update existing
            $customerId = null;
            if (!empty($this->customer_phone)) {
                $customer = Customer::updateOrCreate(
                    ['phone' => $this->customer_phone],
                    [
                        'name' => $this->customer_name ?: 'Customer',
                        'nic' => $this->customer_nic ?: null,
                    ]
                );
                $customerId = $customer->id;
            }

            // Generate invoice number based on type
            $lastInvoice = Invoice::latest('id')->first();
            $prefix = $this->invoice_type === 'service' ? 'SVC' : 'INV';
            $invoiceNumber = $prefix . '-' . date('Ymd') . '-' . str_pad(($lastInvoice ? $lastInvoice->id + 1 : 1), 4, '0', STR_PAD_LEFT);

            // Calculate totals
            $totalAmount = $this->calculateTotal();
            $totalCost = collect($this->items)->sum(function($item) {
                return ($item['cost_price'] ?? 0) * $item['quantity'];
            });
            $totalProfit = $totalAmount - $totalCost;
            $totalDiscount = $this->calculateTotalDiscount();

            // Create invoice
            $invoice = Invoice::create([
                'invoice_number' => $invoiceNumber,
                'invoice_type' => $this->invoice_type,
                'customer_id' => $customerId,
                'customer_name' => $this->customer_name ?: 'Walk-in Customer',
                'customer_phone' => $this->customer_phone ?: null,
                'customer_nic' => $this->customer_nic ?: null,
                'user_id' => auth()->id(),
                'total_amount' => $totalAmount,
                'total_cost' => $totalCost,
                'total_profit' => $totalProfit,
                'discount' => $totalDiscount,
                'warranty_period' => '0',
                'payment_method' => $this->payment_method,
                'bill_description' => '',
                'issued_date' => now()->toDateString(),
            ]);

            // Create invoice items and update stock
            foreach ($this->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item['product_id'],
                    'description' => $item['description'],
                    'service_type' => $item['service_type'] ?? null,
                    'price' => $item['price'],
                    'cost_price' => $item['cost_price'] ?? 0,
                    'quantity' => $item['quantity'],
                    'warranty_months' => $item['warranty_months'],
                    'discount' => $item['discount'],
                    'discount_type' => $item['discount_type'],
                    'total' => $item['total'],
                    'profit' => $item['total'] - (($item['cost_price'] ?? 0) * $item['quantity']),
                    'sale_category' => $item['sale_category'] ?? 'services',
                ]);

                // Update stock only for products
                if ($item['type'] === 'product' && $item['product_id']) {
                    $product = Product::find($item['product_id']);
                    if ($product && $product->stock_quantity !== null) {
                        $newStock = $product->stock_quantity - $item['quantity'];
                        $product->update(['stock_quantity' => $newStock]);

                        StockLog::create([
                            'product_id' => $product->id,
                            'quantity_change' => -$item['quantity'],
                            'stock_after' => $newStock,
                            'type' => 'sale',
                            'reference' => $invoiceNumber,
                        ]);
                    }
                }
            }

            DB::commit();

            $this->savedInvoiceId = $invoice->id;
            $this->showPreview = true;
            session()->flash('message', 'Invoice created successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Error creating invoice: ' . $e->getMessage());
        }
    }

    public function closePreview()
    {
        $this->showPreview = false;
        $this->savedInvoiceId = null;
        $this->reset(['items', 'customer_name', 'customer_phone', 'customer_nic', 'payment_method']);
        $this->resetItemForm();
        $this->resetServiceForm();
        $this->invoice_date = now()->format('Y-m-d');
        $this->invoice_time = '';
    }
}
