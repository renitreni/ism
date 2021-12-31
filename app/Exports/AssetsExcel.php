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
            ->selectRaw('products.name,products.category,products.code,supplies.quantity,products.selling_price')
            ->join('products', 'products.id', '=', 'supplies.product_id')
            ->orderBy('products.name');
    }

    public function headings()
    : array
    {
        return ['Product Name', 'Category', 'Product Model', 'Quantity', 'Selling Price'];
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
        ];
    }
}
