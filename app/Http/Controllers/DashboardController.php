<?php

namespace App\Http\Controllers;

use App\PurchaseInfo;
use App\SalesOrder;
use App\Supply;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;

class DashboardController extends Controller
{
    public function index()
    {
        Supply::recalibrate();

        $supply = Supply::query()
                        ->selectRaw('(supplies.quantity * products.selling_price) total')
                        ->join('products', 'products.id', '=', 'supplies.product_id');

        $assets = $this->computeStock($supply);

        $supply = Supply::query()
                        ->selectRaw('supplies.quantity total')
                        ->join('products', 'products.id', '=', 'supplies.product_id')
                        ->where('supplies.quantity', '<>', 0);

        $stocks = $this->computeStock($supply);

        $po_count = PurchaseInfo::query()->count();

        $so_count = SalesOrder::query()->count();

        return view('dashboard', compact('assets', 'stocks', 'po_count', 'so_count'));
    }

    public function computeStock($result)
    {
        $hold = 0;
        foreach ($result->get()->toArray() as $value) {
            $hold += $value['total'];
        }

        return $hold;
    }

    public function inStock()
    {
        $supply = Supply::query()
                        ->selectRaw('supplies.*, products.name as product_name, products.manual_id, products.code')
                        ->join('products', 'products.id', '=', 'supplies.product_id')
                        ->where('supplies.quantity', '<>', 0);

        return DataTables::of($supply)->make(true);
    }

    public function outOfStock()
    {
        $supply = Supply::query()
                        ->selectRaw('supplies.*, products.name as product_name, products.manual_id, products.code')
                        ->join('products', 'products.id', '=', 'supplies.product_id')
                        ->where('supplies.quantity', '=', 0);

        return DataTables::of($supply)->make(true);
    }

    public function orderedPO()
    {
        $po = PurchaseInfo::query()->where('status', 'Ordered');

        return DataTables::of($po)->make(true);
    }

    public function quoteSO()
    {
        $so = SalesOrder::query()->where('status', 'Quote');

        return DataTables::of($so)->make(true);
    }

    public function returnedSO()
    {
        $so = SalesOrder::query()->where('status', 'Returned');

        return DataTables::of($so)->make(true);
    }

    public function totalPO(Request $request)
    {
        $data   = $request->input();
        $result = DB::table('purchase_infos')
                    ->selectRaw('COALESCE(SUM(summaries.grand_total),0) as total')
                    ->leftJoin('summaries', 'summaries.purchase_order_id', '=', 'purchase_infos.id')
                    ->where('status', 'Received');

        if ($data['start'] != '') {
            $result->whereBetween('purchase_infos.created_at', [$data['start'], $data['end']]);
        }

        return $result->get();
    }

    public function totalSO(Request $request)
    {
        $data   = $request->input();
        $result = DB::table('sales_orders')
                    ->selectRaw('COALESCE(SUM(summaries.grand_total),0) as total')
                    ->leftJoin('summaries', 'summaries.sales_order_id', '=', 'sales_orders.id')
                    ->where('delivery_status', 'Shipped');

        if ($data['start'] != '') {
            $result->whereBetween('sales_orders.created_at', [$data['start'], $data['end']]);
        }

        return $result->get();
    }

    public function assetsPrintable()
    {
        $supply = Supply::query()
                        ->selectRaw(
                            'products.name,
                            products.category,
                            products.manual_id,
                            supplies.quantity,
                            products.selling_price
                            '
                        )
                        ->join('products', 'products.id', '=', 'supplies.product_id')
                        ->orderBy('supplies.quantity', 'desc')
                        ->get();

        //return view('dashboard_assets_printable', compact('supply'));

        $pdf = PDF::loadView('dashboard_assets_printable', ['supply' => $supply]);

        return $pdf->setPaper('a4')->download('ASSETS_AUDIT - ' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function poTotalPrintable($start, $end)
    {
        $result = DB::table('purchase_infos')
                    ->selectRaw('purchase_infos.*, summaries.*,
            purchase_infos.created_at as date_created, vendors.name as vendor_name')
                    ->leftJoin('vendors', 'vendors.id', '=', 'purchase_infos.vendor_id')
                    ->leftJoin('summaries', 'summaries.purchase_order_id', '=', 'purchase_infos.id')
                    ->orderBy('purchase_infos.po_no', 'desc');

        if ($start != '') {
            $result->whereBetween('purchase_infos.created_at', [$start, $end]);
        }

        $data = $result->get();

        //return view('dashboard_po_printable', compact('data'));

        $pdf = PDF::loadView('dashboard_po_printable', ['data' => $data]);

        return $pdf->setPaper('a4')->download('PO_AUDIT - ' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function soTotalPrintable($start, $end)
    {
        $result = DB::table('sales_orders')
                    ->selectRaw('sales_orders.*, summaries.*, customers.name as customer_name, sales_orders.created_at as date_created')
                    ->leftJoin('customers', 'customers.id', '=', 'sales_orders.customer_id')
                    ->leftJoin('summaries', 'summaries.sales_order_id', '=', 'sales_orders.id')
                    ->orderBy('sales_orders.so_no', 'desc');

        if ($start != '') {
            $result->whereBetween('sales_orders.created_at', [$start, $end]);
        }

        $data = $result->get();

        //return view('dashboard_so_printable', compact('data'));

        $pdf = PDF::loadView('dashboard_so_printable', ['data' => $data]);

        return $pdf->setPaper('a4')->download('SO_AUDIT - ' . Carbon::now()->format('Y-m-d') . '.pdf');
    }

    public function qtnTotalPrintable($start, $end)
    {
        $result = DB::table('sales_orders')
                    ->selectRaw('sales_orders.*, summaries.*, customers.name as customer_name, sales_orders.created_at as date_created')
                    ->leftJoin('customers', 'customers.id', '=', 'sales_orders.customer_id')
                    ->leftJoin('summaries', 'summaries.sales_order_id', '=', 'sales_orders.id')
                    ->orderBy('sales_orders.so_no', 'desc');

        if ($start != '') {
            $result->whereBetween('sales_orders.created_at', [$start, $end]);
        }

        $data = $result->get();

        //return view('dashboard_so_printable', compact('data'));

        $pdf = PDF::loadView('dashboard_qtn_printable', ['data' => $data]);

        return $pdf->setPaper('a4')->download('QTN_AUDIT - ' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
