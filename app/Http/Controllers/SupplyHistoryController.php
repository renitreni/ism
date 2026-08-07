<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\SupplyHistory;
use App\User;
use App\Vendor;
use Yajra\DataTables\DataTables;
use DB;
use PDF;
use Carbon\Carbon;

class SupplyHistoryController extends Controller
{
    public function index()
    {
        return view('supply_history');
    }

    public function table()
    {
        $supply_history = SupplyHistory::latest('id')->get();

        for ($x=0; $x < count($supply_history) ; $x++) {
            $user = User::where('id', $supply_history[$x]['action_by'])->first();
            $supply_history[$x]['action_by'] = $user->name;

        }

        return DataTables::of($supply_history)->make(true);
    }

}
