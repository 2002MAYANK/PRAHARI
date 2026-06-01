<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CaseModel extends Model
{
    protected $fillable = [
        'prahari_id',
        
        'type',
        'location',
        'description',
        'status',
    ];

    public function prahari()
    {
        return $this->belongsTo(Prahari::class);
    }

    public function challans()
    {
        return $this->hasMany(Challan::class, 'case_model_id');
    }
}
