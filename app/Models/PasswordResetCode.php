<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordResetCode extends Model
{
    protected $fillable = ['email', 'code', 'is_used'];
    public $timestamps = false;
}
