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
        $this->tops = DB::select("SELECT SUM(s.sub_total) AS total, sales_orders.customer_id, c.name
        FROM sales_orders
        INNER JOIN summaries AS s ON s.sales_order_id = sales_orders.id
        LEFT JOIN customers AS c ON c.id = sales_orders.customer_id 
        WHERE YEAR(sales_orders.due_date) = {$this->year}".
        ($this->month ? " AND MONTH(sales_orders.created_at) = {$this->month} " : ' ')
        ."AND sales_orders.status IN ('Sales', 'Project')
        AND c.id NOT IN (2)
        GROUP BY sales_orders.customer_id, c.name
        ORDER BY 1 desc
        LIMIT 10");

        return view('livewire.top-customer-livewire');
    }
}
