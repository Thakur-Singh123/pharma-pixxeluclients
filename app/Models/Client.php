<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    //Call migration
    protected $table = 'clients';
    protected $fillable = ['name','contact','category_type','details','status'];
}
