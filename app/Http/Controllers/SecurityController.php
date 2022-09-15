<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Silber\Bouncer\BouncerFacade as Bouncer;
use Yajra\DataTables\DataTables;
use DB;

class SecurityController extends Controller
{
    public $array_abilities = [
        "auditlogs",
        "override",
        "orderform",
        "orderformcreate",
        "orderformretrieve",
        "orderformdestroy",
        "purchaseorder",
        "purchaseordercreate",
        "purchaseorderretrieve",
        "purchaseorderupdate",
        "purchaseorderdestroy",
        "purchasestatusupdate",
        "salesorder",
        "salesordercreate",
        "salesorderretrieve",
        "salesorderupdate",
        "salesorderdestroy",
        "customerupdate",
        "customerretrieve",
        "customercreate",
        "customerdestroy",
        "customers",
        "supplies",
        "suppliesoverride",
        "vendors",
        "vendorscreate",
        "suppliescreate",
        "vendorsretrieve",
        "suppliesretrieve",
        "vendorsupdate",
        "suppliesupdate",
        "vendorsdestroy",
        "suppliesdestroy",
        "products",
        "securityview",
        "securitycreate",
        "securityretrieve",
        "securityupdate",
        "securitydestroy",
        "useraccounts",
        "useraccountscreate",
        "useraccountschangepass",
        "useraccountsupdate",
        "useraccountsdestroy",
        "productscreate",
        "productsretrieve",
        "productsupdate",
        "productsdestroy",
        "preference",
        "pricelist",
        "pricelistupload",
        "pricelistdestroy",
        "userassign",
        "productreturn",
        "productreturncreate",
        "productreturndelete",
        "salesstatusupdate",
        "salespaymentupdate",
        "salesvatupdate",
        "salesdeliveryupdate",
        "expenses",
        "expensescreate",
        "expensesupdate",
        "expensesdelete",
    ];

    public function roles()
    {
        return view('security');
    }

    public function table()
    {
        return DataTables::of(DB::table('roles'))->make(true);
    }

    public function create()
    {
        foreach ($this->array_abilities as $key => $value) {
            $abilities[$value] = false;
        }

        $role      = '';
        $abilities = collect($abilities);

        return view('security_form', compact('role', 'abilities'));
    }

    public function store(Request $request)
    {
        $data = $request->input();

        Bouncer::allow($data['role'])->to('manage');
        $role_id = DB::table('roles')->where('name', $data['role'])->get('id')[0]->id;
        DB::table('permissions')->where('entity_id', $role_id)->delete();
        Bouncer::allow($data['role'])->to('manage');

        foreach ($data['abilities'] as $key => $value) {
            if ($value == "true") {
                Bouncer::allow($data['role'])->to($key);
            }
            if ($value == "false") {
                Bouncer::disallow($data['role'])->to($key);
            }
        }

        return ['success' => true];
    }

    public function show($id)
    {
        $role = DB::table('roles')->where('id', $id)->get()[0]->name;

        $ability_ids = DB::table('permissions')->where('entity_id', $id)->get()->pluck('ability_id');

        $ability_list = DB::table('abilities')->whereIn('id', $ability_ids)->pluck('name')->toArray();

        foreach ($ability_list as $key => $value) {
            if (in_array($value, $ability_list)) {
                $abilities[$value] = true;
            } else {
                $abilities[$value] = false;
            }
        }
        $abilities = collect($abilities);

        return view('security_form', compact('role', 'abilities'));
    }

    public function destroy(Request $request)
    {
        DB::table('roles')->where('id', $request->id)->delete();
        DB::table('assigned_roles')->where('role_id', $request->id)->delete();

        return ['success' => true];
    }
}
