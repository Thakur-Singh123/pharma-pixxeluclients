<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Events extends Model
{
    
    protected $table = 'events';

    protected $fillable = [
        'mr_id',
        'manager_id',
        'title',
        'description',
        'location',
        'start_datetime',
        'end_datetime',
        'status',
        'qr_code_path'
    ];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }
}
