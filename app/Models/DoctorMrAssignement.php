<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorMrAssignement extends Model
{
    protected $table = 'doctor_mr_assignments';
    protected $fillable = ['doctor_id', 'mr_id'];
}
