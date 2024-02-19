<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expensedetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_id',
        'expense_note',
        'expense_amount'
    ];
}
