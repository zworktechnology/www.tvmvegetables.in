<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseExtracost extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'purchase_id',
        'extracost_note',
        'extracost',
        'purchase_order',
        'soft_delete'
    ];
}
