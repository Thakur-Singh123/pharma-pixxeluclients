<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['mr_id', 'manager_id', 'title', 'description', 'location', 'start_date','end_date','created_by','status'];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }
}
