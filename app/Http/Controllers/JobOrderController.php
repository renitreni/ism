<?php

namespace App\Http\Controllers;

use App\JobOrder;
use App\JobOrderStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Enums\JobOrderStatusEnum;
use App\Enums\JobOrderProcessEnum;

class JobOrderController extends Controller
{
    public function index()
    {
        return view('job_order');
    }

    public function table()
    {
        return DataTables::of(JobOrder::all())->make(true);
    }

    public function create()
    {
        $jobNo = (new JobOrder)->newJONo();

        $customers = JobOrder::all()->pluck('customer_name');
        $processTypes = JobOrderProcessEnum::cases();
        $statuses = JobOrderStatusEnum::cases();

        return view('job_order_form', compact('jobNo', 'customers', 'processTypes', 'statuses'));
    }

    public function store(Request $request)
    {
        $jobOrder = JobOrder::create([
            "job_no" => "JO23-00001",
            "customer_name" => $request->get('customer_name'),
            "process_type" => $request->get('process_type'),
            "date_of_purchased" => $request->get('date_of_purchased'),
            "so_no" => $request->get('so_no'),
            "contact_person" => $request->get('contact_person'),
            "mobile_no" => $request->get('mobile_no'),
            "status" => $request->get('status'),
            "remarks" => $request->get('remarks'),
            "created_by" => $request->get('created_by')
        ]);

        JobOrderStatus::create([
            'job_order_id' => $jobOrder->id,
            'status' => $request->get('status'),
            'status_date' => $request->get('status_date'),
        ]);

        return ['success' => true];
    }
}
