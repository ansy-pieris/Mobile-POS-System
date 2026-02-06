<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'invoice_type',
        'customer_id',
        'customer_name',
        'customer_phone',
        'customer_nic',
        'user_id',        // salesperson
        'total_amount',
        'total_cost',
        'total_profit',
        'discount',
        'warranty_period',
        'payment_method',
        'bill_description',
        'issued_date',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
