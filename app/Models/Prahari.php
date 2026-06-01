<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Prahari extends Authenticatable
{
    use HasApiTokens, Notifiable;

    protected $fillable = [
        'name',
        'phone',
        'password',
        'aadhaar',
        'is_active',
        'bank_name',
        'account_number',
        'ifsc_code',
        'otp',
        'otp_expires_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
        'otp',
        'otp_expires_at',
    ];

    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function cases()
    {
        return $this->hasMany(CaseModel::class);
    }

    public function challans()
    {
        return $this->hasMany(Challan::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }
}
