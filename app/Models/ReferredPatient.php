<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferredPatient extends Model
{
    //Call migration
    protected $table = 'referred_patients';
    protected $fillable = ['mr_id','manager_id','doctor_id','patient_name','contact_no','address','disease','dob','gender','emergency_contact','blood_group','medical_history','referred_to','status'];

    //Get mr details
    public function mr() {
        return $this->belongsTo(User::class, 'mr_id');
    }

    //Get doctor details
    public function doctor_detail() {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
}
