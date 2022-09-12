<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected  $fillable =[
        'cost_center',
        'description',
        'person_assigned',
        'total_amount',
        'expense_date',
        'si_no',
        'dr_no',
        'remarks',
        'created_by'
    ];
}
