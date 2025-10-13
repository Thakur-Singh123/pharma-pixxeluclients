<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskTourPlan extends Model
{
    //Call migration
    protected $table = 'task_tour_plans';
    protected $fillable = ['task_id','mr_id','manager_id','doctor_id','title','description','location','pin_code','start_date','end_date','approval_status'];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }    
}
