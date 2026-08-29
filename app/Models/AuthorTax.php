<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'rate',
        'countries',
    ];

    protected $casts = [
        'countries' => 'array',
    ];
}
