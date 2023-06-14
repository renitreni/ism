<?php

namespace App\Exports;

use App\ProductDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithHeadings;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;

class PurchaseReportExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths, WithColumnFormatting
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
        return ProductDetail::query()
            ->selectRaw('
            po.status,
            po.subject,
            c.name,
            po.po_no,
            product_name,
            qty,
            vendor_price,
            selling_price,
            discount_item,
            ((qty * selling_price) + discount_item) as subtotal,
            po.received_date,
            po.due_date,
            po.payment_status,
            po.payment_method,
            users.name as assigend_name')
            ->join('purchase_infos as po', 'po.id', '=', 'product_details.purchase_order_id')
            ->leftJoin('vendors as c', 'c.id', '=', 'po.vendor_id')
            ->leftJoin('users', 'users.id', '=', 'po.assigned_to')
            ->whereIn('po.status', ['Received'])
            ->when($this->start && $this->end, function ($q) {
                return $q->whereBetween('due_date', [$this->start, $this->end]);
            })
            ->whereNull('sales_order_id')
            ->orderBy('po.po_no', 'desc');
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 15,
            'C' => 15,
            'D' => 20,
            'E' => 20,
            'F' => 10,
            'G' => 15,
            'H' => 15,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 20,
            'O' => 15,
        ];
    }

    public function headings(): array
    {
        return [
            'Status',
            'DR/SI',
            'Vendor Name',
            'PO No.',
            'Item',
            'QTY',
            'Vendor Price',
            'Selling Price',
            'Discount',
            'Sub Total',
            'Received Date',
            'Due Date',
            'Payment Status',
            'Form Of Payment',
            'Assigned To',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => ['font' => ['bold' => true]],
        ];
    }

    public function columnFormats(): array
    {
        return [
            'A' => NumberFormat::FORMAT_DATE_YYYYMMDD,
        ];
    }
}
