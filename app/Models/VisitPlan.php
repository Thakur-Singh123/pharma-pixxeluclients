<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPlan extends Model
{
    
    protected $fillable = [
        'plan_type',
        'visit_category',
        'title',
        'description',
        'start_date',
        'end_date',
        'location',
        'created_by',
        'assigned_to',
        'doctor_id',
        'is_locked',
        'status',
    ];
}
