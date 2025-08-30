<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPlan extends Model
{
    
    protected $table = 'visit_plans';
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

    public function assignments()
    {
        return $this->hasMany(VisitPlanAssignement::class, 'visit_plan_id');
    }

    public function comments(){
       return $this->hasMany(VisitPlanComment::class, 'visit_plan_id','id');
    } 
}
