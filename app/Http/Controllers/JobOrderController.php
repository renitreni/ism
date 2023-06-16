<?php

namespace App\Http\Controllers;

use App\JobOrder;
use App\JobOrderStatus;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use App\Enums\JobOrderStatusEnum;
use App\Enums\JobOrderProcessEnum;
use App\Http\Requests\JobOrderStoreRequest;
use App\JobOrderProduct;

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

    public function store(JobOrderStoreRequest $request)
    {
        $jobOrder = JobOrder::create([
            "job_no" => $request->get('job_no'),
            "customer_name" => $request->get('customer_name'),
            "process_type" => $request->get('process_type'),
            "date_of_purchased" => $request->get('date_of_purchased'),
            "so_no" => $request->get('so_no'),
            "contact_person" => $request->get('contact_person'),
            "mobile_no" => $request->get('mobile_no'),
            "status" => $request->get('status'),
            "remarks" => $request->get('remarks'),
            'status' => $request->get('status'),
            'agent' => $request->get('agent'),
            "created_by" => $request->get('created_by')
        ]);

        JobOrderStatus::create([
            'job_order_id' => $jobOrder->id,
            'status' => $request->get('status'),
            'status_date' => $request->get('status_date'),
        ]);

        foreach ($request->get('products') as $item) {
            JobOrderProduct::create([
                'job_order_id' => $jobOrder->id,
                "product" => $item['product'],
                "qty" => $item['qty'],
                "serial_number" => $item['serial_number'],
                "physical_appearance" => $item['physical_appearance'],
                "product_status" => $item['product_status'],
            ]);
        }

        return ['success' => true];
    }

    public function edit(JobOrder $jobOrder)
    {
        $overview = $jobOrder->toArray();
        $overview['products'] = $jobOrder->jobOrderProducts->toArray();
        $overview['status_date'] = $jobOrder->jobOrderStatus()
            ->where('status', $jobOrder->status)
            ->orderBy('id', 'desc')
            ->first()
            ->status_date;

        $customers = JobOrder::all()->pluck('customer_name');
        $processTypes = JobOrderProcessEnum::cases();
        $statuses = JobOrderStatusEnum::cases();

        return view('job_order_form', compact('customers', 'processTypes', 'statuses', 'overview'));
    }

    public function update(JobOrderStoreRequest $request)
    {
        $jobOrder = JobOrder::query()->updateOrCreate(
            ["job_no" => $request->get('job_no')],
            [
                "job_no" => $request->get('job_no'),
                "customer_name" => $request->get('customer_name'),
                "process_type" => $request->get('process_type'),
                "date_of_purchased" => $request->get('date_of_purchased'),
                "so_no" => $request->get('so_no'),
                "contact_person" => $request->get('contact_person'),
                "mobile_no" => $request->get('mobile_no'),
                "status" => $request->get('status'),
                "remarks" => $request->get('remarks'),
                'status' => $request->get('status'),
                'agent' => $request->get('agent'),
                "created_by" => $request->get('created_by')
            ]
        );

        JobOrderStatus::updateOrCreate(
            ['job_order_id' => $jobOrder->id, 'status' => $request->get('status')],
            [
                'job_order_id' => $jobOrder->id,
                'status' => $request->get('status'),
                'status_date' => $request->get('status_date'),
            ]
        );

        JobOrderProduct::query()->where('job_order_id', $jobOrder->id)->delete();
        foreach ($request->get('products') as $item) {
            JobOrderProduct::create([
                'job_order_id' => $jobOrder->id,
                "product" => $item['product'],
                "qty" => $item['qty'],
                "serial_number" => $item['serial_number'],
                "physical_appearance" => $item['physical_appearance'],
                "product_status" => $item['product_status'],
            ]);
        }

        return ['success' => true];
    }

    public function destroy(Request $request)
    {
        $jobOrder = JobOrder::query()->find($request->id);
        $jobOrder->jobOrderStatus()->delete();
        $jobOrder->jobOrderProducts()->delete();
        $jobOrder->delete();

        return ['success' => true];
    }
}
