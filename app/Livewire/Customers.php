<?php

namespace App\Livewire;

use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;

class Customers extends Component
{
    use WithPagination;

    public $search = '';

    public function render()
    {
        $query = Customer::query();
        
        if ($this->search) {
            $query->where(function($q) {
                $q->where('name', 'like', '%' . $this->search . '%')
                  ->orWhere('phone', 'like', '%' . $this->search . '%')
                  ->orWhere('nic', 'like', '%' . $this->search . '%');
            });
        }

        return view('livewire.customers', [
            'customers' => $query->latest()->paginate(10)
        ])->layout('layouts.app');
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }
}
