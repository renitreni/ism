<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TopSalesAgentLivewire extends Component
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
        // $this->tops = DB::select("SELECT SUM(s.sub_total) AS sales, agent as name
        $this->tops = DB::select("SELECT SUM(s.grand_total) AS sales, agent as name
            FROM sales_orders
            INNER JOIN summaries AS s ON s.sales_order_id = sales_orders.id
                WHERE YEAR(sales_orders.due_date) = {$this->year}".
                ($this->month ? " AND MONTH(sales_orders.created_at) = {$this->month} " : ' ')
                ."AND sales_orders.status IN ('Sales', 'Project')
                GROUP BY agent
                ORDER BY 1 desc
            LIMIT 10");

        return view('livewire.top-sales-agent-livewire');
    }
}
