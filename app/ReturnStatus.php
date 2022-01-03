<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ReturnStatus extends Model
{
    public function store($id, $request)
    {
        $model = new self();
        $model->product_return_id = $id;
        $model->status = $request['status'];
        $model->assign_to = auth()->id();
        $model->save();
    }
}
