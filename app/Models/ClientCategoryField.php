<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ClientCategoryField extends Model
{
    protected $table = 'client_category_fields';
    
    protected $fillable = [
        'client_category_id',
        'label',
        'name',
        'type',
        'input_type',
        'validation_type',
        'options',
    ];

    protected $casts = [
        'options' => 'array',
    ];

     public function category()
    {
        return $this->belongsTo(ClientCategory::class, 'client_category_id');
     }
}
