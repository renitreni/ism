<?php

namespace App\Exports;

use App\PurchaseInfo;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class POVITotalExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths
{
    public $month;
    public $year;

    public function __construct($month, $year)
    {
        $this->month = $month;
        $this->year   = $year;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function query()
    {
        return (new PurchaseInfo())->totalvi($this->month, $this->year);
    }

    public function headings()
    : array
    {
        return ['Due Date', 'PO No.', 'DR/SI No.', 'Vendor Name', 'Payment Status', 'Vat Type', 'Grand Total'];
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
            'G' => 20,
        ];
    }
}
