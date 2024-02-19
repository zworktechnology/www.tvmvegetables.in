<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'customer_id',
        'branch_id',
        'date',
        'time',
        'bill_no',
        'bank_id',
        'total_amount',
        'note',
        'extra_cost',
        'gross_amount',
        'old_balance',
        'grand_total',
        'paid_amount',
        'balance_amount',
        'sales_payment_id',
        'sales_paymentpaidamount',
        'sales_paymentpending',
        'sales_order',
        'status',
        'soft_delete'
    ];
}
