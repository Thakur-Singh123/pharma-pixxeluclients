<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MangerMR extends Model
{
    protected $table = 'manager_mr'; 

    protected $fillable = [
        'manager_id',
        'mr_id',
    ];
}
