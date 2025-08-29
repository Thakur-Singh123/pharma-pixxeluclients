<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPlanInterest extends Model
{
    protected $table = 'visit_plan_interests';
    protected $fillable = [
        'visit_plan_id',
        'mr_id',
    ];

    public function visitPlan()
    {
        return $this->belongsTo(VisitPlan::class, 'visit_plan_id');
    }

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }
}
