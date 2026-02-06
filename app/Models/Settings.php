<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    use HasFactory;

    protected $fillable = [
        'shop_name',
        'address',
        'location',
        'phone',
        'email',
        'logo',
        'terms_conditions',
    ];
}
