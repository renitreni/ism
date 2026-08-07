<?php

namespace App\Exports;

use App\SalesOrder;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SOVITotalExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths
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

        return (new SalesOrder())->totalvi($this->month, $this->year)->selectRaw('so_no,customers.name,grand_total,agent,payment_status,vat_type,sales_orders.updated_at');
    }

    public function headings()
    : array
    {
        return ['SO No.', 'Vendor Name', 'Grand Total', 'Agent', 'Payment Status','Vat Type', 'Date Of Purchased'];
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
            'A' => 10,
            'B' => 40,
            'C' => 20,
            'D' => 30,
            'E' => 20,
            'F' => 20,
            'G' => 30,
        ];
    }
}
