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

    public function delete(){
        $result = array();
        $deletedRows = DB::table('audit_logs')->delete();
        
        $checkifEmpty = DB::table('audit_logs')->select('*')->get();
        
        if(count($checkifEmpty) === 0){
            return 'deleted';
        }else{
            return 'not_deleted';
        }
         
    }

}
