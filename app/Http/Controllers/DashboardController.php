<?php

namespace App\Http\Controllers;

use App\Expenses;
use App\Exports\AssetsExcel;
use App\Exports\ExpensesTotalExcel;
use App\Exports\LaborTotalExcel;
use App\Exports\POTotalExcel;
use App\Exports\QTNTotalExcel;
use App\Exports\SOTotalExcel;
use App\Product;
use App\ProductDetail;
use App\PurchaseInfo;
use App\SalesOrder;
use App\Supply;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\DataTables;
use Maatwebsite\Excel\Facades\Excel;

class DashboardController extends Controller
{
    public function index()
    {
        Supply::recalibrate();

        //#################
        $supply = Supply::query()
            ->selectRaw('(supplies.quantity * products.selling_price) total')
            ->join('products', 'products.id', '=', 'supplies.product_id');

        $assets = $this->computeStock($supply);
        //#################
        //-----------------
        $supply = Supply::query()
            ->selectRaw('supplies.quantity total')
            ->join('products', 'products.id', '=', 'supplies.product_id')
            ->where('supplies.quantity', '<>', 0);

        $stocks = $this->computeStock($supply);
        //------------------
        $po_count = PurchaseInfo::query()->count();
        $so_count = SalesOrder::query()->count();

        $product_details = (new ProductDetail())->getTotalProject();
        $labor_total     = $this->computeStock($product_details, 'subtotal');
        $expenses_total  = Expenses::query()->sum('total_amount');
        return view('dashboard', compact('assets', 'stocks', 'po_count', 'so_count', 'labor_total', 'expenses_total'));
    }

    public function getFastMoving()
    {
        return DataTables::of((new Product())->results())->make(true);
    }

    public function computeStock($result, $key = 'total')
    {
        $hold = 0;
        foreach ($result->get()->toArray() as $value) {
            $hold += $value[$key];
        }

        return $hold;
    }

    public function inStock()
    {
        return DataTables::of((new Supply())->results()->where('supplies.quantity', ' <> ', 0))->make(true);
    }

    public function outOfStock()
    {
        return DataTables::of((new Supply())->results()->where('supplies.quantity', ' = ', 0))->make(true);
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

    public function totalLaborPrintable(): BinaryFileResponse
    {
        $date = now()->format('Y-m-d_H:i:s');

        return Excel::download(new LaborTotalExcel(), "LABOR_AUDIT-$date.xlsx");
    }

    public function assetsPrintable(): BinaryFileResponse
    {
        $date = now()->format('Y-m-d_H:i:s');

        return Excel::download(new AssetsExcel(), "ASSETS_AUDIT-$date.xlsx");
    }

    public function totalPO(Request $request)
    {
        return (new PurchaseInfo())->total($request->start, $request->end)->sum('grand_total');
    }

    public function poTotalPrintable(Request $request): BinaryFileResponse
    {
        $date = now()->format('Y-m-d_H:i:s');
        $start = 0;
        $end = 0;
        if ($request->filled('daterange')) {
            $dateRange = explode(' - ', $request->get('daterange'));
            $start = Carbon::parse($dateRange[0])->format('Y-m-d');
            $end = Carbon::parse($dateRange[1])->format('Y-m-d');
        }

        return Excel::download(new POTotalExcel($start, $end), "PO_AUDIT-$date.xlsx");
    }

    public function totalExpenses(Request $request)
    {
        return (new Expenses())->total($request->start, $request->end)->sum('total_amount');
    }

    public function totalSO(Request $request)
    {
        return (new SalesOrder())->total($request->start, $request->end)->sum('grand_total');
    }

    public function soTotalPrintable($start, $end): BinaryFileResponse
    {
        $date = now()->format('Y - m - d_H:i:s');

        return Excel::download(new SOTotalExcel($start, $end), "SO_AUDIT-$date.xlsx");
    }

    public function qtnTotalPrintable($start, $end)
    {
        $date = now()->format('Y - m - d_H:i:s');

        return Excel::download(new QTNTotalExcel($start, $end), "QTN_AUDIT-$date.xlsx");
    }

    public function expensesPrintable(Request $request)
    {
        $dates = explode(' - ', $request->get('daterange'));
        $start = $dates[0] ?? '';
        $end = $dates[1] ?? '';
        $date = now()->format('Y - m - d_H:i:s');

        return Excel::download(new ExpensesTotalExcel($start, $end), "EXPENSES_AUDIT-$date.xlsx");
    }
}
