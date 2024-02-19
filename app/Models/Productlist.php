<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Productlist extends Model
{
    use HasFactory;

    protected $fillable = [
        'unique_key',
        'name',
        'status',
        'soft_delete'
    ];
}
