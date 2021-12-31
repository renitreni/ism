<?php

namespace App\Exports;

use App\PurchaseInfo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class POTotalExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths
{
    public $start;
    public $end;

    public function __construct($start, $end)
    {
        $this->start = $start;
        $this->end   = $end;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return (new PurchaseInfo())->total($this->start, $this->end);
    }

    public function headings()
    : array
    {
        return ['PO No.', 'Vendor Name', 'Grand Total'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths()
    : array
    {
        return [
            'A' => 20,
            'B' => 40,
            'C' => 20,
        ];
    }
}
