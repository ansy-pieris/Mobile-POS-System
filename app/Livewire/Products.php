<?php

namespace App\Livewire;

use App\Models\Product;
use App\Models\Category;
use Livewire\Component;
use Livewire\WithPagination;

class Products extends Component
{
    use WithPagination;

    public $product_code = '';
    public $name = '';
    public $category_id = '';
    public $price = '';
    public $stock_quantity = '';
    public $productId = null;
    public $isEditing = false;
    public $search = '';

    protected $rules = [
        'product_code' => 'required|string|max:255',
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'price' => 'required|numeric|min:0',
        'stock_quantity' => 'nullable|integer|min:0',
    ];

    public function render()
    {
        $query = Product::with('category');
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('product_code', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.products', [
            'products' => $query->latest()->paginate(10),
            'categories' => Category::all()
        ])->layout('layouts.app');
    }

    public function save()
    {
        $this->validate();

        if ($this->isEditing) {
            $product = Product::findOrFail($this->productId);
            $product->update([
                'product_code' => $this->product_code,
                'name' => $this->name,
                'category_id' => $this->category_id,
                'price' => $this->price,
                'stock_quantity' => $this->stock_quantity,
            ]);
            session()->flash('message', 'Product updated successfully.');
        } else {
            Product::create([
                'product_code' => $this->product_code,
                'name' => $this->name,
                'category_id' => $this->category_id,
                'price' => $this->price,
                'stock_quantity' => $this->stock_quantity,
            ]);
            session()->flash('message', 'Product created successfully.');
        }

        $this->reset(['product_code', 'name', 'category_id', 'price', 'stock_quantity', 'productId', 'isEditing']);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->product_code = $product->product_code;
        $this->name = $product->name;
        $this->category_id = $product->category_id;
        $this->price = $product->price;
        $this->stock_quantity = $product->stock_quantity;
        $this->isEditing = true;
    }

    public function delete($id)
    {
        // Only admin can delete products
        if (!auth()->user()->isAdmin()) {
            session()->flash('error', 'You are not permitted to delete products. Contact the owner.');
            return;
        }
        
        Product::findOrFail($id)->delete();
        session()->flash('message', 'Product deleted successfully.');
    }

    public function cancel()
    {
        $this->reset(['product_code', 'name', 'category_id', 'price', 'stock_quantity', 'productId', 'isEditing']);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
