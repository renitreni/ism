<?php

namespace App\Http\Controllers;

use App\ProductDetail;
use App\ProductReturn;
use App\PurchaseInfo;
use App\ReturnStatus;
use App\SalesOrder;
use App\Summary;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use PDF;

class ProductReturnController extends Controller
{
    public function index()
    {
        return view('return');
    }

    public function table()
    {
        $product_return = ProductReturn::query()
                                       ->selectRaw('product_returns.*, users.name as username, customers.name as customer_name,return_statuses.status,
                             sales_orders.so_no, return_statuses.updated_at as status_created_at')
                                       ->leftJoin('customers', 'customers.id', '=', 'product_returns.customer_id')
                                       ->leftJoin('sales_orders', 'sales_orders.id', '=',
                                           'product_returns.sales_order_id')
                                       ->leftJoin(DB::raw('(SELECT * FROM return_statuses ORDER BY created_at desc) as return_statuses'),
                                           'return_statuses.product_return_id', '=', 'product_returns.id')
                                       ->join('users', 'users.id', '=', 'product_returns.assigned_to');

        return DataTables::of($product_return)->setTransformer(function ($data) {
            $data                      = $data->toArray();
            $data['status_created_at'] = $data['status_created_at'] != null ? Carbon::parse($data['status_created_at'])
                                                                                    ->format('F j, Y') : '';
            $data['created_at']        = $data['created_at'] != null ? Carbon::parse($data['created_at'])
                                                                             ->format('F j, Y') : '';

            return $data;
        })->make(true);
    }

    public function create()
    {
        $product_return = collect([
            "id"             => "",
            "customer_id"    => "",
            "sales_order_id" => "",
            "pr_no"          => ProductReturn::generate()->newPRNo(),
            "return_type"    => "",
            "contact_person" => "",
            "reason"         => "",
            "remarks"        => "",
            "assigned_to"    => "",
            "created_at"     => "",
            "updated_at"     => "",
            "status"         => "",
        ]);

        $product_details = collect([]);

        return view('return_form', compact('product_return', 'product_details'));
    }

    public function store(Request $request)
    {
        $product_return = new ProductReturn();
        $id             = $product_return->store($request->overview);

        $return_status = new ReturnStatus();
        $return_status->store($id, $request->overview);

        $data = $request->input();

        if (isset($data['products'])) {
            foreach ($data['products'] as $item) {
                unset($item['category']);
                unset($item['quantity']);
                unset($item['unit']);
                if (count($item) > 2) {
                    $item['product_return_id'] = $id;
                    DB::table('product_details')->insert($item);
                }
            }
        }

        return ['success' => true];
    }

    public function show($id)
    {
        $product_return = ProductReturn::query()
                                       ->selectRaw('product_returns.*, users.name as username, customers.name as customer_name,
                             sales_orders.so_no')
                                       ->leftJoin('customers', 'customers.id', '=', 'product_returns.customer_id')
                                       ->leftJoin('sales_orders', 'sales_orders.id', '=',
                                           'product_returns.sales_order_id')
                                       ->join('users', 'users.id', '=', 'product_returns.assigned_to')
                                       ->get()[0];

        $product_details = ProductDetail::query()
                                        ->selectRaw('products.category, products.unit, product_details.*')
                                        ->where('product_return_id', $id)
                                        ->join('products', 'products.id', 'product_details.product_id')
                                        ->get();

        $category = '';
        $hold     = [];
        foreach ($product_details->toArray() as $value) {
            if ($value['category'] != $category) {
                $hold[]   = ['category' => $value['category']];
                $category = $value['category'];
            }
            unset($value['manual_id']);
            unset($value['name']);
            unset($value['code']);
            unset($value['manufacturer']);
            unset($value['description']);
            unset($value['batch']);
            unset($value['color']);
            unset($value['size']);
            unset($value['weight']);
            unset($value['assigned_to']);
            unset($value['id']);
            $hold[] = $value;
        }

        $product_details = collect($hold);

        return view('return_form', compact('product_return', 'product_details'));
    }

    public function destroy(Request $request)
    {
        $data = $request->input();
        ProductReturn::query()->where('id', $data['id'])->delete();
        ProductDetail::query()->where('product_return_id', $data['id'])->delete();
        ReturnStatus::query()->where('product_return_id', $data['id'])->delete();

        return ['success' => true];
    }

    public function printable($id)
    {
        $data            = $this->getOverview($id);
        $sales_order     = $data['sales_order'];
        $product_details = $data['product_details'];
        $sections        = [];
        $cnt             = -1;

        foreach ($product_details as $key => $value) {
            if (count($value) == 1) {
                $sections[] = [
                    $value['category'] => 0,
                ];
                $cnt++;
            } else {
                if ($cnt == -1) {
                    $cnt = 0;
                }
                $total_selling                      = $value['qty'] * $value['selling_price'];
                $total_labor                        = $value['qty'] * $value['labor_cost'];
                $sections[$cnt][$value['category']] += $total_labor + ($total_selling - ($total_selling * ($value['discount_item'] / 100)));
            }
        }

        $pdf = PDF::loadView('return_printable',
            [
                'sales_order'     => $sales_order,
                'product_details' => $product_details,
            ]);

        return $pdf->setPaper('a4')
                    ->setTemporaryFolder(public_path())
                   ->download('RETURNS ' . $sales_order["pr_no"] . ' ' . $sales_order["customer_name"] . '.pdf');
    }

    public function getOverview($id)
    {
        $sales_order = ProductReturn::query()
                                    ->selectRaw('sales_orders.so_no, product_returns.*, IFNULL(customers.name, \'\') as customer_name')
                                    ->leftJoin('customers', 'customers.id', '=', 'product_returns.customer_id')
                                    ->leftJoin('sales_orders', 'sales_orders.id', '=', 'product_returns.sales_order_id')
                                    ->where('product_returns.id', $id)
                                    ->get()[0];

        $product_details = ProductDetail::query()
                                        ->selectRaw('products.code, products.category, products.unit, products.manual_id, product_details.*, supplies.quantity')
                                        ->where('product_return_id', $id)
                                        ->join('products', 'products.id', 'product_details.product_id')
                                        ->join('supplies', 'supplies.product_id', 'product_details.product_id')
                                        ->get();

        $category = '';
        $hold     = [];
        foreach ($product_details->toArray() as $value) {
            if ($value['category'] != $category) {
                $hold[]   = ['category' => $value['category']];
                $category = $value['category'];
            }
            unset($value['name']);
            unset($value['manufacturer']);
            unset($value['description']);
            unset($value['batch']);
            unset($value['color']);
            unset($value['size']);
            unset($value['weight']);
            unset($value['assigned_to']);
            unset($value['id']);
            $hold[] = $value;
        }
        $product_details = collect($hold);

        return [
            'sales_order'     => $sales_order,
            'product_details' => $product_details,
        ];
    }

    public function updateStatus(Request $request)
    {
        ReturnStatus::query()->where('product_return_id', $request->id)
                    ->update([
                        'status'     => $request->status,
                        'updated_at' => Carbon::now(),
                    ]);

        return ['success' => true];
    }

    public function update(Request $request)
    {
        ProductReturn::query()->where('id', $request->id)
                     ->update([
                         'remarks' => $request->remarks,
                     ]);

        return ['success' => true];
    }
}
