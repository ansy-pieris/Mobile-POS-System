<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'type',      // product | service
        'parent_id', // for subcategories
    ];

    // Parent category (Samsung -> Phone)
    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    // Subcategories (Phone -> Samsung, Nokia)
    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    // Products under this category
    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
