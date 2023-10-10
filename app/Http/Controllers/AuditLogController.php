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

        if ($deletedRows > 0) {

            $checkifEmpty = DB::table('audit_logs')->select('*')->get();
            if(count($checkifEmpty) === 0){
                $result= array(
                    "message" => "deleted",
                );
                return $result;
            }else{
                $result= array(
                    "message" => "not_empty",
                );
                return $result;
            }

        } else {

            $checkifEmpty = DB::table('audit_logs')->select('*')->get();
            if(count($checkifEmpty) === 0){
                $result= array(
                    "message" => "deleted",
                );
                return $result;
            }else{
                $result= array(
                    "message" => "not_empty",
                );
                return $result;
            }
        }
    }

}
