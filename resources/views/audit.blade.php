@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Audit Log</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <button class="btn btn-danger" id="delete" @click="deleteLogs">Delete Logs</button>
                            </div>
                            <div class="col-md-auto">
                            </div>
                        </div>
                        <div class="row text-center">
                            <div class="col-md-12 mt-3 d-flex justify-content-center">
                                <div class="form-group" style="padding-right: 11px;">
                                    <label class="control-label">Filter PO/SO</label>
                                    <select class="form-control" v-model="filterSoPo" name="filter_so_po" id="filter_so_po">
                                        <option value="" selected>-- Select Options --</option>
                                        <option value="SO">SO</option>
                                        <option value="PO">PO</option>
                                    </select>
                                </div>
                                    <div  v-if="filterSoPo === 'PO'" class="form-group" style="padding-right: 11px;">
                                        <label class="control-label">Filter Status Previous </label>
                                        <select class="form-control" name="filter_status" id="filter_status1">
                                            <option value="" selected>-- Select Options --</option>
                                            <option value="Ordered">Ordered</option>
                                            <option value="Received">Received</option>
                                        </select>
                                    </div>  
                                    <div  v-if="filterSoPo === 'PO'" class="form-group" style="padding-right: 11px;">
                                        <label class="control-label"> to </label>
                                    </div> 
                                    <div  v-if="filterSoPo === 'PO'" class="form-group" style="padding-right: 11px;">
                                        <label class="control-label">Filter Status Current</label>
                                        <select class="form-control" name="filter_status" id="filter_status2">
                                            <option value="" selected>-- Select Options --</option>
                                            <option value="Ordered">Ordered</option>
                                            <option value="Received">Received</option>
                                        </select>
                                    </div>   

                                    <div  v-if="filterSoPo === 'SO'" class="form-group" style="padding-right: 11px;">
                                        <label class="control-label">Filter Delivery Status Previous</label>
                                        <select class="form-control" name="filter_payment" id="filter_ship1">
                                            <option value="" selected>-- Select Options --</option>
                                            <option value="Shipped">Shipped</option>
                                            <option value="Not Shipped">Not Shipped</option>
                                        </select>
                                    </div>
                                    <div  v-if="filterSoPo === 'SO'" class="form-group" style="padding-right: 11px;">
                                        <label class="control-label"> to </label>
                                    </div>
                                    <div  v-if="filterSoPo === 'SO'" class="form-group" style="padding-right: 11px;">
                                        <label class="control-label">Filter Delivery Status Current</label>
                                        <select class="form-control" name="filter_payment" id="filter_ship2">
                                            <option value="" selected>-- Select Options --</option>
                                            <option value="Shipped">Shipped</option>
                                            <option value="Not Shipped">Not Shipped</option>
                                        </select>
                                    </div>

                                <div class="form-group" style="padding-top:32px;">
                                    <button class="btn btn-info" id="filter_search" > Search </button>
                                </div>

                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12 mt-3" >
                                    <table id="table-product" class="table table-striped "
                                        ></table>
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
                    overview: '',
                    categories: '',
                    category_new: '',
                    filterSoPo:''
                }
            },
            methods: {
                deleteLogs() {
                    Swal.fire({
                        title: 'Do you want to delete all Audit Logs Data?',
                        showCancelButton: true,
                        confirmButtonText: 'Confirm',
                    }).then((result) => {
                        if (result.isConfirmed) {
                            $.ajax({
                                url: '{{ route('audit.delete') }}',
                                method: 'POST',
                                success: function(value) {
                                    if(value == "deleted"){
                                        Swal.fire(
                                            'Deleted!',
                                            'Operation is successful.',
                                            'success'
                                        ).then((result) => {
                                            if (result.value) {
                                                window.location = '{{ route('audit') }}'
                                            }
                                        })
                                    }
                                    if(value == "not_deleted"){
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Operation not success.',
                                        })
                                    }
                                }
                            })
                        } 
                    })
                },
            },
            mounted() {
                var $this = this;
                $this.dt = $('#table-product').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    // pageLength: 100,
                    "autoWidth": false,
                    order: [
                        [0, 'desc']
                    ],
                    ajax: {
                        url: "{{ route('audit.table') }}",
                        data: function(data) {
                            data.filter_so_po = $("#filter_so_po").val();
                            data.filter_status1 = $("#filter_status1").val();
                            data.filter_status2 = $("#filter_status2").val();
                            data.filter_ship1 = $("#filter_ship1").val();
                            data.filter_ship2 = $("#filter_ship2").val();
                        },
                        method: "POST",
                    },
                    columns: [{
                            data: 'created_at',
                            title: 'Timestamp',
                        },
                        {
                            data: 'user',
                            title: 'User',

                        },
                        {
                            data: 'url',
                            title: 'URL',
                        },
                        {
                            data: 'action',
                            title: 'Action',
                        },
                        
                    ],

                    drawCallback: function() {}
                });
                $( document ).on('click', '#filter_search', function() {
                    $this.dt.draw();
                });
            }
        });
    </script>
@endsection
