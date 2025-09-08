<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferredPatient extends Model
{
    //Call migration
    protected $table = 'referred_patients';
    protected $fillable = ['mr_id','manager_id','patient_name','contact_no','address','disease','referred_to','status'];

    //Get mr details
    public function mr() {
        return $this->belongsTo(User::class, 'mr_id');
    }
}
