<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    //Call migration
    protected $table = 'daily_reports';
    protected $fillable = ['mr_id','manager_id','report_date','status'];

    //Function for get daily reports details
    public function report_details() {
        return $this->hasMany(DailyReportDetail::class, 'report_id', 'id');
    }
}
