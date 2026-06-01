<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'prahari_id',
        'amount',
        'bank_account',
        'type',
        'status',
    ];

    public function prahari()
    {
        return $this->belongsTo(Prahari::class);
    }
}
