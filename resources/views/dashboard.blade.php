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
<div id="app">
    <div class="container-fluid">
        <div class="row">
            <!-- Total Expenses -->
            <livewire:component.total-expenses-component />
            <div></div>
            <!-- Total Po -->
            <livewire:component.total-p-o-component />
            <div></div>
            <!-- Total So -->
            <livewire:component.total-s-o-component />
            <div></div>
            <!-- Total Project -->
            <livewire:component.total-project-component />

            <!-- Total PO VI -->
            <div class="col-6 col-md-4 mb-4">
                <form method="POST" action="{{ route('home.povi.printable') }}">
                @csrf
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-file-download"></i>
                                        </button>
                                        Total PO VI
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"> <span id="total_po_vi"></span>
                                          {{-- {{ number_format($assets, 2) }} --}}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                        {{-- <input type="hidden" name="povi_month_text">
                        <input type="hidden" name="povi_year_text"> --}}
                        <div class="row">
                            <div class="col ml-3 mr-3 d-flex flex-row mt-4">
                                <select id="povi_month" name="povi_month" class="form-control">
                                    <option value="" selected disabled>Month</option>
                                    <option value="">All</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <select class="form-control" name="povi_year"  id="povi_year">
                                    <option value="" selected disabled>Year</option>
                                    @for ($i = 2020; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>

                    </div>
                </form>
                </div>

            <!-- Total SO VI -->
            <div class="col-6 col-md-4 mb-4">
                <form method="POST" action="{{ route('home.sovi.printable') }}">
                    @csrf
                    <div class="card border-left-primary shadow h-100 py-2">
                        <div class="card-body">
                            <div class="row no-gutters align-items-center">
                                <div class="col mr-2">
                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                        <button type="submit" class="btn btn-sm btn-primary">
                                            <i class="fas fa-file-download"></i>
                                        </button>
                                        Total SO VI
                                    </div>
                                    <div class="h5 mb-0 font-weight-bold text-gray-800"> <span id="total_so_vi"></span>
                                            {{-- {{ number_format($assets, 2) }} --}}
                                    </div>
                                </div>
                                <div class="col-auto">
                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col ml-3 mr-3 d-flex flex-row mt-4">
                                <select name="sovi_month" id="sovi_month" class="form-control">
                                    <option value="" selected disabled>Month</option>
                                    <option value="">All</option>
                                    <option value="01">January</option>
                                    <option value="02">February</option>
                                    <option value="03">March</option>
                                    <option value="04">April</option>
                                    <option value="05">May</option>
                                    <option value="06">June</option>
                                    <option value="07">July</option>
                                    <option value="08">August</option>
                                    <option value="09">September</option>
                                    <option value="10">October</option>
                                    <option value="11">November</option>
                                    <option value="12">December</option>
                                </select>
                                <select class="form-control" name="sovi_year" id="sovi_year">
                                    <option value="" selected disabled>Year</option>
                                    @for ($i = 2020; $i <= date('Y'); $i++)
                                        <option value="{{ $i }}">{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </form>
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
                                <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($assets, 2) }}
                                </div>
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

    <div class="container-fluid">
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
                                    style="width:100%">
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
                    getPOVITotals() {
                        var $this = this;

                        $.ajax({
                            url: "{{ route('home.total.povi') }}",
                            method: 'POST',
                            data: {
                                month:$('#povi_month').val(),
                                year:$('#povi_year').val()
                            },
                            success(value) {
                                console.log(value);
                                var formattedValue = parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                $('#total_po_vi').text(formattedValue);
                                // $this.po_totals = value;
                            }
                        });
                    },
                    getSOVITotals() {
                        var $this = this;
                        $.ajax({
                            url: "{{ route('home.total.sovi') }}",
                            method: 'POST',
                            data: {
                                month:$('#sovi_month').val(),
                                year:$('#sovi_year').val()
                            },
                            success(value) {
                                var formattedValue = parseFloat(value).toLocaleString('en-US', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
                                $('#total_so_vi').text(formattedValue);
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
                    $this.getPOVITotals();
                    $this.getSOVITotals();

                    fetch('https://geolocation-db.com/json/')
                    .then((response) => {
                        return response.json();
                    })
                    .then((data) => {
                        // Work with JSON data here
                        console.log(data);
                    });

                    $('#povi_month').on('change', function(e) {
                        // $('#povi_month_text').val($('#vi_month').val())
                        $this.getPOVITotals();

                    });
                    $('#povi_year').on('change', function() {
                        // $('#povi_year_text').val($('#povi_year').val())

                        $this.getPOVITotals();
                    });

                    $('#sovi_month').on('change', function() {
                        $this.getSOVITotals();
                    });
                    $('#sovi_year').on('change', function() {
                        $this.getSOVITotals();
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
