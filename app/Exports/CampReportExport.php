<?php

namespace App\Exports;

use App\Models\EventUser;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Collection;

class CampReportExport implements FromCollection, ShouldAutoSize, WithEvents, WithCustomStartCell, WithMapping
{
    //Function for get collection
    public function collection() {
        //Get all records
        return EventUser::with('event_detail.mr')->orderBy('id', 'DESC')->get();
    }

    //Function for start row 
    public function startCell(): string {
        return 'A3';
    }

    //Excel columns
    public function map($row): array {
        $age = (int) $row->age;
        $male = $row->sex == 'male' ? 1 : 0;
        $female = $row->sex == 'female' ? 1 : 0;
        $less18 = $age < 18 ? 1 : 0;
        $btw18_40 = $age >= 18 && $age <= 40 ? 1 : 0;
        $above40 = $age > 40 ? 1 : 0;

        return [
            $row->uid ?? 'N/A',
            $row->event_detail->location ?? 'N/A',
            $row->name ?? 'N/A',
            $row->email ?? 'N/A',
            $row->age ?? 'N/A',
            $row->sex ?? 'N/A',
            $row->phone ?? 'N/A',
            $row->pin_code ?? 'N/A',
            $row->disease ?? 'N/A',
            $row->health_declare ? 'Yes' : 'No',
            $row->event_detail->mr->name  ?? 'N/A',
            $male,
            $female,
            $less18,
            $btw18_40,
            $above40,
        ];
    }
    
    //Function for custom header
    public function registerEvents(): array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();
                $sheet->mergeCells('A1:P1'); 
                $sheet->setCellValue('A1', "All Camp Report Details");
                $sheet->getStyle('A1')->applyFromArray([
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'font' => ['bold' => true, 'size' => 14],
                ]);
                $headers = [
                    'Registration UID','Area Of Camp','Name','Email','Age','Sex','Phone Number','Pin Code','Disease','Health Declare','Event Organizer (MR)',
                    'Male','Female','Less than 18','18 to 40','Above 40'
                ];
                $col = 'A';
                foreach ($headers as $header) {
                    $sheet->setCellValue($col . '2', $header);
                    $col++;
                }
                $sheet->getStyle('A2:P2')->applyFromArray([
                    'font' => ['bold' => true],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                    'borders' => [
                        'allBorders' => ['borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN],
                    ],
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'D9D9D9'],
                    ],
                ]);
                $sheet->freezePane('A3');
                foreach (range('A', 'P') as $columnID) {
                    $sheet->getColumnDimension($columnID)->setAutoSize(true);
                }
                $sheet->getDefaultRowDimension()->setRowHeight(-1);
            },
        ];
    }
}
