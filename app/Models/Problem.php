<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Problem extends Model
{
    //Call migration
    protected $table = 'problems';
    protected $fillable = ['mr_id','visit_id','title','description','camp_type','visit_name','doctor_name','start_date','end_date'];

    //Get visit detail
    public function visit_details() {
        return $this->belongsTo(Visit::class, 'visit_id');
    }
 
    //Function mr details
    public function mr_detail() {
        return $this->belongsTo(User::class, 'mr_id'); 
    }
}
