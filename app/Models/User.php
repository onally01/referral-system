<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'referral_code'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Generate referral code and save
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(static function (User $model) {
            $model->referral_code = Str::random(6);
        });
    }

    /**
     * Relation to referral.
     *
     * @return HasMany
     */
    public function referral(): HasMany
    {
        return $this->hasMany(Referral::class);
    }

    /**
     * Relation to withdrawal.
     *
     * @return HasMany
     */
    public function withdrawal(): HasMany
    {
        return $this->hasMany(Withdrawal::class);
    }

    /**
     * User wallet
     * @return int|mixed
     */
    public function wallet()
    {
        return $this->referral()->sum('earn') - $this->withdrawal()->sum('amount');
    }

    /**
     * Custom Method to save referral.
     * @param $referral_id
     * @param $userId
     */
    public function saveReferral($referral_id, $userId): void
    {
        Referral::create([
            'user_id' => $referral_id,
            'referred' => $userId,
            'earn' => config('referral.earn')
        ]);
    }
}
