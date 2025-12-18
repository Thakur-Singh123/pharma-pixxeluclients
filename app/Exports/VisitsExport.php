<?php

namespace App\Exports;

use App\Models\Visit;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class VisitsExport implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected ?string $visitDate;
    /** @var array<int> */
    protected array $mrIds;

    /**
     * @param  string|null  $visitDate  Expected format: Y-m-d (from input[type=date])
     * @param  array<int>   $mrIds
     */
    public function __construct(?string $visitDate = null, array $mrIds = [])
    {
        $this->visitDate = $visitDate ?: null;
        $this->mrIds = $mrIds;
    }

    public function collection()
    {
        $query = Visit::query()->with(['mr', 'doctor'])->orderByDesc('id');

        if (!empty($this->mrIds)) {
            $query->whereIn('mr_id', $this->mrIds);
        }

        if (!is_null($this->visitDate)) {
            $query->where('visit_date', $this->visitDate);
        }

        $visits = $query->get();

        $data = [];
        foreach ($visits as $visit) {
            $visitDate = $visit->visit_date ? Carbon::parse($visit->visit_date)->format('d M, Y') : 'N/A';

            // Match the "All Visits" UI formatting for visit type column
            $visitTypeLabel = $visit->visit_type ?? 'N/A';
            $visitTypeUi = 'N/A';
            if ($visit->visit_type === 'other') {
                $visitTypeUi = 'Other Visit - (' . ($visit->other_visit ?? 'N/A') . ')';
            } elseif ($visit->visit_type === 'doctor') {
                $doctorName = $visit->doctor->doctor_name ?? 'N/A';
                $specialist = $visit->doctor->specialist ?? 'N/A';
                $hospitalName = $visit->doctor->hospital_name ?? 'N/A';
                $hospitalType = $visit->doctor->hospital_type ?? 'N/A';
                $visitTypeUi = 'Doctor - (' . $doctorName . '-' . $specialist . '-' . $hospitalName . '-' . $hospitalType . ')';
            } elseif ($visit->visit_type === 'religious_places') {
                $visitTypeUi = 'Religious Places - ' . ($visit->religious_place ?? 'N/A');
            } elseif ($visit->visit_type === 'school') {
                $visitTypeUi = 'School - (' . ($visit->school_type ?? 'N/A') . ')';
            } elseif ($visit->visit_type === 'bams_rmp_dental') {
                $visitTypeUi = 'BAMS RMP Dental';
            } elseif ($visit->visit_type === 'asha_workers') {
                $visitTypeUi = 'Asha Workers';
            } elseif ($visit->visit_type === 'health_workers') {
                $visitTypeUi = 'Health Workers';
            } elseif ($visit->visit_type === 'anganwadi') {
                $visitTypeUi = 'Anganwadi / Balvatika';
            } elseif ($visit->visit_type === 'villages') {
                $visitTypeUi = 'Villages - (' . ($visit->villages ?? 'N/A') . ')';
            } elseif ($visit->visit_type === 'city') {
                $visitTypeUi = 'City - (' . ($visit->city ?? 'N/A') . ')';
            } elseif ($visit->visit_type === 'societies') {
                $visitTypeUi = 'Societies - (' . ($visit->societies ?? 'N/A') . ')';
            } elseif ($visit->visit_type === 'ngo') {
                $visitTypeUi = 'NGO - (' . ($visit->ngo ?? 'N/A') . ')';
            }

            $data[] = [
                'Visit Date' => $visitDate,
                'MR Name' => $visit->mr->name ?? 'N/A',
                'Area Name' => $visit->area_name ?? 'N/A',
                'Area Block' => $visit->area_block ?? 'N/A',
                'District' => $visit->district ?? 'N/A',
                'State' => $visit->state ?? 'N/A',
                'Pin Code' => $visit->pin_code ?? 'N/A',
                'Clinic/Hospital' => $visit->clinic_hospital_name ?? 'N/A',
                'Mobile' => $visit->mobile ?? 'N/A',
                'Comments' => $visit->comments ?? '',
                'Visit Type' => $visitTypeLabel,
                'Visit Type Details' => $visitTypeUi,
                'Status' => ucfirst($visit->status ?? 'Pending'),
            ];
        }

        return collect($data);
    }

    public function headings(): array
    {
        return [
            'Visit Date',
            'MR Name',
            'Area Name',
            'Area Block',
            'District',
            'State',
            'Pin Code',
            'Clinic/Hospital',
            'Mobile',
            'Comments',
            'Visit Type',
            'Visit Type Details',
            'Status',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                $sheet->insertNewRowBefore(1, 1);
                $sheet->mergeCells('A1:M1');

                $title = 'All Visit Details';
                if (!is_null($this->visitDate)) {
                    $title .= ' - ' . Carbon::parse($this->visitDate)->format('d M, Y');
                }

                $sheet->setCellValue('A1', $title);
                $sheet->getStyle('A1')->applyFromArray([
                    'font' => ['bold' => true, 'size' => 16],
                    'alignment' => [
                        'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                        'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                    ],
                ]);

                $sheet->getStyle('A2:M2')->applyFromArray([
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
            },
        ];
    }
}
