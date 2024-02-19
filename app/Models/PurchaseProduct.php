<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'purchase_id',
        'date',
        'branch_id',
        'productlist_id',
        'bagorkg',
        'count',
        'note',
        'price_per_kg',
        'total_price',
        'purchase_order',
        'status',
        'soft_delete'
    ];

}
