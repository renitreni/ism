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
        return ['Due Date', 'PO No.', 'DR/SI No.', 'Vendor Name', 'Payment Status', 'Grand Total'];
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
            'B' => 20,
            'C' => 20,
            'D' => 20,
            'E' => 20,
            'F' => 20,
        ];
    }
}
