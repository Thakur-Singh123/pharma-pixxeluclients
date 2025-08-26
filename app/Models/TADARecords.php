<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TADARecords extends Model
{
    //
    protected $table = 'ta_da_records';
    protected $fillable = [
        'mr_id',
        'travel_date',
        'place_visited',
        'distance_km',
        'ta_rate',
        'ta_amount',
        'da_amount',
        'total_amount',
        'mode_of_travel',
        'remarks',
        'status',
        'approved_by',
        'approved_at',
        'purpose_of_visit',
        'attachment',
    ];
}
