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
                                <a href="{{ route('job-order.create') }}" class="btn btn-sm btn-success"><i
                                        class="fa fa-plus"></i> New Job Order</a>
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12 mt-3 d-flex justify-content-center">
                                <div class="form-group" style="padding-right: 11px;">
                                    <label class="control-label">Filter Status</label>
                                    <select class="form-control" name="filter_status" id="filter_status">
                                        <option value="" selected>-- Select Options --</option>
                                        <option value="all">All</option>
                                        <option value="completed">Completed</option>
                                        <option value="ongoing">On-Going</option>
                                        <option value="received">Received</option>
                                        <option value="releasing">Releasing</option>
                                    </select>
                                </div>
                                <div class="form-group" style="padding-top:32px;">
                                    <button class="btn btn-info" id="filter_search" > Search </button>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <table id="table-inquiry" class="table table-striped nowrap table-general"
                                    style="width:100%;text-align: center;"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="statusHistoryMdl" tabindex="-1" aria-labelledby="statusHistoryMdlLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="statusHistoryMdlLabel">Job Order Status History</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12 row">
                                <div class="col-6 font-weight-bold">Status</div>
                                <div class="col-6 font-weight-bold">Date</div>
                            </div>
                            <div class="col-md-12 row" v-for="item in statusHistory">
                                <div class="col-6">@{{ item.status }}</div>
                                <div class="col-6">@{{ item.status_date }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
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
                    overview: null,
                    dt: null,
                    statusHistory: null
                }
            },
            methods: {
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
                                url: "{{ route('job-order.destroy') }}",
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
                        url: "{{ route('job-order.table') }}",
                        data: function(data) {
                            data.filter_status = $("#filter_status").val();
                        },
                        method: "POST",
                    },
                    columns: [{
                            data: function(value) {
                                edit = '<a href="/job-order/edit/' + value.id +
                                    '" class="btn btn-info btn-view"><i class="fa fa-pen"></i></a>';
                                statusHistory = '<button type="button" class="btn btn-warning" data-toggle="modal" data-target="#statusHistoryMdl">' +
                                    '<i class="fas fa-receipt"></i>' +
                                    '</button>';
                                return '<div class="btn-group btn-group-sm shadow-sm" role="group" aria-label="Basic example">' +
                                    '<a href="/job-order/preview/' + value.id +
                                    '" target="_blank" class="btn btn-primary btn-view" target="_blank"><i class="fa fa-download"></i></a>' +
                                    edit +
                                    statusHistory +
                                    '<button type="button" class="btn btn-danger btn-destroy"><i class="fa fa-trash"></i></button>' +
                                    '</div>'
                            },
                            searchable: false,
                            bSortable: false,
                            title: 'Action'
                        },
                        {
                            data: 'job_no',
                            name: 'job_no',
                            title: 'Job No.'
                        },
                        {
                            data: 'customer_name',
                            name: 'customer_name',
                            title: 'Customer Name'
                        },
                        {
                            data: 'process_type',
                            name: 'process_type',
                            title: 'Process Type'
                        },
                        {
                            data: 'date_of_purchased',
                            name: 'date_of_purchased',
                            title: 'Date Of Purchased'
                        },
                        {
                            data: 'so_no',
                            name: 'so_no',
                            title: 'Ref SO No.'
                        },
                        {
                            data: 'contact_person',
                            name: 'contact_person',
                            title: 'Contact Person'
                        },
                        {
                            data: 'mobile_no',
                            name: 'mobile_no',
                            title: 'Mobile No.'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            title: 'Status'
                        },
                        {
                            data: 'remarks',
                            name: 'remarks',
                            title: 'Remarks'
                        },
                    ],
                    drawCallback: function() {
                        $('table .btn').on('click', function() {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            $this.statusHistory = hold.job_order_status;
                            console.log(hold);
                        });
                        $('.btn-destroy').on('click', function() {
                            $this.destroy();
                        });
                    }

                });
                $( document ).on('click', '#filter_search', function() {
                    $this.dt.draw();
                });
            }
        });
    </script>
@endsection
