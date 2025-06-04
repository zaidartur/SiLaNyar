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

    protected $casts = [
        'expired_at' => 'datetime'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (!$model->expired_at) {
                $now = now()->startOfSecond();
                $model->expired_at = $now->copy()->addMinutes(15);
            }
            $model->created_at = now()->startOfSecond();
            $model->updated_at = $model->created_at;
        });
    }
}
