<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'name',
        'shop_name',
        'address',
        'contact_number',
        'mail_address',
        'web_address',
        'gst_number',
        'logo',
        'status'
    ];

    public function purchase()
    {
        return $this->hasMany(Purchase::class, 'branch_id');
    }

    public function expence()
    {
        return $this->hasMany(Expence::class, 'branch_id');
    }

    public function purchasepayment()
    {
        return $this->hasMany(PurchasePayment::class, 'branch_id');
    }

    public function salespayment()
    {
        return $this->hasMany(Salespayment::class, 'branch_id');
    }
}
