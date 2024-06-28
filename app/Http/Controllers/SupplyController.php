<?php

namespace App\Http\Controllers;

use App\Supply;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class SupplyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        return view('supply');
    }

    public function table(Request $request)
    {
        Supply::recalibrate();
        $supplies = Supply::query()
            ->selectRaw('supplies.*, users.name as username,
                         products.name as product_name, products.manual_id,
                         products.selling_price,
                         products.code,
                         products.unit,
                         ifnull(po_sum.total, 0) as po_count,
                         ifnull(so_sum.total, 0) as so_count,
                         ifnull(so_qty.total, 0) as so_qty,
                         ifnull(po_total.total, 0) as po_total')
            ->join('products', 'products.id', '=', 'supplies.product_id')
            ->join('users', 'users.id', '=', 'supplies.assigned_to')
            ->leftJoin(
                DB::raw(
                    '(SELECT COUNT(product_details.id) as total,
                                 product_id
                                 FROM product_details
                                 LEFT JOIN purchase_infos ON purchase_infos.id = product_details.purchase_order_id
                                 WHERE purchase_infos.status LIKE \'Received\'
                                 and sales_order_id IS NULL
                                 and product_details.deleted_at IS NULL group by product_id) as po_sum'),
                'po_sum.product_id', '=', 'supplies.product_id'
            )
            ->leftJoin(
                DB::raw(
                    '(SELECT COUNT(product_details.id) as total,
                                 product_id
                                 FROM product_details
                                 LEFT JOIN sales_orders ON sales_orders.id = product_details.sales_order_id
                                 WHERE sales_orders.delivery_status LIKE \'Shipped\'
                                 and purchase_order_id IS NULL
                                 and product_details.deleted_at IS NULL group by product_id) as so_sum'),
                'so_sum.product_id', '=', 'supplies.product_id'
            )
            ->leftJoin(
                DB::raw(
                    '(SELECT SUM(product_details.qty) as total,
                                 product_id
                                 FROM product_details
                                 LEFT JOIN sales_orders ON sales_orders.id = product_details.sales_order_id
                                 WHERE sales_orders.delivery_status LIKE \'Shipped\'
                                 and purchase_order_id IS NULL
                                 and product_details.deleted_at IS NULL group by product_id) as so_qty'),
                'so_qty.product_id', '=', 'supplies.product_id'
            )
            ->leftJoin(
                DB::raw(
                    '(SELECT SUM(product_details.qty) as total,
                                 product_id
                                 FROM product_details
                                 LEFT JOIN purchase_infos ON purchase_infos.id = product_details.purchase_order_id
                                 WHERE purchase_infos.status LIKE \'Received\'
                                 and sales_order_id IS NULL
                                 and product_details.deleted_at IS NULL group by product_id) as po_total'),
                'po_total.product_id', '=', 'supplies.product_id'
            )
            ->orderBy('supplies.quantity', 'desc');

        return DataTables::of($supplies)->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        $supply = collect([
            "product_name" => "",
            "product_id"   => "",
            "unit_cost"    => "",
        ]);

        return view('supply_form', compact('supply'));
    }

    /**
     * Show the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show($id)
    {
        $supply = Supply::query()
                      ->selectRaw('supplies.*, products.name as product_name')
                      ->join('products', 'products.id', '=', 'supplies.product_id')
                      ->where('supplies.id', $id)
                      ->get()[0];

        return view('supply_form', compact('supply'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit($id)
    {
        return view('edit');
    }

    public function recalibrate_data(){

        Supply::recalibrate();

        return redirect()->back()->with('message', 'Data recalibrated successfully.');

    }

    public function getPOLinks(Request $request)
    {
        $links = DB::table('product_details')
            ->selectRaw('DISTINCT purchase_order_id as link,
            purchase_infos.po_no  as number, purchase_infos.status')
            ->join('purchase_infos', 'purchase_infos.id', 'purchase_order_id')
            ->where('product_id', $request->product_id)
            ->where('purchase_order_id', '<>', null)
            ->where('purchase_infos.status', 'Received')
            ->get();

        return $links;
    }

    public function getSOLinks(Request $request)
    {
        $links = DB::table('product_details')
            ->selectRaw('DISTINCT sales_order_id as link,
            sales_orders.so_no as number, sales_orders.status')
            ->join('sales_orders', 'sales_orders.id', 'sales_order_id')
            ->where('product_id', $request->product_id)
            ->where('sales_order_id', '<>', null)
            ->where('sales_orders.delivery_status', 'Shipped')
            ->get();

        return $links;
    }

    public function updateQuantity(Request $request)
    {
        Supply::query()
            ->where('id', $request->id)
            ->update(['quantity' => $request->quantity]);
    }

    public function previewPO($id)
    {
        $results = DB::table('product_details')
            ->selectRaw('DISTINCT purchase_order_id as link,
            purchase_infos.po_no  as number, purchase_infos.*, vendors.name as vendor_name')
            ->leftjoin('purchase_infos', 'purchase_infos.id', 'purchase_order_id')
            ->leftjoin('vendors', 'vendors.id', 'purchase_infos.vendor_id')
            ->where('product_id', $id)
            ->where('purchase_order_id', '<>', null)
            ->get();

        $type = 'PO';

        return view('supply_printable', compact('results', 'type'));
    }

    public function previewSO($id)
    {
        $results = DB::table('product_details')
            ->selectRaw('DISTINCT sales_order_id as link,
            sales_orders.so_no as number, sales_orders.*, customers.name as customer_name')
            ->join('sales_orders', 'sales_orders.id', 'sales_order_id')
            ->leftjoin('customers', 'customers.id', 'sales_orders.customer_id')
            ->where('product_id', $id)
            ->where('sales_order_id', '<>', null)
            ->get();

        $type = 'SO';

        return view('supply_printable', compact('results', 'type'));
    }

    public function versus($id)
    {
        $po = DB::table('product_details')
            ->selectRaw('product_name, po.po_no, po.`status`, SUM(qty) as total')
            ->join('purchase_infos as po', 'po.id', 'purchase_order_id')
            ->where('product_id', $id)
            ->whereNull('sales_order_id')
            ->groupBy('po.status', 'product_name', 'po.po_no')->get();


        $so = DB::table('product_details')
            ->selectRaw('product_name, so.so_no, so.`status`, SUM(qty) as total, so.delivery_status')
            ->join('sales_orders as so', 'so.id', 'sales_order_id')
            ->where('product_id', $id)
            ->whereNull('purchase_order_id')
            ->groupBy('so.status', 'product_name', 'so.so_no', 'so.delivery_status')->get();

        return view('supply_versus', compact('po', 'so'));
    }
}
