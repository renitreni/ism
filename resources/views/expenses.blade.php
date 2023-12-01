@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Expenses Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <a href="{{ route('expenses.create') }}" class="btn btn-sm btn-success"><i
                                            class="fa fa-plus"></i> New Expenses</a>
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-customer" class="table table-striped nowrap table-general"
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
                                url: "{{ route('expenses.destroy') }}",
                                method: 'POST',
                                data: $this.overview,
                                success(value) {
                                    Swal.fire('Deleted!', 'Your file has been deleted.', 'success');
                                    $this.dt.draw();
                                },
                                error(e) {
                                    console.log(e);
                                    Swal.fire(
                                        e.statusText,
                                        e.responseJSON.message,
                                        'warning'
                                    );
                                }
                            });
                        }
                    });
                }
            },
            mounted() {
                var $this = this;
                $this.dt = $('#table-customer').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    pageLength: 100,
                    order: [[1, 'desc']],
                    ajax: {
                        url: "{{ route('expenses.table') }}",
                        method: "POST",
                    },
                    columns: [
                        {
                            data: function (value) {
                                return '<div class="btn-group btn-group-sm shadow-sm" role="group" aria-label="Basic example">' +
                                    '<a href="/expenses/detail/' + value.id + '" class="btn btn-info btn-view"><i class="fa fa-pen"></i></a>' +
                                    '<button type="button" class="btn btn-danger btn-destroy"><i class="fa fa-trash"></i></button>' +
                                    '</div>'
                            },
                            searchable: false,
                            bSortable: false,
                            title: 'Action'
                        },
                        {data: 'expenses_no', name: 'expenses_no', title: 'ID'},
                        {data: 'cost_center', title: 'Cost Center'},
                        {data: 'description', title: 'Description'},
                        {data: 'person_assigned', title: 'Person Assigned'},
                        {data: 'total_amount', title: 'Total Amount'},
                        {
                            data: function(value) {
                                var $class_color = 'btn-success';
                                var $name = '';
                                if (["VAT EX"].includes(value.vat_type)) {
                                    $class_color = 'btn-info';
                                    $name = "VE";
                                } else if (["VAT INC"].includes(value.vat_type)) {
                                    $class_color = 'btn-primary';
                                    $name = "VI";
                                }
                                return '<div class="btn-group btn-group-sm shadow-sm btn-block" role="group">' +
                                    '<a href="#" class="btn ' + $class_color + ' value="'+value.vat_type+'" btn-vat">' +
                                    $name + '</a>' +
                                    '</div>'
                            },
                            name: 'vat_type',
                            title: 'Vat'
                        },
                        {data: 'expense_date', title: 'Expenses'},
                        {data: 'si_no', title: 'SI Number'},
                        {data: 'dr_no', title: 'DR Number'},
                        {data: 'remarks', title: 'Remarks'},
                    ],
                    drawCallback: function () {
                        $('table .btn').on('click', function () {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                        });

                        $('.btn-destroy').on('click', function () {
                            $this.destroy();
                        });
                    }
                });
            }
        });
    </script>
@endsection
