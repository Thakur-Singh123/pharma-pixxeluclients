<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MonthlyTask extends Model
{
    //Call migration
    protected $table = 'monthly_tasks';
    protected $fillable = ['task_id','mr_id','manager_id','is_approval'];

    //Function for get taks
    public function task_detail() {
        return $this->belongsTo(Task::class, 'task_id');
    }

    // Doctor through Task
    public function doctor_detail() {
        return $this->hasOneThrough(
            Doctor::class,  
            Task::class,    
            'id',           
            'id',           
            'task_id',      
            'doctor_id'    
        );
    }
}
