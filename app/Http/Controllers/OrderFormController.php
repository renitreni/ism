<?php

namespace App\Http\Controllers;

use App\OrderForm;
use App\Product;
use App\ProductDetail;
use App\PurchaseInfo;
use App\SalesOrder;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use DB;

class OrderFormController extends Controller
{
    public function index()
    {
        return view('orderform');
    }

    public function table()
    {
        $purchase_info = OrderForm::query()
                                  ->selectRaw('order_forms.*, users.name as username, customers.name as customer_name')
                                  ->leftJoin('customers', 'customers.id', '=', 'order_forms.customer_id')
                                  ->join('users', 'users.id', '=', 'order_forms.prepared_by');

        return DataTables::of($purchase_info)->make(true);
    }

    public function create()
    {
        $orderform = collect([
            "customer_id"   => "",
            "acct_exec"     => "",
            "no"            => "",
            "so_no"         => "",
            "po_no"         => "",
            "prepared_by"   => auth()->user()->name,
            "stock_card_in" => "",
            "plate_no"      => "",
            "driver"        => "",
        ]);

        $product_details = collect([]);

        return view('orderform_form', compact('orderform', 'product_details'));
    }

    public function show($id)
    {
        $orderform = OrderForm::query()
                              ->selectRaw('order_forms.*, IFNULL(customers.name, \'\') as customer_name')
                              ->leftJoin('customers', 'customers.id', '=', 'order_forms.customer_id')
                              ->where('order_forms.id', $id)
                              ->get()[0];

        $product_details = ProductDetail::query()->where('sales_order_id', $orderform->so_no)
                                        ->where('purchase_order_id', $orderform->po_no)->get();

        return view('orderform_form', compact('orderform', 'product_details'));
    }

    public function store(Request $request)
    {
        $data = $request->input();
        if ($data['overview']['po_no'] != '') {
            $id = PurchaseInfo::query()->insertGetId([
                "assigned_to" => auth()->id(),
                "po_no"       => $data['overview']['po_no'],
                "created_at"  => Carbon::now()->format('Y-m-d'),
                "status"      => "Ordered",
            ]);
            DB::table('summaries')->insert([
                "purchase_order_id" => $id,
                "discount"          => "0",
                "shipping"          => "0",
                "sales_tax"         => "0",
            ]);
        }

        if ($data['overview']['so_no'] != '') {
            $id = SalesOrder::query()->insertGetId([
                "customer_id" => $data['overview']['customer_id'],
                "status"      => "Quote",
                "assigned_to" => auth()->id(),
                "created_at"  => Carbon::now()->format('Y-m-d'),
                "so_no"       => $data['overview']['so_no'],
            ]);

            DB::table('summaries')->insert([
                "sales_order_id" => $id,
                "discount"       => "0",
                "shipping"       => "0",
                "sales_tax"      => "0",
            ]);
        }

        if (isset($data['products'])) {
            foreach ($data['products'] as $item) {
                $item['purchase_order_id'] = $data['overview']['po_no'];
                $item['sales_order_id']    = $data['overview']['so_no'];
                DB::table('product_details')->insert($item);
            }
        }

        $data['overview']['prepared_by'] = auth()->id();
        OrderForm::query()->insert($data['overview']);

        return ['success' => true];
    }

    public function destroy(Request $request)
    {
        OrderForm::query()->where('id', $request->id)->delete();

        DB::table('product_details')->where('purchase_order_id', $request->id)->delete();
        DB::table('sales_orders')->where('id', $request->so_no)->delete();
        DB::table('summaries')->where('sales_order_id', $request->so_no)->delete();

        DB::table('product_details')->where('sales_order_id', $request->id)->delete();
        DB::table('purchase_infos')->where('id', $request->po_no)->delete();
        DB::table('summaries')->where('sales_order_id', $request->po_no)->delete();

        return ['success' => true];
    }
}
