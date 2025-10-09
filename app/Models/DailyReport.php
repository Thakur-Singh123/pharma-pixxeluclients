<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyReport extends Model
{
    //Call migration
    protected $table = 'daily_reports';
    protected $fillable = ['mr_id','manager_id','report_date','status', 'approved_by'];

    //Function for get daily reports details
    public function report_details() {
        return $this->hasMany(DailyReportDetail::class, 'report_id', 'id');
    }

    //Function for get mr details
    public function mr_details() {
        return $this->belongsTo(User::class, 'mr_id', 'id');
    }
}
