<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Cell\StringValueBinder;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithEvents;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Shared\Date as ExcelDate;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use Maatwebsite\Excel\Concerns\WithTitle;

class ReportExport extends StringValueBinder implements FromCollection, WithHeadings, WithMapping, WithColumnFormatting, ShouldAutoSize, WithEvents, WithStyles, WithTitle
{
    protected $new_data;
    
    public function __construct($data)
    {
        //una pupunta constructor
        $this->new_data = $data;
    }

    public function collection()
    {
        return collect($this->new_data);
        // try {
        //     return collect($this->new_data);
        // } catch (\Exception $e) {
        //     \Log::error($e->getMessage());
        // }

    }

    public function headings(): array
    {
        return [
            'PCG TYPE',
            'CATEGORY',
            'PCG CODE',
            'JAPANESE',
            'ENGLISH',
            'CONTROL NUMBER',
            'PLANNERS NAME',
            'PLANNERS NAME ROMANJI',
            'PLANNERS CODE',
            'PLANNERS BRANCH CODE',
            'PLANNERS BRANCH NAME',
            'SALESMANS NAME',
            'SALESMANS NAME ROMANJI',
            'SALESMANS CODE',
            'SALESMANS BRANCH CODE',
            'SALESMANS BRANCH NAME',
            'END USER',
            'DATE',
        ];
    }

    public function columnFormats(): array
    {
        return [
            // 'H' => NumberFormat::FORMAT_DATE_YYYYMMDDSLASH, 
            // 'H' => NumberFormat::FORMAT_DATE_YYYYMMDD,
            'R' => 'yyyy-mm-dd HH:MM:SS',
            
        ];
    }

    public function map($new_data): array
    {
        $new_date = Carbon::createFromFormat('Y-m-d H:i:s', $new_data->date);
        // $new_date = Carbon::createFromFormat('Y-m-d H:i:s', $new_data->pcg_date)->startOfDay();
        return [
            $new_data->pcg_type,
            $new_data->pcg_category,
            $new_data->code,
            $new_data->japanese,
            $new_data->english,
            $new_data->control_number,
            $new_data->planner_kanji,
            $new_data->planner_roma,
            $new_data->planner_code,
            $new_data->planner_branch_code,
            $new_data->planner_branch_name,
            $new_data->salesman_kanji,
            $new_data->salesman_roma,
            $new_data->salesman_code,
            $new_data->salesman_branch_code,
            $new_data->salesman_branch_name,
            $new_data->Employee_Name,
            // $new_data->date,    
            ExcelDate::stringToExcel($new_date->format('Y-m-d H:i:s')),
            // $new_date->toDateString(),
            // $new_date->format('Y/m/d'), 
            // Date::dateTimeToExcel($new_date->toDateTime()),
            // $new_data->remarks_status,
        ];
    }

    public function registerEvents():array
    {
        return [

            AfterSheet::class => function (AfterSheet $event) {
                // $totalRow = [
                //     'ConstructionCode' => 'Total Amount',
                //     'Plan No' => '',
                //     'Type' => '',
                //     'Salesman Staff' => '',
                //     'Architect' => '',
                //     'SentDate' => '',
                //     'Quantity' => '',
                //     'Unit Price' => '',
                //     'Amount' => collect($this->new_data)->sum('Amount'),
                // ];

                // $event->sheet->append($totalRow);

                // $lastRow = $event->sheet->getHighestRow();
                // $event->sheet->getStyle('I' . $lastRow)->applyFromArray([
                //     'numberFormat' => [
                //         'formatCode' => '"¥"#,##_-',
                //     ],
                // ]);
                
            },

        ]; // end of return 
    }
    
    public function styles(Worksheet $sheet)
    {
        // Get the row index you want to style (e.g., 2 for the second row)
        $rowIndex = 1;

    
        // Set the font color for each cell in the row to red
        $sheet->getStyle('A' . $rowIndex . ':Z' . $rowIndex)->applyFromArray([
            'font' => [

                'bold'  =>  true,
                'color' => [
                    'rgb' => 'FF0000', // Red color in RGB format
                ],
            ],

            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER, // Center text horizontally
                'vertical' => Alignment::VERTICAL_CENTER, // Center text vertically
            ],
        ]);
        
    }
    public function title(): string
    {
        $prefix = 'PCG_REPORT-';
        $date = Carbon::now()->format('Y-m-d');
        return $prefix . $date; // Set the worksheet name as "PCG_REPORT-2023-07-27"
    }


}
