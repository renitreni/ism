<?php

namespace App\Exports;

use App\Expenses;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ExpensesTotalExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths, WithColumnFormatting
{
    public $start;
    public $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    public function query()
    {
        return (new Expenses())
            ->query()
            ->when($this->start,function($q){
                $q->whereBetween('expense_date', [$this->start, $this->end]);
            })
            ->select([
                'expenses_no',
                'cost_center',
                'description',
                'person_assigned',
                'total_amount',
                'expense_date',
                'si_no',
                'dr_no',
                'remarks',
                'vat_type',
            ]);
    }

    public function columnWidths()
    : array
    {
        return [
            'A' => 20,
            'B' => 40,
            'C' => 20,
            'D' => 20,
            'E' => 15,
        ];
    }

    public function headings()
    : array
    {
        return ['EXP #', 'Cost Center', 'Description', 'Person Assigned', 'Total Amount', 'Expense Date', 'SI No', 'DR No', 'Remarks', 'Vat Type'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }


    public function columnFormats()
    : array
    {
        return [
            'F' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
