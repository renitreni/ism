<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class AuditLog extends Model
{
    public static function record($request)
    {   

        if (!isset($request['method'])) {
            $request['method'] = '';
        }

        if($request['method'] == "UPDATED"){

            $module = substr($request['action_id'], 0, 2);

            $previous="";

            if($module == "PO"){
                $getPreviousLogs = DB::table('purchase_infos')->where('po_no', $request['action_id'])->get();

                if( $request['current'] == "Ordered" || $request['current'] == "Received" ){
                  $previous =  $getPreviousLogs[0]->status;

               }elseif( $request['current'] == "PAID" || $request['current'] == "UNPAID" || $request['current'] == "PAID WITH BALANCE" ){
                  $previous =  $getPreviousLogs[0]->payment_status;
               }

            }

            if($module == "SO"){
                $getPreviousLogs = DB::table('sales_orders')->where('so_no', $request['action_id'])->get();

                if($request['current'] == "Sales" || $request['current'] == "Quote" || $request['current'] == "Project"){
                    $previous =  $getPreviousLogs[0]->status;

                }elseif( $request['current'] == "Not Shipped" || $request['current'] == "Shipped" ){
                    $previous =  $getPreviousLogs[0]->delivery_status;
  
                }elseif( $request['current'] == "PAID" || $request['current'] == "UNPAID" || $request['current'] == "PAID WITH BALANCE" ){
                    $previous =  $getPreviousLogs[0]->payment_status;
                }

            }

            $log_sentence = $request['name'] . " - UPDATED - " . $request['action_id'] . " - Previous : " . $previous . " - Updated : " . $request['current'] ;

            $model = new self;
            $model->user = $request['name'];
            $model->inputs = json_encode($request['inputs']);
            $model->url = $request['url'];
            $model->action = $log_sentence;
            $model->action_id = $request['action_id'];
            $model->current = $request['current'];
            $model->previous = $previous;
            $model->save();
    
            return 'success';

        }

        if($request['method'] == "CREATED"){

            $log_sentence = $request['name'] . " - CREATED - " . $request['action_id'] . " - Current : " . $request['current'];

            $model = new self;
            $model->user = $request['name'];
            $model->inputs = json_encode($request['inputs']);
            $model->url = $request['url'];
            $model->action = $log_sentence;
            $model->action_id = $request['action_id'];
            $model->current = $request['current'];
            $model->previous = "";
            $model->save();
    
            return 'success';

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

            $model = new self;
            $model->user = $request['name'];
            $model->inputs = json_encode($request['inputs']);
            $model->url = $request['url'];
            $model->action = $log_sentence;
            $model->action_id = $request['action_id'];
            $model->current = $current;
            $model->previous = "";
            $model->save();
    
            return 'success';

        }

        $model = new self;
        $model->user = $request['name'];
        $model->inputs = json_encode($request['inputs']);
        $model->url = $request['url'];
        $model->save();

        return 'success';


    }
}
