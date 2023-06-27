<!DOCTYPE html>
<html>

<head>
    @include('bootstrap_include')
</head>

<body>
    <div class="container">
        {{-- STATEMENT TTILE --}}
        <div class="d-flex justify-content-center">
            <div>
                <h1>Job Order</h1>
                <div class="text-center fs-3">{{ $jobOrder->job_no }}</div>
            </div>
        </div>
        {{-- PURCHASE ORDER --}}
        <div class="d-flex justify-content-between mx-2">
            <div class="d-flex flex-column">
                <div>
                    <label>Customer Name:</label>
                    <label class="fw-bold">{{ $jobOrder->customer_name }}</label>
                </div>
                <div>
                    <label>Process Type:</label>
                    <label class="fw-bold">{{ $jobOrder->process_type }}</label>
                </div>
                <div>
                    <label>Date Of Purchased:</label>
                    <label class="fw-bold">{{ $jobOrder->date_of_purchased }}</label>
                </div>
                <div>
                    <label>Contact Person:</label>
                    <label class="fw-bold">{{ $jobOrder->contact_person }}</label>
                </div>
                <div>
                    <label>Mobile Number:</label>
                    <label class="fw-bold">{{ $jobOrder->mobile_no }}</label>
                </div>
                <div>
                    <label>Agent:</label>
                    <label class="fw-bold">{{ $jobOrder->agent }}</label>
                </div>
                <div>
                    <label>SO No.:</label>
                    <label class="fw-bold">{{ $jobOrder->so_no }}</label>
                </div>
                <div>
                    <label>Remarks:</label>
                    <label class="fw-bold">{{ $jobOrder->remarks }}</label>
                </div>
            </div>
            <div>
                <img src="{{ asset('app/public/logo/logo.jpg') }}" width="150" height="150">
            </div>
        </div>
        {{-- PRODUCT DETAILS --}}
        <table class="table table-bordered mt-3">
            <thead class="bg-aliceblue">
                <tr>
                    <th scope="col">Product</th>
                    <th scope="col">Quantity</th>
                    <th scope="col">Serial Number</th>
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

        {{-- CONFORME --}}
        <div class="d-flex justify-content-between mt-5">
            <div class="ms-5 d-flex flex-column">
                <div>Agent: <span class="fw-bold">{{ $jobOrder->agent }}</span></div>
                <div>Date: <span class="fw-bold">{{ \Carbon\Carbon::now()->format('F j, Y') }}</span></div>
            </div>
            <div class="me-5 d-flex flex-column">
                <div>_____________________________</div>
                <div class="text-center">Customer Signature</div>
            </div>
        </div>
    </div>
    <style>
        @media print {
            #printPageButton {
                display: none;
            }
        }
    </style>
    <div class="d-flex justify-content-center mt-5">
        <button id="printPageButton" class="btn btn-primary" onClick="window.print();">Print</button>
    </div>
</body>

</html>
