<?php

namespace App\Exports;

use App\Supply;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AssetsExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return Supply::query()
            ->selectRaw('products.category,products.code,products.name,supplies.quantity,vendor_price,products.selling_price')
            ->join('products', 'products.id', '=', 'supplies.product_id')
            ->orderBy('products.name');
    }

    public function headings()
    : array
    {
        return ['Category', 'Product Model', 'Product Name', 'Quantity', 'Dealer\'s Price', 'Selling Price'];
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
            'A' => 55,
            'B' => 40,
            'C' => 40,
            'E' => 15,
            'F' => 15,
        ];
    }
}
