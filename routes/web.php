<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InvoiceController;
use App\Http\Controllers\DashboardController;
use App\Livewire\Products;
use App\Livewire\Customers;
use App\Livewire\CreateInvoice;
use App\Livewire\ShopSettings;
use App\Livewire\StaffManagement;

Route::get('/', function () {
    return redirect('/login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Dashboard Routes
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/api/dashboard/data', [DashboardController::class, 'getData'])->name('dashboard.api.data');
    Route::get('/api/dashboard/years', [DashboardController::class, 'getAvailableYears'])->name('dashboard.api.years');

    // Product Management (staff can add/edit, admin can delete)
    Route::get('/products', Products::class)->name('products');
    
    // Customer Management (staff view only, admin full access)
    Route::get('/customers', Customers::class)->name('customers');
    
    // Invoice/Billing (all authenticated users)
    Route::get('/invoice/create', CreateInvoice::class)->name('invoice.create');
    Route::get('/invoice/print/{id}', [InvoiceController::class, 'print'])->name('invoice.print');
    Route::get('/invoice/download/{id}', [InvoiceController::class, 'download'])->name('invoice.download');
    Route::get('/invoices', [InvoiceController::class, 'list'])->name('invoices.list');
    
    // Settings (all authenticated users can update shop details)
    Route::get('/settings', ShopSettings::class)->name('settings');

    // ===== ADMIN ONLY ROUTES =====
    Route::middleware(['admin'])->group(function () {
        // Staff Management (admin only)
        Route::get('/staff', StaffManagement::class)->name('staff');
    });
});

