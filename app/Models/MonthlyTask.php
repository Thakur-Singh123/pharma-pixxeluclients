<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyTask extends Model
{
    //Call migration
    protected $table = 'monthly_tasks';
    protected $fillable = ['task_id','mr_id','manager_id','is_approval'];
}
