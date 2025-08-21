<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    //Call migration
    protected $table = 'doctors';
    protected $fillable = ['area_name','area_block','district','state','area_code','doctor_id','doctor_name','doctor_contact','location','picture','remarks','visit_type'];
}
