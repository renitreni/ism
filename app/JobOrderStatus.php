<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderStatus extends Model
{
    use HasFactory;

    protected $fillable = ['job_order_id', 'status', 'status_date'];
}
