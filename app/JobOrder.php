<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class JobOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'job_no',
        'customer_name',
        'process_type',
        'date_of_purchased',
        'so_no',
        'contact_person',
        'mobile_no',
        'status',
        'remarks',
    ];


    public function newJONo()
    {
        // if (Preference::verify('po_auto') == 0) {
        //     return '';
        // }
        
        $po_no_list = $this->newQuery()
            ->where('job_no', 'like', '%PO%')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->get()
            ->toArray();
        $str_length = 5;
        $year       = Carbon::now()->format('y');
        if (isset($po_no_list[0]["po_no"])) {
            $po_no = $po_no_list[0]["po_no"];
        }
        if (count($po_no_list) == 0 || substr(explode('-', $po_no)[0], -2) != $year) {
            $num = 1;
            $str = substr("0000{$num}", -$str_length);

            return 'JO' . $year . '-' . $str;
        } else {
            $numbering = explode('-', $po_no)[1];
            $year      = Carbon::now()->format('y');
            $final_num = (int) $numbering + 1;
            $str       = substr("0000{$final_num}", -$str_length);

            return 'JO' . $year . '-' . $str;
        }
    }

    public function jobOrderStatus(): HasMany
    {
        return $this->hasMany(JobOrderStatus::class);
    }
}
