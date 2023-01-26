<!DOCTYPE html>
<html>
<head>
    <link
        href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css"
        rel="stylesheet">
    <style>
        html * {
            font-size: 12px !important;
        }

        td, th {
            padding: 0 !important;
        }

        .header-content {
            font-weight: bold;
        }

        .bg-aliceblue {
            background-color: #c4ddf3;
        }

        .bg-category {
            background-color: #f3c927;
        }

        body {
            font-size: 12px !important;
        }

        [scope="col"] {
            text-align: center;
        }

        .iden {
            font-size: 15px !important;
            font-weight: 900 !important;
            padding-bottom: 6px !important;
            text-align: center;
        }

        .title {
            font-size: 25px !important;
            font-weight: 900 !important;
            text-align: center;
        }
    </style>
</head>
<body>
{{--STATEMENT TTILE--}}
<table style="width: 100%">
    <tbody>
    <tr>
        <td class="title">Delivery Receipt</td>
    </tr>
    <tr>
        <td class="iden">{{ str_replace('SO', 'DR', $sales_order->so_no) }}</td>
    </tr>
    </tbody>
</table>
{{--SALES ORDER--}}
<table style="width: 100%">
    <tbody>
    <tr>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>Subject:</td>
                    <td class="header-content">{{ $sales_order->subject }}</td>
                </tr>
                <tr>
                    <td>Customer Name:</td>
                    <td class="header-content">{{ $sales_order->customer_name }}</td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td class="header-content">{{ $sales_order->address }}</td>
                </tr>
                <tr>
                    <td>Mobile Number:</td>
                    <td class="header-content">{{ $sales_order->phone }}</td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td class="header-content">{{ \Carbon\Carbon::parse($sales_order->due_date)->format('F j, Y') }}</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td width="20%">
            <img src="{{ public_path('app/public/logo/logo.jpg') }}" width="150" height="150">
        </td>
    </tr>
    </tbody>
</table>
{{--PRODUCT DETAILS--}}
<table class="table table-bordered">
    <thead class="bg-aliceblue">
    <tr>
        <th scope="col">Description</th>
        <th scope="col">Product Model</th>
        <th scope="col">Serial No.</th>
        <th scope="col">Quantity</th>
        <th scope="col">Unit</th>
        <th scope="col">(Material)<br> Unit Cost</th>
        <th scope="col">(Material)<br> Total Cost</th>
        <th scope="col">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($product_details as $product)
        @if(isset($product['product_name']))
            <tr>
                <td>{{ $product['product_name'] }}</td>
                <td>{{ $product['code'] }}</td>
                <td>{{ $product['notes'] }}</td>
                <td>{{ $product['qty'] }}</td>
                <td>{{ $product['unit'] }}</td>
                <td>{{ number_format($product['selling_price'], 2) }}</td>
                <td>{{ number_format($product['qty'] * $product['selling_price'], 2) }}</td>
                <td>{{ number_format(($product['qty'] * $product['selling_price']) + $product['discount_item'], 2) }}</td>
            </tr>
        @else
            <tr class="bg-category">
                <td colspan="9"><strong>{{ $product['category'] }}</strong></td>
            </tr>
        @endif @endforeach
    <tr class="bg-aliceblue">
        <td colspan="6"></td>
        <td><strong>Sub-Total</strong></td>
        <td>&#8369; {{ number_format($summary->sub_total,2) }}</td>
    </tr>
    </tbody>
</table>
{{--SUMMARY--}}

<table style="width: 100%">
    <tbody>
    <tr>
        <td style="width: 50%;">
            <table>
                <tbody>
                <tr>
                    <td style="padding-bottom: 5px;"><strong>Terms and Conditions</strong></td>
                </tr>
                <tr>
                    <td>{!! nl2br(e($sales_order->tac)) !!}</td>
                </tr>
                <tr>
                    <td>
                        <table>
                            <tbody>
                            <tr>
                                <td style="padding-bottom: 5px;" colspan="2"><strong>Payment
                                        Details</strong></td>
                            </tr>
                            <tr>
                                <td>Payment Method:</td>
                                <td>{{ $sales_order->payment_method }}</td>
                            </tr>
                            <tr>
                                <td>Account Name:</td>
                                <td>{{ $sales_order->account_name }}</td>
                            </tr>
                            <tr>
                                <td>Account No:</td>
                                <td>{{ $sales_order->account_no }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
        <td>
            <table
                style="border: 1px solid black; padding-left: 5%; width: 100%">
                <tbody>
                <tr>
                    <td colspan="2" style="padding-bottom: 8px"><strong>SUMMARY:</strong></td>
                </tr>
                @foreach($sections as $section) @foreach($section as $key =>
							$value)
                    <tr>
                        <td>{{ $key }}</td>
                        <td style="padding-left: 10px !important;">{{ number_format($value,2) }}</td>
                    </tr>
                @endforeach @endforeach
                <tr>
                    <td align="right"><strong>SUB-TOTAL</strong></td>
                    <td style="padding-left: 10px !important;">
                        {{ number_format($summary->sub_total, 2) }}
                    </td>
                </tr>
                <tr>
                    <td align="right"><strong>SHIPPING</strong></td>
                    <td style="padding-left: 10px !important">{{ number_format($summary->shipping,2)}}</td>
                </tr>

                @if($sales_order->vat_type == 'VAT INC')
                    <tr>
                        <td align="right"><strong>SALES %</strong></td>
                        <td style="padding-left: 10px !important">{{ $summary->sales_tax }}</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>SALES TAX</strong></td>
                        <td style="padding-left: 10px !important">{{ $summary->sales_actual }}</td>
                    </tr>
                @endif
                <tr>
                    <td align="right"><strong>GRAND TOTAL</strong></td>
                    <td style="padding-left: 10px !important">
                        &#8369; {{ number_format($summary->grand_total, 2) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
{{-- CONFORME--}}
<table style="margin-top: 50px; width:100%">
    <tbody>
    <tr>
        <td>
            <table>
                <thead>
                <th><strong>Prepared By:</strong></th>
                <th><strong>Date:</strong></th>
                </thead>
                <tbody>
                <tr>
                    <td style="padding-right: 20px !important;">{{ $sales_order->name }} </td>
                    <td>___________________________</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td>
            <table>
                <thead>
                <th><strong>Received By:</strong></th>
                <th><strong>Date:</strong></th>
                </thead>
                <tbody>
                <tr>
                    <td style="padding-right: 20px !important;">___________________</td>
                    <td>___________________</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
