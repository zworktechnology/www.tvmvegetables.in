<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'supplier_id',
        'branch_id',
        'date',
        'time',
        'bill_no',
        'bank_id',
        'total_amount',

        'commission_ornet',
        'commission_percent',
        'commission_amount',

        'note',
        'tot_comm_extracost',
        'gross_amount',
        'old_balance',
        'grand_total',
        'paid_amount',
        'balance_amount',
        'purchase_payment_id',
        'payment_paid_amount',
        'payment_pending',
        'paid_status',
        'purchase_order',
        'purchase_remark',
        'status',
        'soft_delete'
    ];


    public function supplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }


    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
