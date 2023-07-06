<?php

namespace App\Http\Controllers;

use App\JobOrder;
use App\JobOrderProduct;
use App\JobOrderStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Spatie\DbDumper\Databases\MySql;
use Symfony\Component\Process\Process;
use RealRashid\SweetAlert\Facades\Alert;
use DB;

class OverrideController extends Controller
{
    public function index()
    {
        $list    = scandir('.', SCANDIR_SORT_DESCENDING);
        $backups = [];
        foreach ($list as $value) {
            if (strpos($value, '.sql') > 0) {
                array_push($backups, $value);
            }
        }
        $list = $backups;

        return view('override', compact('list'));
    }

    public function backupSQL()
    {
        MySql::create()
             ->setDbName(env('DB_DATABASE'))
             ->setUserName(env('DB_USERNAME'))
             ->setPassword(env('DB_PASSWORD'))
             ->dumpToFile('BACKUP_' . Carbon::now()->format('Y-m-d_h:i:sa') . '.sql');

        Alert::success('Created Restore Point', 'Restore Point Created!');

        return redirect()->back();
    }

    public function restoreSQL(Request $request)
    {
        $cmd = "mysql -u " . env('DB_USERNAME') . " --password=" . env('DB_PASSWORD') . " " . env('DB_DATABASE') . " < backups/$request->dumpsql";
        exec($cmd);

        Alert::success('Back Restored', 'Successful Restored!');

        return redirect()->back();
    }

    public function databaseWipe(Request $request)
    {
        if ($request->db) {
            switch ($request->db) {
                case 'po':
                    DB::table('purchase_infos')->delete();
                    DB::table('product_details')->whereNull('sales_order_id')->delete();
                    DB::table('summaries')->whereNull('sales_order_id')->delete();
                    break;
                case 'so':
                    DB::table('sales_orders')->delete();
                    DB::table('product_details')->whereNull('purchase_order_id')->delete();
                    DB::table('summaries')->whereNull('purchase_order_id')->delete();
                    break;
                case 'customers':
                    DB::table('customers')->delete();
                    break;
                case 'products_inventory':
                    DB::table('products')->delete();
                    DB::table('supplies')->delete();
                    break;
                case 'vendors':
                    DB::table('vendors')->delete();
                    break;
                case 'expenses':
                    DB::table('expenses')->delete();
                    break;
                case 'supplies':
                    DB::table('supplies')->update(['quantity' => 0]);
                case 'job_order':
                    JobOrderStatus::query()->delete();
                    JobOrderProduct::query()->delete();
                    JobOrder::query()->delete();
                    break;
            }
            Alert::success('DB Wiped Successful!', 'Successful Wiped!');
        } else {
            Alert::success('DB Wiped Failed!', 'Please try again!');
        }

        return redirect()->back();
    }
}
