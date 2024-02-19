<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'name',
        'contact_number',
        'email_address',
        'shop_name',
        'shop_address',
        'shop_contact_number',
        'old_balance',
        'status',
        'soft_delete'
    ];

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }

    public function purchasepayment()
    {
        return $this->hasMany(PurchasePayment::class, 'supplier_id');
    }
}
