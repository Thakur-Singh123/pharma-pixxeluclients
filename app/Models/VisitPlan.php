<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPlan extends Model
{
    
    protected $fillable = [
        'manager_id',
        'mr_id',
        'visit_date',
        'location',
        'doctor_id',
        'notes',
        'status',
    ];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }
}
