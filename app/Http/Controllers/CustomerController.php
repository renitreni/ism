<?php

namespace App\Http\Controllers;

use App\Customer;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Yajra\DataTables\DataTables;
use DB;
use PDF;
use Carbon\Carbon;

class CustomerController extends Controller
{
    public function index()
    {
        return view('customer');
    }

    public function table()
    {
        $customer = Customer::query()
                            ->selectRaw('customers.*, users.name as username')
                            ->join('users', 'users.id', '=', 'customers.assigned_to');

        return DataTables::of($customer)->make(true);
    }

    public function create()
    {
        $customer = collect([
            "name"           => "",
            "contact_person" => "",
            "landline"       => "",
            "mobile_phone"   => "",
            "email"          => "",
            "address"        => "",
            "payment_method" => "",
            "assigned_to"    => "",
        ]);

        return view('customer_form', compact('customer'));
    }

    public function store(Request $request)
    {
        $data                = $request->input();
        $data['assigned_to'] = auth()->id();
        Customer::create($data);

        return ['success' => true];
    }

    public function show($id)
    {
        $customer = Customer::find($id);

        return view('customer_form', compact('customer'));
    }

    public function update(Request $request)
    {
        Customer::query()->where('id', $request->overview['id'])->update($request->overview);

        return ['success' => true];
    }

    public function destroy(Request $request)
    {
        Customer::query()->where('id', $request->id)->delete();

        return ['success' => true];
    }

    public function getList(Request $request)
    {
        return [
            "results" => DB::table('customers')
                           ->selectRaw("id as id, name as text, mobile_phone as phone, address")
                           ->whereRaw("name LIKE '%{$request->term}%'")
                           ->where('deleted_at', null)
                           ->get(),
        ];
    }

    public function printable()
    {
        $customers = Customer::all()->sortByDesc('id');

        $pdf = PDF::loadView('customer_printable', ['customers' => $customers]);

        return $pdf->setPaper('a4')
            ->setTemporaryFolder(public_path())
            ->download('CUSTOMER_LIST - ' . Carbon::now()->format('Y-m-d') . '.pdf');
    }
}
