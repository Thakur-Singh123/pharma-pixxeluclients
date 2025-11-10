<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounselorPatient extends Model
{
    protected $table = 'counselor_patients';
    protected $fillable = [
        'patient_name',
        'mobile_no',
        'email',
        'department',
        'uhid_no',
        'booking_amount',
        'booking_done',
        'counselor_id',
        'remark',
    ];
}
