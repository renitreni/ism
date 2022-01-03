<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Vendor;
use Yajra\DataTables\DataTables;
use DB;
use PDF;
use Carbon\Carbon;

class VendorController extends Controller
{
    public function index()
    {
        return view('vendor');
    }

    public function table()
    {
        $vendors = Vendor::query()
                         ->selectRaw('vendors.id, vendors.contact_person,
            vendors.name, vendors.email, users.name as username')
                         ->join('users', 'users.id', '=', 'vendors.assigned_to');

        return DataTables::of($vendors)->make(true);
    }

    public function show($id)
    {
        $vendor = Vendor::find($id);

        return view('vendor_form', compact('vendor'));
    }

    public function create()
    {
        $vendor = collect([
            "id"              => "",
            "name"            => "",
            "contact_person"  => "",
            "landline"        => "",
            "mobile_phone"    => "",
            "email"           => "",
            "payment_method"  => "",
            "shipping_method" => "",
            "address"         => "",
            "assigned_to"     => "",
        ]);

        return view('vendor_form', compact('vendor'));
    }

    public function update(Request $request)
    {
        Vendor::query()->where('id', $request->id)->update($request->input());

        return ['success' => true];
    }

    public function store(Request $request)
    {
        $data                = $request->input();
        $data['assigned_to'] = auth()->id();
        Vendor::query()->insert($data);

        return ['success' => true];
    }

    public function destroy(Request $request)
    {
        DB::table('vendors')->where('id', $request->id)->delete();

        return ['success' => true];
    }

    public function getList(Request $request)
    {
        return [
            "results" => DB::table('vendors')
                           ->selectRaw("id as id, IFNULL(name, '') as text, shipping_method, contact_person as contact_name")
                           ->whereRaw("name LIKE '%{$request->term}%'")
                           ->get(),
        ];
    }

    public function printable()
    {
        $vendors = Vendor::all()->sortByDesc('id');

        $pdf = PDF::loadView('vendor_printable', ['vendors' => $vendors]);

        return $pdf->setPaper('a4')->download('VENDOR_LIST - ' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
