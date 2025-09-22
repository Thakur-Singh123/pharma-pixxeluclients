<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    
    protected $table = 'events';

    protected $fillable = [
        'mr_id',
        'manager_id',
        'doctor_id',
        'title',
        'description',
        'location',
        'pin_code',
        'start_datetime',
        'end_datetime',
        'status',
        'qr_code_path',
        'created_by',
        'is_active',
    ];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }

    //Function for get doctors
        public function doctor_detail() {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
}
