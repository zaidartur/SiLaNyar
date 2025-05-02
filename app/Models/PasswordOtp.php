<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PasswordOtp extends Model
{
    use HasFactory;

    protected $table = 'password_otp';

    protected $fillable = [
        'identitas',
        'otp',
        'via',
        'expired_at',
    ];

    protected $dates = ['expired_at'];
}
