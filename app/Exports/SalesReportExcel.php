<?php

namespace App\Exports;

use App\ProductDetail;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles as WithStylesAlias;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SalesReportExcel implements FromQuery, WithHeadings, WithStylesAlias, WithColumnWidths, WithColumnFormatting
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

        // return ProductDetail::query()
        // ->selectRaw('
        //     so.status,
        //     so.due_date,
        //     so.agent,
        //     so.so_no,
        //     c.name,
        //     product_name,
        //     qty,
        //     vendor_price,
        //     selling_price,
        //     CASE WHEN qty IS NULL OR qty = 0 THEN NULL ELSE d.discount END AS discount,
        //     CASE WHEN qty IS NULL OR qty = 0 THEN NULL ELSE d.shipping END AS shipping,
        //     CASE WHEN qty IS NULL OR qty = 0 THEN NULL ELSE d.sales_actual END AS sales_tax,
        //     CASE WHEN qty IS NULL OR qty = 0 THEN NULL ELSE ((qty * selling_price) + d.shipping + d.sales_actual - d.discount) END as subtotal,
        //     so.payment_status,
        //     so.payment_method,
        //     so.vat_type'
        // )
        // ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
        // ->leftJoin('summaries as d', 'd.sales_order_id', '=', 'product_details.sales_order_id')
        // ->leftJoin('customers as c', 'c.id', '=', 'so.customer_id')
        // ->whereIn('so.status', ['Sales', 'Project'])
        // ->when($this->start && $this->end, function ($q) {
        //     return $q->whereBetween('due_date', [$this->start, $this->end]);
        // })
        // // ->whereNull('product_details.purchase_order_id') // Specify the table
        // ->orderBy('so.so_no', 'desc');

        $mainQuery = ProductDetail::query()->selectRaw('
        so.status,
        so.due_date,
        so.agent,
        so.so_no,
        c.name,
        product_name,
        qty,
        vendor_price,
        selling_price,
        CASE WHEN qty IS NULL OR qty = 0 THEN NULL ELSE d.discount END AS discount,
        CASE WHEN qty IS NULL OR qty = 0 THEN NULL ELSE d.shipping END AS shipping,
        CASE WHEN qty IS NULL OR qty = 0 THEN NULL ELSE d.sales_actual END AS sales_tax,
        CASE WHEN qty IS NULL OR qty = 0 THEN NULL ELSE ((qty * selling_price) + d.shipping + d.sales_actual - d.discount) END as subtotal,
        so.payment_status,
        so.payment_method,
        so.vat_type'
    )
    ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
    ->leftJoin('summaries as d', 'd.sales_order_id', '=', 'product_details.sales_order_id')
    ->leftJoin('customers as c', 'c.id', '=', 'so.customer_id')
    ->whereIn('so.status', ['Sales', 'Project'])
    ->when($this->start && $this->end, function ($q) {
        return $q->whereBetween('due_date', [$this->start, $this->end]);
    });

$subQuery = ProductDetail::query()->selectRaw('
        so.status,
        so.due_date,
        so.agent,
        so.so_no,
        NULL as name,
        NULL as product_name,
        NULL as qty,
        NULL as vendor_price,
        NULL as selling_price,
        SUM(d.discount) AS discount,
        SUM(d.shipping) AS shipping,
        SUM(d.sales_actual) AS sales_tax,
        NULL as subtotal,
        NULL as payment_status,
        NULL as payment_method,
        NULL as vat_type'
    )
    ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
    ->leftJoin('summaries as d', 'd.sales_order_id', '=', 'product_details.sales_order_id')
    ->whereIn('so.status', ['Sales', 'Project'])
    ->groupBy('so.so_no')
    ->orderByRaw('MAX(grand_total) DESC') // Order by the maximum grand total
    ->limit(1);

$results = $mainQuery
    ->unionAll($subQuery)
    // ->groupBy('so_no') // Group by sales order number to ensure unique entries
    ->orderBy('so_no', 'desc');

        return $results;


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
            'G' => 12,
            'H' => 12,
            'J' => 15,
            'K' => 15,
            'L' => 15,
            'M' => 15,
            'N' => 15,
            'O' => 15,
            'P' => 15,
            'Q' => 15,
            'R' => 15,
        ];
    }

    public function headings(): array
    {
        return [
            'Status',
            'Date',
            'Agent',
            'SO No.',
            'Customer Name',
            'Item',
            'QTY',
            'Vendor Price',
            'Selling Price',
            'Discount',
            'Shipping',
            'Sales Tax',
            'Sub Total',
            'Payment Status',
            'Form Of Payment',
            'Vat Type',
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
