<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //Call migration
    protected $table = 'clients';
    protected $fillable = ['mr_id','manager_id','category_type','details','status','approved_by'];
}
