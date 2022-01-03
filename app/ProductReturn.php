<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class ProductReturn extends Model
{
    public static function generate()
    {
        return new static;
    }

    public function newPRNo()
    {
        if (Preference::verify('po_auto') == 0) {
            return '';
        }

        $pr_no_list = $this->newQuery()
            ->where('pr_no', 'like', '%PR%')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->get()
            ->toArray();
        $str_length = 5;
        $year = Carbon::now()->format('y');

        if (isset($pr_no_list[0]["pr_no"])) {
            $pr_no = $pr_no_list[0]["pr_no"];
        }

        if (count($pr_no_list) == 0 || substr(explode('-', $pr_no)[0], -2) != $year) {
            $num = 1;
            $str = substr("0000{$num}", -$str_length);

            return 'PR' . $year . '-' . $str;
        } else {
            $numbering = explode('-', $pr_no)[1];
            $year = Carbon::now()->format('y');
            $final_num = (int)$numbering + 1;
            $str = substr("0000{$final_num}", -$str_length);

            return 'PR' . $year . '-' . $str;
        }

    }

    public function store($request)
    {
        $model = new self();
        $model->customer_id = $request['customer_id'];
        $model->sales_order_id = $request['sales_order_id'];
        $model->pr_no = $request['pr_no'];
        $model->return_type  = $request['return_type'];
        $model->contact_person = $request['contact_person'];
        $model->reason = $request['reason'];
        $model->remarks = $request['remarks'];
        $model->assigned_to = auth()->id();
        $model->save();

        return $model->id;
    }
}
