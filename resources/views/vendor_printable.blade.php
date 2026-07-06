<style>
    @page {
        size: A4 landscape;
        margin: 0.5cm;
    }
    body {
        margin: 0;
        padding: 5px;
    }
    thead {
        display: table-header-group;
    }
    tfoot {
        display: table-row-group;
    }
    tr {
        page-break-inside: avoid;
    }
    table {
        width: 100%;
        border-collapse: collapse;
        margin-top: 10px;
        font-size: 11px;
        table-layout: fixed;
    }
    td, th {
        padding: 4px;
        border: 1px solid #000;
        word-wrap: break-word;
        overflow-wrap: break-word;
        word-break: break-word;
        font-size: 10px;
    }
    th {
        background-color: #f2f2f2;
        font-weight: bold;
        text-align: left;
    }
    td {
        vertical-align: top;
    }
    th:nth-child(7),
    td:nth-child(7) {
        width: 25%;
        max-width: 25%;
    }
    h2, h3 {
        margin: 5px 0;
        padding: 0;
    }
</style>

<h2>{{ \Carbon\Carbon::now()->format('F j, Y') }}</h2>

<h3>Vendor List</h3>
<table border="1">
    <thead>
    <th>Customer ID</th>
    <th>Name</th>
    <th>Contact Person</th>
    <th>Landline</th>
    <th>Mobile Phone</th>
    <th>E-mail</th>
    <th>Address</th>
    </thead>
    <tbody>
    @foreach ($vendors as $item)
        <tr>
            <td>{{ $item->id }}</td>
            <td>{{ $item->name }}</td>
            <td>{{ $item->contact_person }}</td>
            <td>{{ $item->landline }}</td>
            <td>{{ $item->mobile_phone }}</td>
            <td>{{ $item->email }}</td>
            <td>{{ $item->address }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
