<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salespayment extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'branch_id',
        'sales_id',
        'customer_id',
        'date',
        'time',
        'oldblance',
        'salespayment_discount',
        'salespayment_totalamount',
        'amount',
        'payment_pending',
        'soft_delete'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
}
