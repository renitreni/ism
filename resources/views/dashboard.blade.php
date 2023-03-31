@extends('admin_layout')

@section('styles')
    <style>
        input,
        select,
        textarea {
            color: #ffffff !important;
            background-color: #ffffff00 !important;
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <!-- Total Expenses -->
            <livewire:component.total-expenses-component>

                <!-- Total Po -->
                <div class="col-6 col-md-4 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                        <div class="card-body" style="padding-bottom: .1rem;">
                            <div class="row no-gutters align-items-center">
                                <div class="col">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <a target="_blank"
                                            v-bind:href="'/home/po/printable/' + po_range.start + '/' + po_range.end"
                                            class="btn btn-sm btn-info">
                                            <i class="fas fa-file-download"></i>
                                        </a>
                                        Total PO
                                    </div>
                                    <div class="row no-gutters align-items-center">
                                        <div class="col-auto">
                                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                @{{ numberWithCommas(po_totals) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col ml-3 mr-3">
                                <input type="text" id="po_totals" class="form-control" name="daterange" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total So -->
                <div class="col-6 col-md-4 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                        <div class="card-body" style="padding-bottom: .1rem;">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                        <a href="#" class="btn btn-sm btn-warning" data-toggle="modal"
                                            data-target="#downloadTypeMdl">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        Total SO
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                        @{{ numberWithCommas(so_totals) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col ml-3 mr-3">
                                <input type="text" id="so_totals" class="form-control" name="daterange" />
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Assets -->
                <div class="col-6 col-md-4 mb-4">
                    <div class="card border-left-secondary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                        <a href="{{ route('home.labor.printable') }}" target="_blank"
                                            class="btn btn-sm btn-secondary">
                                            <i class="fas fa-hand-holding-usd"></i>
                                        </a>
                                        Total Project
                                    </div>
                                    <div class="h5 font-weight-bold text-gray-800">{{ number_format($labor_total, 2) }}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Assets -->
                <div class="col-6 col-md-4 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                        <a href="{{ route('home.assets.printable') }}" target="_blank"
                                            class="btn btn-sm btn-primary">
                                            <i class="fas fa-file-download"></i>
                                        </a>
                                        Assets
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($assets, 2) }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Product Stocks -->
                <div class="col-6 col-md-4 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                        <a v-bind:href="'/products/'" class="btn btn-sm btn-success">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        Product Stocks
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $stocks }}</div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-boxes fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
    </div>

    <div id='app' class="container-fluid">
        <div class="row">
            <div class="col-6 mb-4">
                <livewire:top-sales-agent-livewire>
            </div>
            <div class="col-6 mb-4">
                <livewire:top-customer-livewire>
            </div>
            <div class="col-12 mb-4">
                <livewire:top-products-livewire>
            </div>
        </div>

        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Fast Moving</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-fast-moving" class="table table-striped nowrap" style="width:100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">In Stock</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-in-stock" class="table table-striped nowrap" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">

                $('#expenses_totals').daterangepicker({
                opens: 'left',
                }, function (start, end, label) {
                $this.expenses_range.start = start.format('YYYY-MM-DD');
                $this.expenses_range.end = end.format('YYYY-MM-DD');
                $this.getExpenseTotals();
                $('#expenses_totals').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                });
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Out of Stock</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-out-of-stock" class="table table-striped nowrap"
                                    style="w

                                       $('#expenses_totals').daterangepicker({
                                           opens: 'left',
                                       }, function (start, end, label) {
                                           $this.expenses_range.start = start.format('YYYY-MM-DD');
                                           $this.expenses_range.end = end.format('YYYY-MM-DD');
                                           $this.getExpenseTotals();
                                           $('#expenses_totals').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                                       });idth:100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">PO in Ordered Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-po" class="table table-striped nowrap" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">SO Quotation</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-so" class="table table-striped nowrap" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Returned SO</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-so-returned" class="table table-striped nowrap" style="width:100%">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="downloadTypeMdl" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">SO Download Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    {{-- <div class="modal-body">
                    </div> --}}
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a target="_blank" v-bind:href="'/home/so/printable/' + so_range.start + '/' + so_range.end"
                            class="btn btn-primary">Sales Order</a>
                        <a target="_blank" v-bind:href="'/home/qtn/printable/' + so_range.start + '/' + so_range.end"
                            class="btn btn-info">Quotation</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        const app = new Vue({
            el: '#app',
            data() {
                return {
                    dt: null,
                    overview: {
                        id: "",
                        subject: "",
                        recipient_email: "",
                        recipient_name: "",
                        message: "",
                    },
                    so_range: {
                        start: '0',
                        end: '0'
                    },
                    po_range: {
                        start: '0',
                        end: '0'
                    },
                    expenses_range: {
                        start: '0',
                        end: '0'
                    },
                    po_totals: 0,
                    so_totals: 0,
                    expense_totals: 0,
                }
            },
            methods: {
                getExpenseTotals() {
                    var $this = this;
                    $.ajax({
                        url: "{{ route('home.total.expenses') }}",
                        method: 'POST',
                        data: $this.expenses_range,
                        success(value) {
                            $this.expense_totals = value;
                        }
                    });
                },
                getSOTotals() {
                    var $this = this;
                    $.ajax({
                        url: "{{ route('home.total.so') }}",
                        method: 'POST',
                        data: $this.so_range,
                        success(value) {
                            $this.so_totals = value;
                        }
                    });
                },
                getPOTotals() {
                    var $this = this;
                    $.ajax({
                        url: "{{ route('home.total.po') }}",
                        method: 'POST',
                        data: $this.po_range,
                        success(value) {
                            $this.po_totals = value;
                        }
                    });
                },
                numberWithCommas(x) {
                    return parseFloat(x).toFixed(2).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
                }

            },
            mounted() {
                var $this = this;

                $this.getSOTotals();
                $this.getPOTotals();
                $this.getExpenseTotals();

                fetch('https://geolocation-db.com/json/')
                    .then((response) => {
                        return response.json();
                    })
                    .then((data) => {
                        // Work with JSON data here
                        console.log(data);
                    });

                $('#expenses_totals').daterangepicker({
                    opens: 'left',
                }, function(start, end, label) {
                    $this.expenses_range.start = start.format('YYYY-MM-DD');
                    $this.expenses_range.end = end.format('YYYY-MM-DD');
                    $this.getExpenseTotals();
                    $('#expenses_totals').val(start.format('YYYY-MM-DD') + ' - ' + end.format(
                    'YYYY-MM-DD'));
                });

                $('#po_totals').daterangepicker({
                    opens: 'left',
                }, function(start, end, label) {
                    $this.po_range.start = start.format('YYYY-MM-DD');
                    $this.po_range.end = end.format('YYYY-MM-DD');
                    $this.getPOTotals();
                    $('#po_totals').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                });

                $('#so_totals').daterangepicker({
                    opens: 'right',
                }, function(start, end, label) {
                    $this.so_range.start = start.format('YYYY-MM-DD');
                    $this.so_range.end = end.format('YYYY-MM-DD');
                    $this.getSOTotals();
                    $('#so_totals').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                });

                $this.dt = $('#table-in-stock').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    lengthChange: false,
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 5,
                    ajax: {
                        url: "{{ route('home.instock') }}",
                        method: "POST",
                    },
                    columns: [{
                            data: 'code',
                            name: 'products.code',
                            title: 'Product Model'
                        },
                        {
                            data: 'product_name',
                            name: "products.name",
                            title: 'Product'
                        },
                        {
                            data: 'quantity',
                            title: 'Quantity'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                        });
                    }
                });

                $this.dt = $('#table-fast-moving').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    lengthChange: false,
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 5,
                    ajax: {
                        url: "{{ route('home.fast.moving') }}",
                        method: "POST",
                    },
                    columns: [{
                            data: 'code',
                            name: 'products.code',
                            title: 'Product Model'
                        },
                        {
                            data: 'product_name',
                            name: "products.name",
                            title: 'Product'
                        },
                        {
                            data: 'quantity',
                            title: 'Quantity'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                        });
                    }
                });

                $this.dt = $('#table-out-of-stock').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    lengthChange: false,
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 5,
                    ajax: {
                        url: "{{ route('home.outofstock') }}",
                        method: "POST",
                    },
                    columns: [{
                            data: 'code',
                            name: 'products.code',
                            title: 'Product Model'
                        },
                        {
                            data: 'product_name',
                            name: "products.name",
                            title: 'Product'
                        },
                        {
                            data: 'quantity',
                            title: 'Quantity'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                        });
                    }
                });

                $this.dt = $('#table-po').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    lengthChange: false,
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 5,
                    ajax: {
                        url: "{{ route('home.po') }}",
                        method: "POST",
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            title: 'ID'
                        },
                        {
                            data: 'subject',
                            name: "subject",
                            title: 'Subject'
                        },
                        {
                            data: 'status',
                            title: 'Status'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                        });
                    }
                });

                $this.dt = $('#table-so-returned').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    lengthChange: false,
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 5,
                    ajax: {
                        url: "{{ route('home.returned') }}",
                        method: "POST",
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            title: 'ID'
                        },
                        {
                            data: 'subject',
                            name: "subject",
                            title: 'Subject'
                        },
                        {
                            data: 'status',
                            title: 'Status'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                        });
                    }
                });
                $this.dt = $('#table-so').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    lengthChange: false,
                    order: [
                        [0, 'desc']
                    ],
                    pageLength: 5,
                    ajax: {
                        url: "{{ route('home.so') }}",
                        method: "POST",
                    },
                    columns: [{
                            data: 'id',
                            name: 'id',
                            title: 'ID'
                        },
                        {
                            data: 'subject',
                            name: "subject",
                            title: 'Subject'
                        },
                        {
                            data: 'status',
                            title: 'Status'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                        });
                    }
                });
            }
        });
    </script>
@endsection
