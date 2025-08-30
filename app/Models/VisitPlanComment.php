<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VisitPlanComment extends Model
{
    protected $table = 'visit_plan_comment';
    protected $fillable = ['visit_plan_id','related_id','role','comment'];
}
