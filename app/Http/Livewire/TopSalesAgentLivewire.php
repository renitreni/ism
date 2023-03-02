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
        $this->tops = DB::select("SELECT COUNT(*) AS sales, assigned_to, u.name
            FROM sales_orders
            LEFT JOIN users AS u ON u.id = assigned_to
            WHERE MONTH(sales_orders.created_at) = {$this->month}
            AND YEAR(sales_orders.created_at) = {$this->year}
            GROUP BY assigned_to, u.name
            ORDER BY 1 desc");

        return view('livewire.top-sales-agent-livewire');
    }
}