<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    //Call migration
    protected $table = 'daily_visits';
    protected $fillable = ['area_name','area_block','district','state','area_code','status','mr_id','doctor_id','visit_type'];

    //get the mr that owns the visit
    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }
    //get the doctor that owns the visit
    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id');
    }

}
