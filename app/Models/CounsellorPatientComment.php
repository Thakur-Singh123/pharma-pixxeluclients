<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CounsellorPatientComment extends Model
{
    protected $table = 'counsellor_patient_comments';

    protected $fillable = [
        'counselor_patient_id',
        'user_id',
        'role',
        'comment',
    ];

    public function counselorPatient()
    {
        return $this->belongsTo(CounselorPatient::class, 'counselor_patient_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
