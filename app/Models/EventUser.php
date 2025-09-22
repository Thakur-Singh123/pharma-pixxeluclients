<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventUser extends Model
{
    protected $table = 'event_users';
    protected $fillable = ['event_id', 'name', 'email', 'kyc', 'age', 'sex', 'phone', 'pin_code', 'uid', 'disease', 'health_declare'];

    //Function for get events
    public function event_detail() {
        return $this->belongsTo(Events::class, 'event_id','id');
    }
}
