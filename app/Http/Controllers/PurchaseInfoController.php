<?php

namespace App\Http\Controllers;

use PDF;
use App\Supply;
use App\Product;
use App\Summary;
use App\AuditLog;
use Carbon\Carbon;
use App\Preference;
use App\PurchaseInfo;
use App\ProductDetail;
use App\PrintSetting;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Exports\PurchaseReportExcel;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class PurchaseInfoController extends Controller
{
    public function index()
    {
        return view('purchase');
    }

    public function purchase_stockin()
    {
        return view('purchase_stock_in');
    }
    public function table(Request $request)
    {
        Supply::recalibrate();
        $purchase_info = PurchaseInfo::query()
            ->selectRaw('purchase_infos.id, purchase_infos.subject,purchase_infos.received_date,
            purchase_infos.vat_type,purchase_infos.po_status,purchase_infos.payment_status,
            vendors.name as vendor_name, purchase_infos.tracking_number, purchase_infos.po_no,
            purchase_infos.requisition_no, users.name, purchase_infos.status, purchase_infos.created_at,
            purchase_infos.updated_at, grand_total, purchase_infos.due_date')
            ->leftJoin('summaries', 'summaries.purchase_order_id', '=', 'purchase_infos.id')
            ->leftJoin('vendors', 'vendors.id', '=', 'purchase_infos.vendor_id')
            ->leftJoin('users', 'users.id', '=', 'purchase_infos.assigned_to')
            ->where(function ($purchase_info) {
                $purchase_info->where('purchase_infos.po_status', '<>', 'SI')
                      ->orWhereNull('purchase_infos.po_status');
            });

            if ($request->filled('filter_payment')) {
                $purchase_info->where('purchase_infos.payment_status', $request->input('filter_payment'));
            }
            if ($request->filled('filter_status')) {
                $purchase_info->where('purchase_infos.status', $request->input('filter_status'));
            }
            if ($request->filled('filter_vat')) {
                $purchase_info->where('purchase_infos.vat_type', $request->input('filter_vat'));
            }
        return DataTables::of($purchase_info)->setTransformer(function ($data) {
            $data               = $data->toArray();
            $data['created_at'] = Carbon::parse($data['created_at'])->format('F j, Y');
            $data['received_date_display'] = $data['received_date'] ? Carbon::parse($data['received_date'])->format('F j, Y') : 'No Date';
            $data['due_date']   = isset($data['due_date']) ? Carbon::parse($data['due_date'])->format('F j, Y') : 'No Date';

            return $data;
        })->make(true);
    }

    public function table_stock_in(Request $request)
    {
        Supply::recalibrate();
        $purchase_info = PurchaseInfo::query()
            ->selectRaw('purchase_infos.id, purchase_infos.subject,purchase_infos.received_date,
            purchase_infos.vat_type,purchase_infos.po_status,purchase_infos.payment_status,
            vendors.name as vendor_name, purchase_infos.tracking_number, purchase_infos.po_no,
            purchase_infos.requisition_no, users.name, purchase_infos.status, purchase_infos.created_at,
            purchase_infos.updated_at, grand_total, purchase_infos.due_date')
            ->leftJoin('summaries', 'summaries.purchase_order_id', '=', 'purchase_infos.id')
            ->leftJoin('vendors', 'vendors.id', '=', 'purchase_infos.vendor_id')
            ->leftJoin('users', 'users.id', '=', 'purchase_infos.assigned_to')
            ->where('purchase_infos.po_status', 'SI');

            if ($request->filled('filter_payment')) {
                $purchase_info->where('purchase_infos.payment_status', $request->input('filter_payment'));
            }
            if ($request->filled('filter_status')) {
                $purchase_info->where('purchase_infos.status', $request->input('filter_status'));
            }
            if ($request->filled('filter_vat')) {
                $purchase_info->where('purchase_infos.vat_type', $request->input('filter_vat'));
            }
        return DataTables::of($purchase_info)->setTransformer(function ($data) {
            $data               = $data->toArray();
            $data['created_at'] = Carbon::parse($data['created_at'])->format('F j, Y');
            $data['received_date_display'] = $data['received_date'] ? Carbon::parse($data['received_date'])->format('F j, Y') : 'No Date';
            $data['due_date']   = isset($data['due_date']) ? Carbon::parse($data['due_date'])->format('F j, Y') : 'No Date';

            return $data;
        })->make(true);
    }

    public function create()
    {
        $purchase_info = collect([
            "id"               => "",
            "subject"          => "",
            "vendor_id"        => "",
            "requisition_no"   => "",
            "tracking_number"  => "",
            "contact_name"     => "",
            "phone"            => "",
            "due_date"         => "",
            "fax"              => "",
            "carrier"          => "",
            "deliver_to"       => "",
            "shipping_method"  => "",
            "assigned_to"      => "",
            "status"           => "Ordered",
            "date_received"    => "",
            "po_no"            => PurchaseInfo::generate()->newPONo(),
            "payment_method"   => "",
            "billing_address"  => Preference::status('billing_address_fill'),
            "delivery_address" => Preference::status('delivery_address_fill'),
            "check_number"     => "",
            "check_writer"     => "",
            "payment_status"   => "UNPAID",
            "tac"              => Preference::status('tac_po_fill'),
            "description"      => "",
            "updated_at"       => Carbon::now()->format('Y-m-d'),
            "vat_type"         => "VAT EX",
            "received_date"   => Carbon::now()->format('Y-m-d'),
        ]);

        $product_details = collect([]);

        $summary = collect([
            "id"                => "",
            "purchase_order_id" => "",
            "discount"          => "0",
            "shipping"          => "0",
            "sales_tax"         => "0",
            "sales_actual"      => "0",
            "sub_total"         => "0",
            "grand_total"       => "0",
        ]);

        return view('purchase_form', compact('purchase_info', 'product_details', 'summary'));
    }

    public function store(Request $request)
    {
        $data = $request->input();

        unset($data['overview']['vendor_name']);
        unset($data['overview']['vendor_address']);
        unset($data['overview']['vendor_mobile_phone']);

        if ($data['overview']['payment_method'] != 'Check') {
            $data['overview']['check_number'] = '';
            $data['overview']['check_writer'] = '';
        }

        $data['overview']['po_no']       = PurchaseInfo::generate()->newPONo();
        $data['overview']['created_at']  = Carbon::now()->format('Y-m-d');
        $data['overview']['assigned_to'] = auth()->id();
        $id                              = DB::table('purchase_infos')->insertGetId($data['overview']);

        $product_details = [];
        $pd              = false;
        if (isset($data['products'])) {
            foreach ($data['products'] as $item) {
                if (count($item) > 2) {
                    $product_details[] = [
                        'purchase_order_id' => $id,
                        //'sales_order_id'    => '',
                        //'product_return_id' => '',
                        'product_id'        => $item['product_id'],
                        'product_name'      => $item['product_name'],
                        'notes'             => $item['notes'],
                        'qty'               => $item['qty'],
                        'selling_price'     => $item['selling_price'],
                        'labor_cost'        => $item['labor_cost'],
                        'vendor_price'      => $item['vendor_price'],
                        'discount_item'     => $item['discount_item'],
                    ];

                    $product = Product::find($item['product_id']);
                    $product->selling_price = $item['selling_price'];
                    $product->vendor_price = $item['vendor_price'];
                    $product->save();
                }
            }

            $pd = DB::table('product_details')->insert($product_details);
        }

        if ($pd) {
            Supply::recalibrate();
        }

        $data['summary']['purchase_order_id'] = $id;
        $data['summary']['discount']          = $data['summary']['discount'] == '' ? '0' : $data['summary']['discount'];
        $data['summary']['shipping']          = $data['summary']['shipping'] == '' ? '0' : $data['summary']['shipping'];

        DB::table('summaries')->insert($data['summary']);

        // Record Action in Audit Log
        $name = auth()->user()->name;

        if($name != 'Super Admin') {
            \App\AuditLog::record([
                'name' => $name,
                'inputs' => $request->input(),
                'url' => $request->url(),
                'action_id' => $data['overview']['po_no'],
                'current' => $data['overview']['status'],
                'method' => "CREATED"
            ]);
        }

        return ['success' => true];
    }

    public function update(Request $request)
    {
        $data = $request->input();

        unset($data['overview']['vendor_name']);
        unset($data['overview']['vendor_address']);
        unset($data['overview']['vendor_mobile_phone']);

        if ($data['overview']['payment_method'] != 'Check') {
            $data['overview']['check_number'] = '';
            $data['overview']['check_writer'] = '';
        }

        // Record Action in Audit Log
        $name = auth()->user()->name;

        if($name != 'Super Admin') {
            \App\AuditLog::record([
                'name' => $name,
                'inputs' => $request->input(),
                'url' => $request->url(),
                'action_id' => $data['overview']['po_no'],
                'current' => $data['overview']['status'],
                'method' => "UPDATED"
            ]);
        }

        // Update Purchase Order Info
        PurchaseInfo::updateInfo($data['overview']);

        // Delete products that have been reset
        DB::table('product_details')->where('purchase_order_id', $data['overview']['id'])->delete();

        // Insert new Product Details
        $product_details = [];
        $pd              = false;
        if (isset($data['products'])) {
            foreach ($data['products'] as $item) {
                if (count($item) > 2) {
                    $product_details[] = [
                        'purchase_order_id' => $data['overview']['id'],
                        //'sales_order_id'    => '',
                        //'product_return_id' => '',
                        'product_id'        => $item['product_id'],
                        'product_name'      => $item['product_name'],
                        'notes'             => $item['notes'],
                        'qty'               => $item['qty'],
                        'selling_price'     => $item['selling_price'],
                        'labor_cost'        => $item['labor_cost'],
                        'vendor_price'      => $item['vendor_price'],
                        'discount_item'     => $item['discount_item'],
                    ];
                }
            }

            $pd = DB::table('product_details')->insert($product_details);
        }

        if ($pd) {
            Supply::recalibrate();
        }

        // Insert new summary
        $data['summary']['purchase_order_id'] = $data['overview']['id'];

        DB::table('summaries')->where('purchase_order_id', $data['overview']['id'])->delete();

        $data['summary']['discount'] = $data['summary']['discount'] == '' ? '0' : $data['summary']['discount'];
        $data['summary']['shipping'] = $data['summary']['shipping'] == '' ? '0' : $data['summary']['shipping'];

        DB::table('summaries')->insert($data['summary']);

        return ['success' => true];
    }

    public function destroy(Request $request)
    {

        // Reset supply count based on current product details
        $product_details = ProductDetail::fetchDataPO($request->id);
        foreach ($product_details as $item) {
            if ('Received' == $request->status) {
                if (Product::isLimited($item['product_id'])) {
                    Supply::decreCount($item['product_id'], $item['qty']);
                }
            }
        }

        // Record Action in Audit Log
        $name = auth()->user()->name;

        if($name != 'Super Admin') {
            \App\AuditLog::record([
                'name' => $name,
                'inputs' => $request->input(),
                'url' => $request->url(),
                'action_id' => $request->po_no,
                // 'current' => $current,
                'method' => "DELETED"
            ]);
        }

        ProductDetail::query()->where('purchase_order_id', $request->id)->delete();
        DB::table('purchase_infos')->where('id', $request->id)->delete();
        DB::table('summaries')->where('purchase_order_id', $request->id)->delete();


        return ['success' => true];
    }

    public function updateStatus(Request $request)
    {
        $data          = $request->input();
        $purchase_info = DB::table('purchase_infos')->where('id', $data['id'])->get()[0];

        if ($purchase_info->status != $data['status']) {

            // Record Action in Audit Log
            $name = auth()->user()->name;

            if($name != 'Super Admin') {
                \App\AuditLog::record([
                    'name' => $name,
                    'inputs' => $request->input(),
                    'url' => $request->url(),
                    'action_id' => $data['po_no'],
                    'current' => $data['status'],
                    'method' => "UPDATED"
                ]);
            }


            $purchase = PurchaseInfo::find($data['id']);
            $purchase->received_date = null;
            $purchase->status = $data['status'];
            if ($data['status'] == 'Received') {
                $purchase->received_date = Carbon::now()->format('Y-m-d');
            }

            $purchase->save();


            return ['success' => true];
        }

        if ($purchase_info->vat_type != $data['vat_type']) {
            DB::table('purchase_infos')->where('id', $data['id'])
                ->update(['vat_type' => $data['vat_type']]);

            return ['success' => true];
        }

        if ($purchase_info->po_status != $data['po_status']) {
            DB::table('purchase_infos')->where('id', $data['id'])
                ->update(['po_status' => $data['po_status']]);

            return ['success' => true];
        }

        if ($purchase_info->received_date != $data['received_date']) {
            DB::table('purchase_infos')->where('id', $data['id'])
                ->update(['received_date' => Carbon::parse($data['received_date'])->format('Y-m-d')]);

            return ['success' => true];
        }

        return ['success' => false];
    }

    public function updatePaymentStatus(Request $request)
    {
        $data = $request->input();

        // Record Action in Audit Log
        $name = auth()->user()->name;

        if($name != 'Super Admin') {
            \App\AuditLog::record([
                'name' => $name,
                'inputs' => $request->input(),
                'url' => $request->url(),
                'action_id' => $data['po_no'],
                'current' => $data['payment_status'],
                'method' => "UPDATED"
            ]);
        }

        DB::table('purchase_infos')->where('id', $data['id'])
            ->update(['payment_status' => $data['payment_status']]);


        return ['success' => true];
    }

    public function show($id)
    {
        $data = $this->getOverview($id);
        unset($data['purchase_info']['name']);
        $purchase_info   = $data['purchase_info'];
        $product_details = $data['product_details'];
        $summary         = $data['summary'];

        return view('purchase_form', compact('purchase_info', 'product_details', 'summary'));
    }

    public function printable($id)
    {
        $data            = $this->getOverview($id);
        $purchase_info   = $data['purchase_info'];
        $product_details = $data['product_details'];
        $summary         = $data['summary'];
        $sections        = [];
        $cnt             = -1;
        $print_setting   = PrintSetting::query()->first();

        foreach ($product_details as $key => $value) {
            if (count($value) == 1) {
                $sections[] = [
                    $value['category'] => 0,
                ];
                $cnt++;
            } else {
                $total_selling                      = $value['qty'] * $value['vendor_price'];
                $total_labor                        = $value['qty'] * $value['labor_cost'];
                $sections[$cnt][$value['category']] += $total_labor + ($total_selling - ($total_selling * ($value['discount_item'] / 100)));
            }
        }

        $hold_section = $sections;
        foreach ($hold_section as $index => $section) {
            foreach ($section as $key => $value) {
                $hold_section[$index] = [$this->converToRoman($index + 1) . '. ' . $key => $value];
            }
        }
        $sections = $hold_section;

        $pdf = PDF::loadView(
            'purchase_printable',
            [
                'purchase_info'   => $purchase_info,
                'product_details' => $product_details,
                'summary'         => $summary,
                'sections'        => $sections,
                'print_setting' => $print_setting,

            ]
        );

        return $pdf->setPaper('a4')
            ->setTemporaryFolder(public_path())
            ->download($purchase_info["po_no"] . '_' . $purchase_info["vendor_name"] . '.pdf');
    }

    public function previewPO($id)
    {
        $data            = $this->getOverview($id);
        $purchase_info   = $data['purchase_info'];
        $product_details = $data['product_details'];
        $summary         = $data['summary'];
        $sections        = [];
        $cnt             = -1;
        foreach ($product_details as $key => $value) {
            if (count($value) == 1) {
                $sections[] = [
                    $value['category'] => 0,
                ];
                $cnt++;
            } else {
                $total_selling                      = $value['qty'] * $value['vendor_price'];
                $total_labor                        = $value['qty'] * $value['labor_cost'];
                $sections[$cnt][$value['category']] += $total_labor + ($total_selling - ($total_selling * ($value['discount_item'] / 100)));
            }
        }

        $hold_section = $sections;
        foreach ($hold_section as $index => $section) {
            foreach ($section as $key => $value) {
                $hold_section[$index] = [$this->converToRoman($index + 1) . '. ' . $key => $value];
            }
        }
        $sections = $hold_section;

        return view('purchase_printable', compact('purchase_info', 'product_details', 'summary', 'sections'));
    }

    public function getOverview($id)
    {
        $purchase_info = PurchaseInfo::query()
            ->selectRaw('purchase_infos.*, IFNULL(vendors.name, \'\') as vendor_name, users.name,
                                     vendors.address as vendor_address, vendors.mobile_phone as vendor_mobile_phone')
            ->leftJoin('vendors', 'vendors.id', '=', 'purchase_infos.vendor_id')
            ->leftJoin('users', 'users.id', '=', 'purchase_infos.assigned_to')
            ->where('purchase_infos.id', $id)
            ->get()[0];

        $product_details = ProductDetail::query()
            ->selectRaw('products.category, products.unit, product_details.*')
            ->where('purchase_order_id', $id)
            ->join('products', 'products.id', 'product_details.product_id')
            ->get();
        $category        = '';
        $hold            = [];
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

        $summary = collect([
            'purchase_order_id' => '',
            'sales_order_id'    => '',
            'discount'          => '0',
            'sub_total'         => '0',
            'shipping'          => '0',
            'sales_tax'         => '0',
            'grand_total'       => '0',
        ]);

        if (Summary::query()->where('purchase_order_id', $id)->count() > 0) {
            $summary = Summary::query()->where('purchase_order_id', $id)->get()[0];
        }

        return [
            'purchase_info'   => $purchase_info,
            'product_details' => $product_details,
            'summary'         => $summary,
        ];
    }

    public function converToRoman($num)
    {
        $n   = intval($num);
        $res = '';

        //array of roman numbers
        $romanNumber_Array = [
            'M'  => 1000,
            'CM' => 900,
            'D'  => 500,
            'CD' => 400,
            'C'  => 100,
            'XC' => 90,
            'L'  => 50,
            'XL' => 40,
            'X'  => 10,
            'IX' => 9,
            'V'  => 5,
            'IV' => 4,
            'I'  => 1,
        ];

        foreach ($romanNumber_Array as $roman => $number) {
            //divide to get  matches
            $matches = intval($n / $number);

            //assign the roman char * $matches
            $res .= str_repeat($roman, $matches);

            //substract from the number
            $n = $n % $number;
        }

        // return the result
        return $res;
    }

    public function downloadPurchaseReport(Request $request): BinaryFileResponse
    {
        $date = now()->format('Y-m-d_H:i:s');
        $start = $request->start ?: now()->format('Y-m-d');
        $end = $request->end ?: now()->format('Y-m-d');
        return Excel::download(
            new PurchaseReportExcel($start, $end),
            "PURCHASE_REPORT_$date.xlsx"
        );
    }

    public function downloadPurchaseReportAll(Request $request): BinaryFileResponse
    {
        return Excel::download(
            new PurchaseReportExcel(0, 0),
            "PURCHASE_REPORT_" . now()->format('Y-m-dHms') . ".xlsx"
        );
    }
}
