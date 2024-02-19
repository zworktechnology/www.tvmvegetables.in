<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalesProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'sales_id',
        'date',
        'branch_id',
        'productlist_id',
        'bagorkg',
        'count',
        'note',
        'price_per_kg',
        'total_price',
        'sales_order',
        'status',
        'soft_delete'
    ];
}
