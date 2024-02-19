<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'productlist_id',
        'branchtable_id',
        'description',
        'available_stockin_bag',
        'available_stockin_kilograms',
        'status',
        'soft_delete'
    ];
}
