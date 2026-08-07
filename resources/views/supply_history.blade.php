@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Supply History Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <table id="table-vendor" class="table table-striped nowrap table-general" style="width:100%"></table>
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
                    overview: {
                        id: "",
                        subject: "",
                        recipient_email: "",
                        recipient_name: "",
                        message: "",
                    }
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
                                url: "{{ route('vendor.destroy') }}",
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
                $this.dt = $('#table-vendor').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    pageLength: 100,
                    order: [[1, 'desc']],
                    ajax: {
                        url: "{{ route('supply_history.table') }}",
                        method: "POST",
                    },
                    columns: [

                        {
                            data: function(value) {

                                return '<input class="form-control" value="' + value.product_name +
                                    '">';
                            },
                            name: 'value.product_name',
                            title: 'Product Name',
                            width: '50%'
                        },
                        {data: 'previous', title: 'PREV QTY'},
                        {data: 'quantity', title: 'QTY'},
                        {data: 'balance_qty', title: 'BALANCE QTY'},
                        // {data: 'unit', title: 'Unit'},
                        {data: 'from', title: 'From'},
                        {data: 'item_status', title: 'Status'},
                        {data: 'in_out', title: 'In Out'},
                        {data: 'po_so_id', title: 'PO SO Number'},
                        // {data: 'action_by', title: 'Action By'},
                        // {
                        //     data: 'created_at',
                        //     title: 'Date Created',
                        //     render: function(data, type, row) {
                        //         if (data) {
                        //             const date = new Date(data);
                        //             const formattedDate =
                        //                 (date.getMonth() + 1).toString().padStart(2, '0') + '/' +
                        //                 date.getDate().toString().padStart(2, '0') + '/' +
                        //                 date.getFullYear();
                        //             return formattedDate;
                        //         }
                        //         return '';
                        //     }
                        // },
                    ],
                    drawCallback: function () {

                    }
                });
            }
        });
    </script>
@endsection
