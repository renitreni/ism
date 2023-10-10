<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use DB;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('audit');
    }

    public function table(DataTables $dataTables)
    {
        return $dataTables->queryBuilder(DB::table('audit_logs'))->make(true);
    }

    public function delete()
    {
        DB::table('audit_logs')->delete();

        return DB::table('audit_logs')->count() === 0 ? 'deleted' : 'not_deleted';
    }

}
