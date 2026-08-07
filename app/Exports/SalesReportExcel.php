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
use Illuminate\Support\Facades\DB;

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

        return ProductDetail::query()
        ->selectRaw(
            '
            so.status,
            so.due_date,
            so.agent,
            so.so_no,
            c.name,
            product_details.product_name,
            product_details.qty,
            product_details.vendor_price,
            product_details.selling_price,

            -- Use subquery to get the discount, shipping, and sales_actual only for the first product in the sales order
            IF(
                product_details.id = (
                    SELECT MIN(pd.id)
                    FROM product_details pd
                    WHERE pd.sales_order_id = product_details.sales_order_id
                ),
                COALESCE(d.discount, 0), 0
            ) as discount,

            IF(
                product_details.id = (
                    SELECT MIN(pd.id)
                    FROM product_details pd
                    WHERE pd.sales_order_id = product_details.sales_order_id
                ),
                COALESCE(d.shipping, 0), 0
            ) as summary_shipping,

            IF(
                product_details.id = (
                    SELECT MIN(pd.id)
                    FROM product_details pd
                    WHERE pd.sales_order_id = product_details.sales_order_id
                ),
                COALESCE(d.sales_actual, 0), 0
            ) as sales_actual,

            product_details.shipping as product_shipping,
            product_details.actual_sales,

            -- Calculate subtotal correctly
            (
                (COALESCE(product_details.qty, 0) * COALESCE(product_details.selling_price, 0)) -- Multiplication of qty and selling price
                - COALESCE(
                    IF(product_details.id = (
                        SELECT MIN(pd.id)
                        FROM product_details pd
                        WHERE pd.sales_order_id = product_details.sales_order_id
                    ), d.discount, 0), 0)
                + COALESCE(
                    IF(product_details.id = (
                        SELECT MIN(pd.id)
                        FROM product_details pd
                        WHERE pd.sales_order_id = product_details.sales_order_id
                    ), d.shipping, 0), 0)
                + COALESCE(
                    IF(product_details.id = (
                        SELECT MIN(pd.id)
                        FROM product_details pd
                        WHERE pd.sales_order_id = product_details.sales_order_id
                    ), d.sales_actual, 0), 0)
            ) as subtotal,

            so.payment_status,
            so.payment_method,
            so.vat_type
        ')
        ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
        ->leftJoin('summaries as d', 'd.sales_order_id', '=', 'product_details.sales_order_id')
        ->leftJoin('customers as c', 'c.id', '=', 'so.customer_id')
        ->whereIn('so.status', ['Sales', 'Project'])
        ->when($this->start && $this->end, function ($q) {
            return $q->whereBetween('so.due_date', [$this->start, $this->end]);
        })
        ->orderBy('so.so_no', 'desc');


        // dd($sales);



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
        //     discount,
        //     shipping,
        //     actual_sales,
        //    (qty * selling_price) + shipping + actual_sales - discount as subtotal,
        //     so.payment_status,
        //     so.payment_method,
        //     so.vat_type'
        // )
        // ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
        // // ->leftJoin('summaries as d', 'd.sales_order_id', '=', 'product_details.sales_order_id')
        // ->leftJoin('customers as c', 'c.id', '=', 'so.customer_id')
        // ->whereIn('so.status', ['Sales', 'Project'])
        // ->when($this->start && $this->end, function ($q) {
        //     return $q->whereBetween('due_date', [$this->start, $this->end]);
        // })
        // // ->whereNull('product_details.purchase_order_id') // Specify the table
        // ->orderBy('so.so_no', 'desc');

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
            'Zero Column',
            'Zero Column',
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
