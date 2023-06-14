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
                            <div class="col-md-12 mt-3">
                                <table id="table-inquiry" class="table table-striped nowrap table-general"
                                    style="width:100%"></table>
                            </div>
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
                }
            },
            methods: {},
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
                        method: "POST",
                    },
                    columns: [{
                            data: function(value) {
                                edit = '<a href="/job-order/edit/' + value.id +
                                    '" class="btn btn-info btn-view"><i class="fa fa-pen"></i></a>';
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
