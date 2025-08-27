<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPlanAssignement extends Model
{
    protected $table = 'visit_plan_assignments';
    protected $fillable = [
        'visit_plan_id',
        'mr_id',
    ];

    public function visitPlan()
    {
        return $this->belongsTo(VisitPlan::class, 'visit_plan_id');
    }
}
