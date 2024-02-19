<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bank extends Model
{
    use HasFactory;


    protected $fillable = [
        'unique_key',
        'name',
        'details',
        'status',
        'soft_delete'
    ];
}
