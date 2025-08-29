<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MrDailyReport extends Model
{
    protected $table    = 'mr_daily_reports';
    protected $fillable = [
        'mr_id',
        'report_date',
        'total_visits',
        'patients_referred',
        'notes',
        'status',
        'manager_id',
        'reviewed_at',
    ];

    public function mr()
    {
        return $this->belongsTo(User::class, 'mr_id', 'id');
    }
}
