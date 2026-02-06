<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'quantity_change',
        'stock_after',
        'type', // sale | add | update
        'reference',
        'note',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
