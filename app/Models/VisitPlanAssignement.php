<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPlanAssignement extends Model
{
    //
    protected $fillable = [
        'visit_plan_id',
        'mr_id',
    ];
}
