<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    //Call migration
    protected $table = 'doctors';
    protected $fillable = ['user_id','hospital_name','hospital_type','area_name','area_block','district','state','area_code','doctor_id','doctor_name','specialist','doctor_contact',
    'location','picture','remarks','visit_type','status'];

    //get the mr that owns the doctor
    public function mr()
    {
        return $this->belongsToMany(User::class, 'doctor_mr_assignments', 'doctor_id', 'mr_id');
    }
}

