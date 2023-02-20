@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Supplies Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <table id="table-supplies" class="table table-striped nowrap table-general"
                                       style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="linksModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Links</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4" v-for="link in links">
                                <div class="form-group">
                                    <a v-bind:href="url + link.link" target="_blank" class="btn btn-link text-white">
                                        @{{ link.number }} <span class="badge badge-light">@{{ link.status }}</span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <a v-bind:href="url_view + overview.product_id" target="_blank" class="btn btn-primary">View
                            Grid</a>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="quantityModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Quantity Override</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Quantity</label>
                                    <input class="form-control" v-model="overview.quantity">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="updateQuantity">Save
                            Changes
                        </button>
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
                        quantity: 0,
                    },
                    links: [],
                    url: '',
                    url_view: ''
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
                },
                getPOlinks() {
                    var $this = this;
                    $.ajax({
                        url: "{{ route('supply.po.links') }}",
                        method: 'POST',
                        data: $this.overview,
                        success: function (value) {
                            $this.links = value;
                            $this.url = '/purchase/view/';
                            $this.url_view = '/supply/po/';
                        }
                    })
                },
                getSOlinks() {
                    var $this = this;
                    $.ajax({
                        url: "{{ route('supply.so.links') }}",
                        method: 'POST',
                        data: $this.overview,
                        success: function (value) {
                            $this.links = value;
                            $this.url = '/sales/view/';
                            $this.url_view = '/supply/so/';
                        }
                    });
                },
                updateQuantity() {
                    var $this = this;
                    $.ajax({
                        url: "{{ route('supply.update.quantity') }}",
                        method: 'POST',
                        data: $this.overview,
                        success: function (value) {
                            $this.dt.draw();
                        }
                    });
                }
            },
            mounted() {
                var $this = this;
                $this.dt = $('#table-supplies').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    pageLength: 100,
                    order: [[0, 'desc']],
                    ajax: {
                        url: "{{ route('supply.table') }}",
                        method: "POST",
                    },
                    columns: [
                        {data: 'product_name', name: 'products.name', title: 'Product Name', width: '100%'},
                        {data: 'code', name: 'products.code', title: 'Product Model'},
                        {data: 'selling_price', name: 'products.selling_price', title: 'Unit Price'},
                        {
                            data: function (value) {
                                if (value.unit == null) {
                                    value.unit = '';
                                }
                                return '<a href="/supply/versus/' + value.product_id + '" target="_blank" class="btn btn-sm btn-info">' + value.quantity + ' ' + value.unit + '</a>';
                            },
                            name: 'quantity', title: 'Quantity'
                        },
                        {
                            data: function (value) {
                                return '<a href="#" class="links-btn-po btn btn-sm btn-primary">' + value.po_count + '</a>';
                            },
                            name: 'po_sum.total', title: 'PO'
                        },
                        {
                            data: function (value) {
                                return '<a href="#" class="links-btn-so btn btn-sm btn-primary">' + value.so_count + '</a>';
                            },
                            name: 'so_sum.total', title: 'SO'
                        },
                    ],
                    drawCallback: function () {
                        $('table .btn').on('click', function () {
                            let data = $(this).parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                        });

                        $('.btn-destroy').on('click', function () {
                            $this.destroy();
                        });

                        $('.links-btn-po').on('click', function () {
                            $('#linksModal').modal('show');
                            $this.getPOlinks();
                        });

                        $('.links-btn-so').on('click', function () {
                            $('#linksModal').modal('show');
                            $this.getSOlinks();
                        });

                        $('.links-btn-quantity').on('click', function () {
                            $('#quantityModal').modal('show');
                        });
                    }
                });
            }
        });
    </script>
@endsection
