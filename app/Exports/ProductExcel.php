<?php

namespace App\Exports;

use App\Product;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths
{
    /**
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function query()
    {
        return Product::query()
            ->selectRaw('manual_id, name, code, category, manufacturer, unit, selling_price, vendor_price, batch, color, size, weight, type, created_at')
            ->orderBy('name');
    }

    public function headings(): array
    {
        return [
            'Manual ID',
            'Name',
            'Code',
            'Category',
            'Manufacturer',
            'Unit',
            'Selling Price',
            'Vendor Price',
            'Batch',
            'Color',
            'Size',
            'Weight',
            'Type',
            'Created At',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 35,
            'C' => 20,
            'D' => 18,
            'E' => 22,
            'F' => 12,
            'G' => 15,
            'H' => 15,
            'I' => 14,
            'J' => 12,
            'K' => 12,
            'L' => 12,
            'M' => 12,
            'N' => 22,
        ];
    }
}
