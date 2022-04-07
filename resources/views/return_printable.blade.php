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
        <td class="title">Product Return</td>
    </tr>
    <tr>
        <td class="iden">{{ $sales_order->so_no }}</td>
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
                    <td>Project:</td>
                    <td class="header-content">{{ $sales_order->customer_name }}</td>
                </tr>
                <tr>
                    <td>SO NO:</td>
                    <td class="header-content">{{ $sales_order->so_no }}</td>
                </tr>
                <tr>
                    <td>Date:</td>
                    <td class="header-content">{{ \Carbon\Carbon::parse($sales_order->created_at)->format('F j, Y') }}</td>
                </tr>
                </tbody>
            </table>
        </td>
        <td width="150"><img src="{{ public_path('app/public/logo/logo.jpg') }}"
                             width="150" height="150"></td>
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
        <th scope="col">(Material)<br> Unit Cost
        </th>
        <th scope="col">(Material)<br> Total Cost
        </th>
        <th scope="col">Total</th>
    </tr>
    </thead>
    <tbody>
    <?php $total=0; ?>
    @foreach($product_details as $product)
        @if(isset($product['product_name']))
            <tr>
                <td>{{ $product['product_name'] }}</td>
                <td>{{ $product['code'] }}</td>
                <td>{{ $product['notes'] }}</td>
                <td>{{ $product['qty'] }}</td>
                <td>{{ $product['unit'] }}</td>
                <td>{{ number_format($product['selling_price'], 2, '.', '') }}</td>
                <td>{{ number_format($product['qty'] * $product['selling_price'], 2,
					'.', '') }}</td>
                <td>{{ number_format(($product['qty'] * $product['labor_cost']) +
					($product['qty'] * $product['selling_price']), 2, '.', '') }}</td>
                {{ $total += number_format(($product['qty'] * $product['labor_cost']) + ($product['qty'] * $product['selling_price']), 2, '.', '') }}
            </tr>
        @else
            <tr class="bg-category">
                <td colspan="9"><strong>{{ $product['category'] }}</strong></td>
            </tr>
        @endif @endforeach
    <tr class="bg-aliceblue">
        <td colspan="6"></td>
        <td><strong>Sub-Total</strong></td>
        <td>{{ $total }}</td>
    </tr>
    </tbody>
</table>
{{-- CONFORME--}}
<table style="margin-top: 50px; width:100%">
    <tbody>
    <tr>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<strong>Prepared By:</strong>
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;<strong>Date:</strong>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;___________________</td>
                    <td pad>&nbsp;&nbsp;&nbsp;___________________</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<i>Owner's Signature</i>
                    </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </td>
        <td>
            <table>
                <tbody>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<strong>Received By:</strong>
                    </td>
                    <td>&nbsp;&nbsp;&nbsp;<strong>Date:</strong>
                    </td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;___________________</td>
                    <td pad>&nbsp;&nbsp;&nbsp;___________________</td>
                </tr>
                <tr>
                    <td>&nbsp;&nbsp;&nbsp;<i>Signature Over Printed Name</i>
                    </td>
                    <td></td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
</body>
</html>
