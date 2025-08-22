<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'mr_id', 'name', 'age', 'gender', 'disease', 'address', 'contact_number'
    ];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id');
    }
}
