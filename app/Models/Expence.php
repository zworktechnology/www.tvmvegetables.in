<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expence extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'date',
        'time',
        'amount',
        'note',
        'branch_id',
        'soft_delete'
    ];

    public function branch()
    {
        return $this->belongsTo(Branch::class, 'branch_id');
    }
}
