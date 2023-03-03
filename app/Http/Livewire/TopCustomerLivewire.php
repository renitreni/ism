<?php

namespace App\Http\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\DB;

class TopCustomerLivewire extends Component
{
    public $tops;

    public $month;

    public $year;

    public function mount()
    {
        $this->month = now()->format('m');
        $this->year = now()->format('Y');
    }
    public function render()
    {
        $this->tops = DB::select("SELECT count(*) AS total, sales_orders.customer_id, c.name
        FROM sales_orders
        LEFT JOIN customers AS c ON c.id = sales_orders.customer_id
        WHERE MONTH(sales_orders.created_at) = {$this->month}
        AND YEAR(sales_orders.created_at) = {$this->year}
        AND c.id NOT IN (2)
        GROUP BY sales_orders.customer_id, c.name
        ORDER BY 1 desc
        LIMIT 10");

        return view('livewire.top-customer-livewire');
    }
}
