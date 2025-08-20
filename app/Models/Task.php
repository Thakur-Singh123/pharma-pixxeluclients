<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = ['mr_id', 'manager_id', 'title', 'description', 'status'];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }
}
