<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Challan extends Model
{
    protected $fillable = [
        'prahari_id',
        'case_model_id',
        'amount',
        'status',
    ];

    public function case()
    {
        return $this->belongsTo(CaseModel::class, 'case_model_id');
    }

    public function prahari()
    {
        return $this->belongsTo(Prahari::class);
    }
}
