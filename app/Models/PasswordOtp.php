<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            // Set expired_at berdasarkan created_at yang akan dibuat
            $now = Carbon::now();
            $model->created_at = $now;
            $model->expired_at = $now->copy()->addMinutes(15);
        });
    }
}
