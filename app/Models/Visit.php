<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Visit extends Model
{
    //Call migration
    protected $table = 'daily_visits';
    protected $fillable = ['area_name','area_block','district','state','area_code','status'];
}
