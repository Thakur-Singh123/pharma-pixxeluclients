<?php

namespace App\Exports;

use App\Models\DailyReport;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Carbon\Carbon;

class ReportsExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    //Get filter input requests
    protected $filter;

    //Function for constructor
    public function __construct($filter = null) {
        $this->filter = $filter;
    }

    //Function for collection data
    public function collection() {
        //Query
        $query = DailyReport::OrderBy('ID', 'DESC')->with(['mr_details', 'report_details.doctor']);
        //filters
        if ($this->filter === 'today') {
            $query->whereDate('report_date', now()->toDateString());
        } elseif ($this->filter === 'week') {
            $query->whereBetween('report_date', [now()->startOfWeek(), now()->endOfWeek()]);
        } elseif ($this->filter === 'month') {
            $query->whereMonth('report_date', now()->month)->whereYear('report_date', now()->year);
        } elseif ($this->filter === 'year') {
            $query->whereYear('report_date', now()->year);
        }
        //Get reports data
        $reports = $query->get();
        //export data
        $data = [];
        //Get reports with details
        foreach ($reports as $report) {
            foreach ($report->report_details as $detail) {
                $data[] = [
                    'Report Date' => Carbon::parse($report->report_date)->format('d M, Y'),
                    'Created MR Name' => $report->mr_details->name ?? 'N/A',
                    'Doctor Name' => $detail->doctor->doctor_name ?? 'N/A',
                    'Area Served' => $detail->area_name ?? 'N/A',
                    'Total Visits' => $detail->total_visits ?? 0,
                    'Patients Referred' => $detail->patients_referred ?? 0,
                    'Notes' => $detail->notes ?? '',
                    'Status' => ucfirst($report->status ?? 'Pending'),
                ];
            }
        }
        //Return data collection
        return collect($data);
    }

    //Function column headings Excel sheet
    public function headings(): array {
        return ['Report Date','Created MR Name','Doctor Name','Area Served','Total Visits','Patients Referred','Notes','Status'];
    }

    //Function for customize Excel sheet
    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->insertNewRowBefore(1, 1);
                $sheet->mergeCells('A1:H1');
                $sheet->setCellValue('A1', 'All Report Details');
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);
                $sheet->getStyle('A2:H2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);
                $sheet->freezePane('A3');
                foreach (range('A', 'H') as $col) {
                    $sheet->getColumnDimension($col)->setAutoSize(true);
                }
            },
        ];
    }
}
