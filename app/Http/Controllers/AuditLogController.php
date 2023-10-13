<?php

namespace App\Http\Controllers;

use Yajra\DataTables\DataTables;
use DB;
use Illuminate\Http\Request;

class AuditLogController extends Controller
{
    public function index()
    {
        return view('audit');
    }

    public function table(DataTables $dataTables, Request $request)
    {   
        $query = DB::table('audit_logs');
    
        // Apply filters
        if ($request->filled('filter_so_po') && $request->input('filter_so_po') == 'SO') {
            if ($request->filled('filter_ship1')) {
                $query->where('current', $request->input('filter_ship1'));
            }
            if ($request->filled('filter_ship2')) {
                $query->where('previous', $request->input('filter_ship2'));
            }
        }else{
            if ($request->filled('filter_status1')) {
                $query->where('current', $request->input('filter_status1'));
            }
            if ($request->filled('filter_status2')) {
                $query->where('previous', $request->input('filter_status2'));
            }
        }
        
        return $dataTables->queryBuilder($query)->make(true);
    }

    public function delete()
    {
        DB::table('audit_logs')->delete();

        return DB::table('audit_logs')->count() === 0 ? 'deleted' : 'not_deleted';
    }

}
