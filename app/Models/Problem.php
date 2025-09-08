<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    //Call migration
    protected $table = 'problems';
    protected $fillable = ['mr_id','visit_id','title','description'];

    //Get visit detail
    public function visit_details() {
        return $this->belongsTo(Visit::class, 'visit_id');
    }
}
