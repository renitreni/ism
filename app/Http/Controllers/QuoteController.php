<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Traits\HasProductDetail;
use Carbon\Carbon;
use App\SalesOrder;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class QuoteController extends Controller
{
    use HasProductDetail;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('quote');
    }

    public function table()
    {
        $vendors = SalesOrder::query()
            ->selectRaw('sales_orders.*, users.name as username, customers.name as customer_name,
                             summaries.grand_total')
            ->leftJoin('summaries', 'summaries.sales_order_id', '=', 'sales_orders.id')
            ->leftJoin('customers', 'customers.id', '=', 'sales_orders.customer_id')
            ->leftJoin('users', 'users.id', '=', 'sales_orders.assigned_to')
            ->whereNotIn('sales_orders.status', ['Sales', 'Project']);

        return DataTables::of($vendors)->setTransformer(function ($data) {
            $data                   = $data->toArray();
            $data['created_at']     = Carbon::parse($data['created_at'])->format('F j, Y');
            $data['updated_at']     = Carbon::parse($data['updated_at'])->format('F j, Y');
            $data['due_date']       = isset($data['due_date']) ? Carbon::parse($data['due_date'])->format('F j, Y') : 'No Date';
            $data['can_be_shipped'] = 1;

            $product_details = $this->getProductDetail($data['id']);
            foreach ($product_details as $products) {
                $diff = $products->quantity - $products->qty;

                if ($diff < 0 && $products->type == 'limited') {
                    $data['can_be_shipped'] = 0;
                }
            }

            return $data;
        })->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
