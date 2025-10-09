<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReportDetail extends Model
{
    //Call migration
    protected $table = 'daily_report_details';
    protected $fillable = ['report_id','doctor_id','area_name','total_visits','patients_referred','notes'];

}
