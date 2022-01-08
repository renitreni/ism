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
    public $start;
    public $end;

//    public function __construct($start, $end)
//    {
//        $this->start = $start;
//        $this->end   = $end;
//    }

    public function query()
    {
        return ProductDetail::query()
            ->selectRaw('so.due_date,
            so.agent,
            so.so_no,
            c.name,
            product_name,
            qty,
            vendor_price,
            selling_price,
            (qty * selling_price) as subtotal,
            so.payment_status,
            so.payment_method')
            ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
            ->join('customers as c', 'c.id', '=', 'so.customer_id')
            ->whereRaw("lower(product_name) LIKE '%labor%'")
            ->where('so.status', 'Sales')
            ->when($this->start && $this->end, function ($q) {
                return $q->whereBetween('due_date', [$this->start, $this->end]);
            })
            ->whereNull('purchase_order_id')
            ->orderBy('so.so_no', 'desc');
    }

    public function columnWidths()
    : array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 20,
            'E' => 20,
            'F' => 10,
            'G' => 12,
            'H' => 12,
            'J' => 15,
            'K' => 15,
        ];
    }

    public function headings()
    : array
    {
        return [
            'Date',
            'Agent',
            'SO No.',
            'Customer Name',
            'Item',
            'QTY',
            'Vendor Price',
            'Selling Price',
            'Sub Total',
            'Payment Status',
            'Form Of Payment',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }
}
