<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Expenses extends Model
{
    protected $fillable = [
        'expenses_no',
        'cost_center',
        'description',
        'person_assigned',
        'total_amount',
        'expense_date',
        'si_no',
        'dr_no',
        'remarks',
        'created_by',
    ];

    public function newExpenseNo()
    {
        $so_no_list = $this->newQuery()
                           ->where('expenses_no', 'like', '%EXP%')
                           ->orderBy('id', 'desc')
                           ->limit(1)
                           ->get()
                           ->toArray();
        $str_length = 5;
        $year       = now()->format('y');

        if (isset($so_no_list[0]["expenses_no"])) {
            $so_no = $so_no_list[0]["expenses_no"];
        }

        if (count($so_no_list) == 0 || substr(explode('-', $so_no)[0], -2) != $year) {
            $num = 1;
            $str = substr("0000{$num}", -$str_length);

            return 'EXP'.$year.'-'.$str;
        } else {
            $numbering = explode('-', $so_no)[1];
            $year      = now()->format('y');
            $final_num = (int) $numbering + 1;
            $str       = substr("0000{$final_num}", -$str_length);

            return 'EXP'.$year.'-'.$str;
        }
    }

    public function total($start, $end)
    {
        return $this->when($start && $end, function ($q) use ($start, $end) {
            $q->whereBetween('expense_date', [$start, $end]);
        });
    }
}
