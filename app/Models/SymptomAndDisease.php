<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SymptomAndDisease extends Model
{
    use HasFactory;

    protected $fillable = ['disease', 'symptoms'];

    protected $casts = [
        'symptoms' => 'array'
    ];
}
