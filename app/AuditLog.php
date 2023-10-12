<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuditLog extends Model
{
    public static function record($request)
    {   
        $previous = "";
        $log_sentence = "";
        
        if (!isset($request['method'])) {
            $request['method'] = '';
        }

        if($request['method'] == "UPDATED"){
            $module = substr($request['action_id'], 0, 2);

            if($module == "PO"){
                $getPreviousLogs = DB::table('purchase_infos')->where('po_no', $request['action_id'])->first();
                if(in_array($request['current'], ["Ordered", "Received"])){
                  $previous =  $getPreviousLogs->status;
               }elseif(in_array($request['current'], ["PAID", "UNPAID", "PAID WITH BALANCE"])){
                  $previous =  $getPreviousLogs->payment_status;
               }
            }

            if($module == "SO"){
                $getPreviousLogs = DB::table('sales_orders')->where('so_no', $request['action_id'])->first();
                if(in_array($request['current'], ["Sales", "Quote", "Project"])){
                    $previous =  $getPreviousLogs->status;
                }elseif(in_array($request['current'], ["Not Shipped", "Shipped"])){
                    $previous =  $getPreviousLogs->delivery_status;
                }elseif(in_array($request['current'], ["PAID", "UNPAID", "PAID WITH BALANCE"])){
                    $previous =  $getPreviousLogs->payment_status;
                }
            }

            $log_sentence = $request['name'] . " - UPDATED - " . $request['action_id'] . " - Previous : " . $previous . " - Updated : " . $request['current'] ;
        }

        if($request['method'] == "CREATED"){
            $log_sentence = $request['name'] . " - CREATED - " . $request['action_id'] . " - Current : " . $request['current'];
        }

        if($request['method'] == "DELETED"){
            $module = substr($request['action_id'], 0, 2);
            $current = "";

            if($module == "PO"){
                $getPreviousLogs = DB::table('purchase_infos')->where('po_no', $request['action_id'])->get();
                $current = $getPreviousLogs[0]->status;
            }
            if($module == "SO"){
                $getPreviousLogs = DB::table('sales_orders')->where('so_no', $request['action_id'])->get();
                $current = $getPreviousLogs[0]->status;
            }
            
            $log_sentence = $request['name'] . " - DELETED - " . $request['action_id'] . " - Current : " . $current;
        }

        $model = new self;
        $model->user = $request['name'];
        $model->inputs = json_encode($request['inputs']);
        $model->url = $request['url'];
        $model->action = $log_sentence ?? null;
        $model->action_id = $request['action_id'] ?? null;
        $model->current = $request['current'] ?? null;
        $model->previous = $previous;
        $model->save();

        return 'success';
    }
}
