<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCategory extends Model
{
    protected $table = 'client_categories';

    protected $fillable = [
        'name',
        'status',
        'created_at',
        'updated_at',
    ];

     public function fields()
    {
        return $this->hasMany(ClientCategoryField::class);
    }
}
