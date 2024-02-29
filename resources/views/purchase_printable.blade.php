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
        .footer {
            background-color: #f3c927;
            padding: 10px;
            bottom: 0;
            width: 100%;
            margin-top: 500px;
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


<body>
{{--STATEMENT TTILE--}}
<table style="width: 100%">
    <tbody>
    <tr>
        <td class="title">Purchase Order</td>
    </tr>
    <tr>
        <td class="iden">{{ $purchase_info->po_no }}</td>
    </tr>
    </tbody>
</table>
{{--PURCHASE ORDER--}}
<table style="width: 100%">
    <tbody>
    <tr>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>Subject:</td>
                    <td class="header-content">{{ $purchase_info->subject }}</td>
                </tr>
                <tr>
                    <td>Vendor Name:</td>
                    <td class="header-content">{{ $purchase_info->vendor_name }}</td>
                </tr>
                <tr>
                    <td>Address:</td>
                    <td class="header-content">{{ $purchase_info->vendor_address }}</td>
                </tr>
                <tr>
                    <td>Mobile Number:</td>
                    <td class="header-content">{{ $purchase_info->vendor_mobile_phone }}</td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td class="header-content">{{ \Carbon\Carbon::now()->format('F
									j, Y') }}</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td width="20%">
            <img src="{{ asset(''.$print_setting->header_logo_path . $print_setting->header_logo) }}" style="width: auto; height: 86px;">

        </td>
    </tr>
    </tbody>
</table>
{{--PRODUCT DETAILS--}}
<table class="table table-bordered">
    <thead class="bg-aliceblue">
    <tr>
        <th scope="col">Description</th>
        <th scope="col">Quantity</th>
        <th scope="col">Unit</th>
        <th scope="col">(Material)<br> Unit Cost
        </th>
        <th scope="col">(Material)<br> Total Cost
        </th>
        <th scope="col">Total</th>
    </tr>
    </thead>
    <tbody>
    @foreach($product_details as $product)
        @if(isset($product['product_name']))
            <tr>
                <td>{{ $product['product_name'] }}</td>
                <td>{{ $product['qty'] }}</td>
                <td>{{ $product['unit'] }}</td>
                <td>{{ number_format($product['vendor_price'], 2) }}</td>
                <td>{{ number_format($product['qty'] * $product['vendor_price'], 2) }}</td>
                <td>{{ number_format($product['qty'] * $product['vendor_price'], 2) }}</td>
            </tr>
        @else
            <tr class="bg-category">
                <td colspan="8"><strong>{{ $product['category'] }}</strong></td>
            </tr>
        @endif @endforeach
    <tr class="bg-aliceblue">
        <td colspan="2"></td>
        <td colspan="2" style="text-align: right"><strong>Sub-Total</strong></td>
        <td colspan="2" style="text-align: right">&#8369; {{ number_format($summary->sub_total, 2) }}</td>
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
                    <td>{!! nl2br(e($purchase_info->tac)) !!}</td>
                </tr>
                <tr>
                    <td style="padding-bottom: 5px;"><strong>Company Details</strong></td>
                </tr>
                <tr>
                    <td style="height:100%">{!! nl2br(e(\App\Preference::status('company_details_fill'))) !!}</td>
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
                    <td align="right" style="padding-top: 10px !important;"><strong>DISCOUNT</strong></td>
                    <td
                        style="padding-left: 10px !important; padding-top: 10px !important;">{{ number_format($summary->discount,2) }}</td>
                </tr>
                <tr>
                    <td align="right"><strong>SUB-TOTAL</strong></td>
                    <td style="padding-left: 10px !important;">{{ number_format($summary->sub_total,2) }}</td>
                </tr>
                <tr>
                    <td align="right"><strong>SHIPPING</strong></td>
                    <td style="padding-left: 10px !important">{{ number_format($summary->shipping,2) }}</td>
                </tr>
                @if($purchase_info->vat_type == 'VAT INC')
                    <tr>
                        <td align="right"><strong>SALES %</strong></td>
                        <td style="padding-left: 10px !important">{{ $summary->sales_tax }}</td>
                    </tr>
                    <tr>
                        <td align="right"><strong>SALES TAX</strong></td>
                        <td style="padding-left: 10px !important">{{ number_format($summary->sales_actual,2 ) }}</td>
                    </tr>
                @endif
                <tr>
                    <td align="right"><strong>GRAND TOTAL</strong></td>
                    <td style="padding-left: 10px !important">
                        &#8369; {{ number_format($summary->grand_total,2) }}
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

{{--ADDRESSES--}}
</br></br>
<table style="width: 100%;">
    <tbody>
    <tr>
        <td style="width: 50%;">
            <table>
                <tbody>
                <tr>
                    <td style="padding-bottom: 5px;"><strong>Billing Address</strong></td>
                </tr>
                <tr>
                    <td>{!! nl2br(e($purchase_info->billing_address)) !!}</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td style="width: 50%;">
            <table>
                <tbody>
                <tr>
                    <td style="padding-bottom: 5px;"><strong>Delivery Address</strong></td>
                </tr>
                <tr>
                    <td>{!! nl2br(e($purchase_info->delivery_address)) !!}</td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
{{-- CONFORME--}}
<table style="margin-top: 50px;">
    <thead>
    <th width="100">
        <strong>Received By:</strong>
    </th>
    <th>
        <strong>Date:</strong>
    </th>
    </thead>
    <tbody>
    <tr>
        <td>{{ $purchase_info->name }}</td>
        <td width="200"> ______________________</td>
    </tr>
    <tr>
        <td>&nbsp;</td>
        <td></td>
    </tr>
    </tbody>
</table>

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
