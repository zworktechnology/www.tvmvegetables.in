<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasePayment extends Model
{
    use HasFactory;


    protected $fillable = [
        'unique_key',
        'branch_id',
        'purchase_id',
        'supplier_id',
        'date',
        'time',
        'oldblance',
        'purchasepayment_discount',
        'purchasepayment_totalamount',
        'amount',
        'payment_pending',
        'soft_delete'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }
}
