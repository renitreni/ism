<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobOrderProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_order_id',
        'product',
        'qty',
        'serial_number',
        'physical_appearance',
        'product_status'
    ];
}
