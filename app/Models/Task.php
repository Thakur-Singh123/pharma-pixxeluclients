<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['mr_id', 'manager_id', 'doctor_id', 'pin_code', 'title', 'description', 'location', 'start_date','end_date','created_by','status','is_active'];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'id');
    }
    
}
