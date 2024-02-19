<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchwiseBalance extends Model
{
    use HasFactory;


    protected $fillable = [
        'supplier_id',
        'branch_id',
        'customer_id',
        'purchase_amount',
        'purchase_paid',
        'purchase_balance',
        'sales_amount',
        'sale_discount',
        'sales_paid',
        'sales_balance'
    ];

}
