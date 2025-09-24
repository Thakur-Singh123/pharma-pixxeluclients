<?php

namespace App\Exports;

use App\Models\MRDailyReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ReportsExport implements FromCollection, WithHeadings
{
    protected $filter;

    public function __construct($filter = null)
    {
        $this->filter = $filter;
    }

    public function collection()
    {
        $query = MRDailyReport::with('mr');
        // Apply filter
        if ($this->filter === 'today') {
            $query->whereDate('report_date', now()->toDateString());
        } elseif ($this->filter === 'week') {
            $query->whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filter === 'month') {
            $query->whereMonth('report_date', now()->month)->whereYear('report_date', now()->year);
        } elseif ($this->filter === 'year') {
            $query->whereYear('report_date', now()->year);
        }
        $data = $query->get();
        return collect($this->get_data($data));
    }

    //function for get data
    public function get_data($report): array
    {
        $data = []; //

        foreach ($report as $key => $value) {
            $data[$key]['s_no'] = $key + 1;
            $data[$key]['report_date'] = $value->report_date;
            $data[$key]['mr_name'] = $value->mr->name ?? '';
            $data[$key]['doctor_id'] = $value->doctor_detail->doctor_name ?? '';
            $data[$key]['area_name'] = $value->area_name;
            $data[$key]['total_visits'] = $value->total_visits;
            $data[$key]['patients_referred'] = $value->patients_referred;
            $data[$key]['notes'] = $value->notes;
            $data[$key]['status'] = ucfirst($value->status); // Optional: Capitalize
        }

        return $data;
    }

    public function headings(): array
    {
        return [
            'S No.', 'Report Date', 'MR Name', 'Doctor Name', 'Area Served', 'Total Visits', 'Patients Referred', 'Notes', 'Status'
        ];
    }
}
