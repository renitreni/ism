@extends('admin_layout')

@section('styles')
    <style>
        input,
        select,
        textarea {
            color: #ffffff !important;
            background-color: #ffffff00 !important;
        }

        .select2-selection__rendered {
            color: #ffffff !important;
        }

        .select2-container--default .select2-selection--single {
            background-color: #ffffff00;
        }
    </style>
@endsection

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sales Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('sales.create') }}" class="btn btn-sm btn-success"><i
                                        class="fa fa-plus"></i> New Sales Order</a>
                                <a href="#!" class="btn btn-sm btn-info" data-toggle="modal"
                                    data-target="#salesReportMdl">
                                    <i class="fas fa-download"></i> Sales Report</a>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12 mt-3 d-flex justify-content-center">
                                <div class="form-group" style="padding-right: 11px;">
                                    <label class="control-label">Filter Payment</label>
                                    <select class="form-control" name="filter_payment" id="filter_payment">
                                        <option value="" selected>-- Select Options --</option>
                                        <option value="PAID">PAID</option>
                                        <option value="UNPAID">UNPAID</option>
                                        <option value="PAID WITH BALANCE">PAID WITH BALANCE</option>
                                        <option value="STOCK OUT">STOCK OUT</option>

                                    </select>
                                </div>
                                <div class="form-group" style="padding-right: 11px;">
                                    <label class="control-label">Filter status</label>
                                    <select class="form-control" name="filter_status" id="filter_status">
                                        <option value="" selected>-- Select Options --</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Project">Project</option>
                                    </select>
                                </div>
                                <div class="form-group" style="padding-right: 11px;">
                                    <label class="control-label">Filter Delivery Status</label>
                                    <select class="form-control" name="filter_delivery_status" id="filter_delivery_status">
                                        <option value="" selected>-- Select Options --</option>
                                        <option value="Not Shipped">Not Shipped</option>
                                        <option value="Shipped">Shipped</option>
                                    </select>
                                </div>
                                <div class="form-group" style="padding-right: 11px;">
                                    <label class="control-label">Filter VAT</label>
                                    <select class="form-control" name="filter_vat" id="filter_vat">
                                        <option value="" selected>-- Select Options --</option>
                                        <option value="VAT EX">VE</option>
                                        <option value="VAT INC">VI</option>
                                    </select>
                                </div>
                                <div class="form-group" style="padding-top:32px;">
                                    <button class="btn btn-info" id="filter_search"> Search </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <table id="table-sales" class="table table-striped nowrap table-general"
                                    style="width:100%; text-align:center;">
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="shippedDateModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Pick a status</label>
                                    <input type="date" class="form-control" v-model="overview.shipped_date">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="update">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="statusModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Pick a status</label>
                                    <select class="form-control" v-model="overview.status">
                                        <option value="Quote">Quote</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Project">Project</option>
                                        <option value="Stock Out">Stock Out</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="update">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="vatTypeModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Vat Type</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Pick a type</label>
                                    <select class="form-control" v-model="overview.vat_type">
                                        <option value="">-- Select Options --</option>
                                        <option value="VAT EX">VAT EX</option>
                                        <option value="VAT INC">VAT INC</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updateVat">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="deliveryStatusModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Delivery Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Delivery Status</label>
                                    <select type="text" class="form-control form-control-sm"
                                        v-model="overview.delivery_status">
                                        @can('salesstatusupdate')
                                            @can('statusUpdateToUnshipped')
                                                <option value="Not Shipped">Not Shipped</option>
                                            @endcan
                                            @can('statusUpdateToShipped')
                                                <option value="Shipped">Shipped</option>
                                            @endcan
                                        @endcan
                                    </select>
                                    @cannot('statusUpdateToUnshipped')
                                        <label for="">You dont have permission to Not Shipped please contact the
                                            administrator</label>
                                    @endcannot
                                    @cannot('statusUpdateToShipped')
                                        <label for="">You dont have permission to Shipped please contact the
                                            administrator</label>
                                    @endcannot
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updateDelivery">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="paymentModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Payment Status</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Pick a Status</label>
                                    <select class="form-control" v-model="overview.payment_status">
                                        <option value="">-- Select Options --</option>
                                        <option value="PAID">PAID</option>
                                        <option value="UNPAID">UNPAID</option>
                                        <option value="PAID WITH BALANCE">PAID WITH BALANCE</option>
                                        <option value="STOCK OUT">STOCK OUT</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="updatePayment">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="printModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Sales Print</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="row" style="text-align: center">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Warranty Slip</label>
                                            <a href="/sales/print/" id="print_warranty"
                                                class="btn btn-primary btn-block"><i class="fa fa-print"
                                                    aria-hidden="true"></i> WS</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Quote</label>
                                            <a href="/sales/quote/" id="print_quote" class="btn btn-primary btn-block"><i
                                                    class="fa fa-print" aria-hidden="true"></i> QN</a>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label class="control-label">Delivery Receipt</label>
                                            <a href="/sales/deliver/" id="print_delivery"
                                                class="btn btn-primary btn-block"><i class="fa fa-print"
                                                    aria-hidden="true"></i> DR</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="salesReportMdl" tabindex="-1" role="dialog" aria-labelledby="salesReportMdl"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Download Sales Report </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <label>Set Date Range</label>
                                <input type="text" id="sales_report" class="form-control" name="daterange" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a v-bind:href="'/sales/report/'+ sales_report.start_date +'/'+ sales_report.end_date"
                            type="button" class="btn btn-primary">DOWNLOAD</a>
                        <a v-bind:href="'/sales/report/all'" type="button" class="btn btn-primary">DOWNLOAD ALL</a>
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
                        status: ""
                    },
                    sales_report: {
                        start_date: '0',
                        end_date: '0'
                    }

                }
            },
            methods: {
                update() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('sales.status.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire('Updated!', 'Status has been updated.', 'success');
                            $this.dt.draw();
                            $('#deliveryStatusModal').modal('hide');
                            $('#statusModal').modal('hide');
                            $('#vatTypeModal').modal('hide');
                            $('#paymentModal').modal('hide');
                            $('#shippedDateModal').modal('hide');
                        }
                    });
                },
                updatePayment() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('sales.payment.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire('Updated!', 'Status has been updated.', 'success');
                            $this.dt.draw();
                            $('#paymentModal').modal('hide');
                        }
                    });
                },
                updateVat() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('sales.vat.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire('Updated!', 'Status has been updated.', 'success');
                            $this.dt.draw();
                            $('#vatTypeModal').modal('hide');
                        }
                    });
                },
                updateDelivery() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('sales.delivery.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire('Updated!', 'Status has been updated.', 'success');
                            $this.dt.draw();
                            $('#deliveryStatusModal').modal('hide');
                        }
                    });
                },
                destroy() {
                    var $this = this;
                    Swal.fire({
                        title: 'Are you sure?',
                        text: "You won't be able to revert this!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#3085d6',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, delete it!'
                    }).then((result) => {
                        if (result.value) {
                            $.ajax({
                                url: "{{ route('sales.destroy') }}",
                                method: 'POST',
                                data: $this.overview,
                                success(value) {
                                    Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                                    $this.dt.draw();
                                }
                            });
                        }
                    });
                }
            },
            mounted() {
                var $this = this;

                $('#sales_report').daterangepicker({
                    opens: 'left',
                }, function(start, end, label) {
                    $this.sales_report.start_date = start.format('YYYY-MM-DD');
                    $this.sales_report.end_date = end.format('YYYY-MM-DD');
                    $('#sales_report').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
                });

                $('#sales_report').val('');

                $this.dt = $('#table-sales').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    pageLength: 100,
                    order: [
                        [1, 'desc']
                    ],
                    ajax: {
                        url: "{{ route('sales.table') }}",
                        data: function(data) {
                            data.filter_payment = $("#filter_payment").val();
                            data.filter_status = $("#filter_status").val();
                            data.filter_delivery_status = $("#filter_delivery_status").val();
                            data.filter_vat = $("#filter_vat").val();
                        },
                        method: "POST",
                    },
                    columns: [{
                            data: function(value) {
                                if (value.delivery_status !== 'Shipped') {
                                    edit = '<a href="/sales/detail/' + value.id +
                                        '" class="btn btn-info btn-view"><i class="fa fa-pen"></i></a>';
                                } else {
                                    edit = '';
                                }
                                return '<div class="btn-group btn-group-sm shadow-sm" role="group" aria-label="Basic example">' +
                                    '<a href="/sales/view/' + value.id +
                                    '" class="btn btn-primary btn-view">' +
                                    '<i class="fa fa-eye"></i></a>' +
                                    '<a class="btn btn-primary display_print" data="' + value.id +
                                    '"><i class="fa fa-print" aria-hidden="true"></i></a>' +
                                    edit +
                                    '<button type="button" class="btn btn-danger btn-destroy"><i class="fa fa-trash"></i></button>' +
                                    '<button type="button" class="btn btn-danger btn-clone" data="' + value.id + '" data-so="' + value.so_no +'" ><i class="fa fa-clone"></i></button>' +
                                    '</div>'
                            },
                            searchable: false,
                            bSortable: false,
                            title: 'Action'
                        },
                        {
                            data: 'so_no',
                            name: 'sales_orders.so_no',
                            title: 'SO NO.'
                        },
                        {
                            data: function(value) {
                                var $class_color = value.payment_status === 'UNPAID' ?
                                    'btn-warning' : 'btn-success';
                                return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group">' +
                                    '<a href="#" class="btn ' + $class_color + ' btn-payment">' +
                                    value.payment_status + '</a>' +
                                    '</div>'
                            },
                            name: 'sales_orders.payment_status',
                            title: 'Payment'
                        },
                        {
                            data: function(value) {
                                var $class_color = 'btn-success';
                                if (["Quote"].includes(value.status)) {
                                    $class_color = 'btn-warning';
                                } else if (["Project"].includes(value.status)) {
                                    $class_color = 'btn-primary';
                                }
                                return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group">' +
                                    '<a href="#" class="btn ' + $class_color + ' btn-status">' +
                                    value.status + '</a>' +
                                    '</div>'
                            },
                            name: 'status',
                            title: 'Status'
                        },
                        {
                            data: function(value) {
                                if (value.can_be_shipped || value.delivery_status == 'Shipped') {
                                    var $class_color = value.delivery_status === 'Not Shipped' ?
                                        'btn-warning' : 'btn-success';
                                    return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group">' +
                                        '<a href="#" class="btn ' + $class_color +
                                        ' btn-delivery-status">' + value.delivery_status + '</a>' +
                                        '</div>'
                                }
                                return 'Check Inventory';
                            },
                            name: 'status',
                            title: 'Delivery Status'
                        },
                        {
                            data: function(value) {
                                var $class_color = 'btn-success';
                                if (["VAT EX"].includes(value.vat_type)) {
                                    $class_color = 'btn-info';
                                    $name = "VE";
                                } else if (["VAT INC"].includes(value.vat_type)) {
                                    $class_color = 'btn-primary';
                                    $name = "VI";
                                }else{
                                    $name = "VE";
                                }
                                return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group">' +
                                    '<a href="#" value="' + value.vat_type + '" class="btn ' +
                                    $class_color + ' btn-vat">' +
                                    $name + '</a>' +
                                    '</div>'
                            },
                            name: 'vat_type',
                            title: 'Vat'
                        },

                        {
                            data: 'customer_name',
                            name: 'customers.name',
                            title: 'Customer'
                        },
                        {
                            data: 'subject',
                            name: 'subject',
                            title: 'Subject'
                        },
                        {
                            data: 'grand_total',
                            name: 'summaries.grand_total',
                            title: 'Total'
                        },
                        {
                            data: 'agent',
                            name: 'agent',
                            title: 'Assigned'
                        },
                        {
                            data: function(value) {
                                if (value.shipped_date_display == 'No Date')
                                    return value.shipped_date_display

                                return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group" aria-label="Basic example">' +
                                    '<a href="#" class="btn btn-info btn-shipped-date">' +
                                    value.shipped_date_display + '</a>' +
                                    '</div>'
                            },
                            name: 'shipped_date',
                            title: 'Shipped Date',
                        },
                        {
                            data: 'due_date',
                            name: 'due_date',
                            title: 'Date of Purchased'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                        });
                        $('.btn-destroy').on('click', function() {
                            $this.destroy();
                        });
                        $('.btn-clone').on('click', function() {
                            let id = $(this).attr('data');
                            let so = $(this).attr('data-so');
                            Swal.fire({
                                title: "Do you want to clone this "+so+"?",
                                showCancelButton: true,
                                confirmButtonText: "Clone"
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    $.ajax({
                                        url: "{{ route('sales.clone') }}",
                                        method: 'POST',
                                        data: {id: id},
                                        success(value) {
                                            Swal.fire('Cloned!', 'Your file has been cloned.', 'success');
                                            $this.dt.draw();
                                        }
                                    });
                                }
                            });

                        });
                        $('.btn-status').on('click', function() {
                            $('#statusModal').modal('show');
                        });
                        $('.btn-vat').on('click', function() {
                            $('#vatTypeModal').modal('show');
                        });
                        $('.btn-payment').on('click', function() {
                            $('#paymentModal').modal('show');
                        });
                        $('.btn-delivery-status').on('click', function() {
                            $('#deliveryStatusModal').modal('show');
                        });
                        $('.btn-shipped-date').on('click', function() {
                            $('#shippedDateModal').modal('show');
                        });
                        $('.display_print').on('click', function(e) {
                            var id = $(this).attr('data');
                            $("#print_warranty").attr("href", "/sales/print/" + id);
                            $("#print_quote").attr("href", "/sales/quote/" + id);
                            $("#print_delivery").attr("href", "/sales/deliver/" + id);
                            $('#printModal').modal('show');
                        });
                    }
                });
                $(document).on('click', '#filter_search', function() {
                    $this.dt.draw();
                });
            }
        });
    </script>
@endsection
