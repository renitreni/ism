<?php

namespace App\Exports;

use App\ProductDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class LaborTotalExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths
{

    public function query()
    {
        return (new ProductDetail())->getLabor()
            ->selectRaw('so_no, product_name, qty, selling_price')
            ->orderBy('so_no');
    }

    public function columnWidths()
    : array
    {
        return [
            'A' => 10,
            'B' => 55,
            'C' => 10,
        ];
    }

    public function headings()
    : array
    {
        return ['SO No.', 'Product Name','Quantity', 'Selling Price'];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
