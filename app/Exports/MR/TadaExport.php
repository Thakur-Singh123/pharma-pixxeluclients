<?php

namespace App\Exports\MR;

use App\Models\TADARecords;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;

class TadaExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    //Protected
    protected $status;
    protected $travelDate;

    //Function for construct
    public function __construct() {
        //Get requests
        $this->status = request('status');
        $this->travelDate = request('travel_date');
    }

    //Functionton for collection
    public function collection() {
        //Query
        $query = TADARecords::where('mr_id', auth()->id())
            ->orderByDesc('created_at');
        //Status filter
        if (!empty($this->status)) {
            $query->where('status', $this->status);
        }
        //Date filter
        if (!empty($this->travelDate)) {
            $query->whereDate('travel_date', $this->travelDate);
        }
        //Get records
        $records = $query->get();
      
        $data = [];

        foreach ($records as $key => $row) {
            $data[] = [
                $key + 1,
                $row->travel_date ? Carbon::parse($row->travel_date)->format('d M, Y') : 'N/A',
                $row->place_visited ?? 'N/A',
                $row->distance_km ?? 'N/A',
                '₹ ' . number_format($row->ta_amount, 2),
                '₹ ' . number_format($row->da_amount, 2),
                '₹ ' . number_format($row->total_amount, 2),
                $row->mode_of_travel ?? 'N/A',
                $row->outstation_stay ?? 'N/A',
                $row->purpose_of_visit ?? 'N/A',
                $row->remarks ?? 'N/A',
                ucfirst($row->status),
            ];
        }

        return collect($data);
    }

    //Function for header
    public function headings(): array  {
        return [
            'SR. No',
            'Date of Travel',
            'Place Visited',
            'Distance (KM)',
            'TA Amount',
            'DA Amount',
            'Total Amount',
            'Mode of Travel',
            'Outstation Stay',
            'Purpose of Visit',
            'Remarks',
            'Status',
        ];
    }
    
    //Function for register events
    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                //Insert Title Row
                $sheet->insertNewRowBefore(1, 1);
                $highestColumn = $sheet->getHighestColumn();
                $sheet->mergeCells("A1:{$highestColumn}1");

                //Dynamic Title Build
                $title = 'All TADA Records';

                if (!empty($this->status)) {
                    $title = 'All TADA ' . ucfirst($this->status) . ' Records';
                }

                if (!empty($this->travelDate)) {
                    $formattedDate = Carbon::parse($this->travelDate)->format('d F Y');
                    $title .= ' - ' . $formattedDate;
                }

                $sheet->setCellValue('A1', $title);

                //Title Styling
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                //Header Row Styling
                $sheet->getStyle('A2:' . $highestColumn . '2')->applyFromArray([
                    'font' => ['bold' => true],
                    'borders' => [
                        'allBorders' => [
                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                        ],
                    ],
                    'fill' => [
                        'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);

                $highestRow = $sheet->getHighestRow();
                $sheet->getStyle('A3:A' . $highestRow)
                    ->getNumberFormat()
                    ->setFormatCode('0"."');
                $sheet->freezePane('A3');
            },
        ];
    }
}
