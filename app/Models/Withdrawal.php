<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Withdrawal extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'amount'];

    /**
     * Generate referral code and save
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(static function (Withdrawal $model) {
            $model->user_id = Auth::id();
        });
    }
}
