<!DOCTYPE html>
<html>

<head>
    @include('bootstrap_include')
</head>

<body>


    <body>
        {{-- STATEMENT TTILE --}}
        <div class="d-flex justify-content-center">
            <div>
                <h1>Job Order</h1>
                <div class="text-center">{{ $jobOrder->job_no }}</div>
            </div>
        </div>
        {{-- PURCHASE ORDER --}}
        <table style="width: 100%" class="ms-5">
            <tbody>
                <tr>
                    <td>
                        <table>
                            <tbody>
                                <tr>
                                    <td>Customer Name:</td>
                                    <td class="header-content">{{ $jobOrder->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td>Process Type:</td>
                                    <td class="header-content">{{ $jobOrder->process_type }}</td>
                                </tr>
                                <tr>
                                    <td>Date Of Purchased:</td>
                                    <td class="header-content">{{ $jobOrder->date_of_purchased }}</td>
                                </tr>
                                <tr>
                                    <td>Contact Person:</td>
                                    <td class="header-content">{{ $jobOrder->contact_person }}</td>
                                </tr>
                                <tr>
                                    <td>Mobile Number:</td>
                                    <td class="header-content">{{ $jobOrder->mobile_no }}</td>
                                </tr>
                                <tr>
                                    <td>Agent:</td>
                                    <td class="header-content">{{ $jobOrder->agent }}</td>
                                </tr>
                                <tr>
                                    <td>SO No.:</td>
                                    <td class="header-content">{{ $jobOrder->so_no }}</td>
                                </tr>
                                <tr>
                                    <td>Remarks:</td>
                                    <td class="header-content">{{ $jobOrder->remarks }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                    <td width="20%">
                        <img src="{{ asset('app/public/logo/logo.jpg') }}" width="150" height="150">
                    </td>
                </tr>
            </tbody>
        </table>
        {{-- PRODUCT DETAILS --}}
        <table class="table table-bordered mt-3">
            <thead class="bg-aliceblue">
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Erial Number</th>
                    <th scope="col">Physical Appearance</th>
                    <th scope="col">Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($products as $product)
                    <tr>
                        <td>{{ $product->product }}</td>
                        <td>{{ $product->qty }}</td>
                        <td>{{ $product->serial_number }}</td>
                        <td>{{ $product->physical_appearance }}</td>
                        <td>{{ $product->product_status }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </body>

    {{-- CONFORME --}}
    <div class="d-flex justify-content-between mt-5">
        <div class="ms-5">
            Agent: <span class="fw-bold">{{ $jobOrder->agent }}</span>
        </div>
        <div class="me-5">
            Date: <span class="fw-bold">{{ \Carbon\Carbon::now()->format('F j, Y') }}</span>
        </div>
    </div>

</html>
