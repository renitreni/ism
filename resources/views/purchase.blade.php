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
                        <h6 class="m-0 font-weight-bold text-primary">Purchase Order Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('purchase.create') }}" class="btn btn-sm btn-success"><i
                                        class="fa fa-plus"></i> New Purchase Order</a>
                                <a href="#!" class="btn btn-sm btn-info" data-toggle="modal"
                                    data-target="#purchaseReportMdl">
                                    <i class="fas fa-download"></i> Purchase Report</a>
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-inquiry" class="table table-striped nowrap table-general"
                                    style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="purchaseReportMdl" tabindex="-1" role="dialog" aria-labelledby="purchaseReportMdl"
            aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Download Purchase Report </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-6">
                                <label>Set Date Range</label>
                                <input type="text" id="purchase_report" class="form-control" name="daterange" />
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <a v-bind:href="'/purchase/report/'+ purchase_report.start_date +'/'+ purchase_report.end_date"
                            type="button" class="btn btn-primary">DOWNLOAD</a>
                        <a v-bind:href="'/purchase/report/all'"
                            type="button" class="btn btn-primary">DOWNLOAD ALL</a>
                    </div>
                </div>
            </div>
        </div>

        <div id="receivedDateModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Edit Received Date</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Received Date</label>
                                    <input type="date" class="form-control form-control-sm"
                                        v-model="overview.received_date">
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
                                        <option value="Ordered">Ordered</option>
                                        <option value="Received">Received</option>
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
                                    <label class="control-label">Pick a status</label>
                                    <select class="form-control" v-model="overview.payment_status">
                                        <option value="">-- Select Options --</option>
                                        <option value="PAID">PAID</option>
                                        <option value="UNPAID">UNPAID</option>
                                        <option value="PAID WITH BALANCE">PAID WITH BALANCE</option>
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
                        <button type="button" class="btn btn-primary" @click="update">Save changes</button>
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
                    purchase_report: {
                        start_date: '0',
                        end_date: '0'
                    },
                    overview: {
                        id: "",
                        status: "",
                    }
                }
            },
            methods: {
                updatePayment() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('purchase.payment.status.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire('Updated!', 'Payment Status has been updated.', 'success');
                            $this.dt.draw();
                            $('#paymentModal').modal('hide');
                        }
                    })
                },
                update() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('purchase.status.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire('Updated!', 'Status has been updated.', 'success');
                            $this.dt.draw();
                            $('#statusModal').modal('hide');
                            $('#vatTypeModal').modal('hide');
                            $('#paymentModal').modal('hide');
                            $('#receivedDateModal').modal('hide');
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
                                url: "{{ route('purchase.destroy') }}",
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

                $('#purchase_report').daterangepicker({
                    opens: 'left',
                }, function(start, end, label) {
                    $this.purchase_report.start_date = start.format('YYYY-MM-DD');
                    $this.purchase_report.end_date = end.format('YYYY-MM-DD');
                    $('#purchase_report').val(start.format('YYYY-MM-DD') + ' - ' + end.format(
                        'YYYY-MM-DD'));
                    console.log(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'))
                });


                $this.dt = $('#table-inquiry').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    pageLength: 100,
                    order: [
                        [1, 'desc']
                    ],
                    ajax: {
                        url: "{{ route('purchase.table') }}",
                        method: "POST",
                    },
                    columns: [{
                            data: function(value) {
                                if (value.status == 'Ordered') {
                                    edit = '<a href="/purchase/detail/' + value.id +
                                        '" class="btn btn-info btn-view"><i class="fa fa-pen"></i></a>';
                                } else {
                                    edit = '';
                                }
                                return '<div class="btn-group btn-group-sm shadow-sm" role="group" aria-label="Basic example">' +
                                    '<a href="/purchase/view/' + value.id +
                                    '" class="btn btn-primary btn-view"><i class="fa fa-eye"></i></a>' +
                                    edit +
                                    '<button type="button" class="btn btn-danger btn-destroy"><i class="fa fa-trash"></i></button>' +
                                    '</div>'
                            },
                            searchable: false,
                            bSortable: false,
                            title: 'Action'
                        },
                        {
                            data: 'po_no',
                            name: 'purchase_infos.po_no',
                            title: 'PO NO.'
                        },
                        {
                            data: function(value) {
                                var $class_color = value.payment_status === 'UNPAID' ?
                                    'btn-warning' : 'btn-success';
                                return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group" aria-label="Basic example">' +
                                    '<a href="#" class="btn ' + $class_color + ' btn-payment">' +
                                    value.payment_status + '</a>' +
                                    '</div>'
                            },
                            name: 'payment_status',
                            title: 'Payment'
                        },
                        {
                            data: function(value) {
                                var $class_color = value.status === 'Ordered' ? 'btn-warning' :
                                    'btn-success';
                                return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group" aria-label="Basic example">' +
                                    '<a href="#" class="btn ' + $class_color + ' btn-status">' +
                                    value.status + '</a>' +
                                    '</div>'
                            },
                            name: 'status',
                            title: 'Status'
                        },
                        {
                            data: 'subject',
                            title: 'DR/SI'
                        },
                        {
                            data: 'vendor_name',
                            name: 'vendors.name',
                            title: 'Vendor'
                        },
                        {
                            data: 'grand_total',
                            name: 'summaries.grand_total',
                            title: 'Total'
                        },
                        {
                            data: 'name',
                            name: 'users.name',
                            title: 'Assigned'
                        },
                        {
                            data: function(value) {
                                if (value.received_date_display == 'No Date')
                                    return value.received_date_display

                                return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group" aria-label="Basic example">' +
                                    '<a href="#" class="btn btn-info btn-received-date">' +
                                    value.received_date_display + '</a>' +
                                    '</div>'
                            },
                            name: 'purchase_infos.received_date',
                            title: 'Received Date'
                        },
                        {
                            data: 'due_date',
                            name: 'due_date',
                            title: 'Due Date'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                        });
                        $('.btn-destroy').on('click', function() {
                            $this.destroy();
                        });
                        $('.btn-status').on('click', function() {
                            $('#statusModal').modal('show');
                        });
                        $('.btn-payment').on('click', function() {
                            $('#paymentModal').modal('show');
                        });
                        $('.btn-vat').on('click', function() {
                            $('#vatTypeModal').modal('show');
                        });
                        $('.btn-received-date').on('click', function() {
                            $('#receivedDateModal').modal('show');
                        });
                    }
                });
            }
        });
    </script>
@endsection
