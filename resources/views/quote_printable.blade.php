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

        .footer {
            margin-top: 50px;
            width: 100%;
            background-color: white;
            border-top: 1px solid black;
        }

        .TC {
            background-color: white;
            padding: 10px;
            margin-top: 50px;
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
        <td class="title">Quotation</td>
    </tr>
    <tr>
        <td class="iden">{{ str_replace('SO', 'QTN', $sales_order->so_no) }}</td>
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
            {{-- <img src="{{ public_path('app/public/print/header/1_ITR_CORPORATION_LOGO.jpg') }}" height="150"> --}}
            <img src="{{ public_path('app/public/print/header/'.$print_setting->header_logo) }}" height="100">
        </td>
    </tr>
    </tbody>
</table>
{{--PRODUCT DETAILS--}}
<table class="table table-bordered">
    <thead class="bg-aliceblue">
    <tr>
        <th scope="col">Description</th>
        {{-- <th scope="col">Product Model</th> --}}
        <th scope="col" colspan="2">Serial No.</th>
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
                {{-- <td>{{ $product['code'] }}</td> --}}
                <td colspan="2">{{ $product['notes'] }}</td>
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
        @if ($summary->shipping)
            <tr class="bg-aliceblue">
                <td colspan="6"></td>
                <td><strong>Shipping</strong></td>
                <td style="text-align: right">&#8369; {{ number_format($summary->shipping, 2) }}</td>
            </tr>
        @endif
        @if ($summary->discount)
            <tr class="bg-aliceblue">
                <td colspan="6"></td>
                <td><strong style="color:red">Discount</strong></td>
                <td style="text-align: right; color:red">&#8369; - {{ number_format($summary->discount, 2) }}</td>
            </tr>
        @endif
        {{-- <tr class="bg-aliceblue">
            <td colspan="6"></td>
            <td><strong>Sales %</strong></td>
            <td style="text-align: right"> {{ $summary->sales_tax }} %</td>
        </tr> --}}
        @if ($summary->sales_actual)
            <tr class="bg-aliceblue">
                <td colspan="6"></td>
                <td><strong>Sales TAX</strong></td>
                <td style="text-align: right">&#8369; {{ number_format($summary->sales_actual, 2) }}</td>
            </tr>
        @endif
        <tr class="bg-aliceblue">
            <td colspan="6"></td>
            <td><strong>Grand Total</strong></td>
            <td style="text-align: right">&#8369; {{ number_format($summary->grand_total, 2) }}</td>
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
        {{-- <td>
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
                            {{ number_format($summary->sub_total,2) }}
                        </td>
                    </tr>
                    @if ($summary->shipping)
                    <tr class="bg-aliceblue">
                        <td colspan="6"></td>
                        <td><strong>Shipping</strong></td>
                        <td style="text-align: right">&#8369; {{ number_format($summary->shipping, 2) }}</td>
                    </tr>
                @endif
                @if ($summary->discount)
                    <tr class="bg-aliceblue">
                        <td colspan="6"></td>
                        <td><strong style="color:red">Discount</strong></td>
                        <td style="text-align: right; color:red">&#8369; - {{ number_format($summary->discount, 2) }}</td>
                    </tr>
                @endif

                @if ($summary->sales_actual)
                    <tr class="bg-aliceblue">
                        <td colspan="6"></td>
                        <td><strong>Sales TAX</strong></td>
                        <td style="text-align: right">&#8369; {{ number_format($summary->sales_actual, 2) }}</td>
                    </tr>
                @endif
                <tr class="bg-aliceblue">
                    <td colspan="6"></td>
                    <td><strong>Grand Total</strong></td>
                    <td style="text-align: right">&#8369; {{ number_format($summary->grand_total, 2) }}</td>
                </tr>
                </tbody>
            </table>
        </td> --}}
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
<div class="TC">
    <table style="width: 100%; text-align: center;">
        <table>

            <tbody>
                <tr>
                    <td style="padding-bottom: 5px;"><strong>Warranty Policy</strong></td>
                </tr>
                <tr>
                    <td>{!! nl2br(e($sales_order->warranty)) !!}</td>
                </tr>
            </tbody>
    </table>
</div>
<div class="footer">
    <table style="width: 100%; text-align: center;">
        <table>
            <thead style="text-align: center">
                <th colspan="3"  style="text-align: center; font-size: 10px !important;"><strong style="font-size: 10px !important;" >Address</strong></th>
                <th colspan="" rowspan="2" style="text-align: center; font-size: 10px !important;"><strong style="font-size: 10px !important;"> For warranty and technical concerns please call our RMA team</strong></th>
                <th colspan="3" style="text-align: center; font-size: 10px !important;"><strong style="font-size: 10px !important;"> For Sales inquiries, Please call our Sales Team</strong></th>
            </thead>
            <tbody>
                <tr>
                    <td colspan="3" style="text-align: center;padding-right: 11px !important; font-size: 8px !important;">
                        {{$print_setting->address}}
                    </td>
                    <td colspan="3" style="text-align: center;padding-right: 11px !important; font-size: 8px !important;">
                        {{$print_setting->rma_team}}
                    </td>
                    <td  style="text-align: center; padding-left: 16px !important; font-size: 8px !important;">
                        *{{$print_setting->sales1}}
                        *{{$print_setting->sales2}}
                        *{{$print_setting->email}}
                    </td>
                </tr>

            </tbody>
    </table>
</div>

</body>
</html>
