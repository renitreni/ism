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
                        <h6 class="m-0 font-weight-bold text-primary">Job Order Form</h6>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="col-md-2 mb-2">
                                    <label>Job No.</label>
                                    <input class="form-control" v-model='overview.job_no'>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label>Customer Name</label>
                                    <input class="form-control customer" v-model='overview.customer_name'>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label>Contact Person</label>
                                    <input class="form-control" v-model='overview.contact_person'>
                                </div>
                                <div class="col-md-3 mb-2">
                                    <label>Agent</label>
                                    <input class="form-control" v-model='overview.agent'>
                                </div>
                                <div class="col-md-12"></div>
                                <div class="col-md-2 mb-2">
                                    <label>Date Of Purchased</label>
                                    <input type="date" class="form-control" v-model='overview.date_of_purchased'>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>SO No.</label>
                                    <input class="form-control" v-model='overview.so_no'>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>Mobile No.</label>
                                    <input class="form-control" v-model='overview.mobile_no'>
                                </div>
                                <div class="col-md-12"></div>
                                <div class="col-md-2 mb-2">
                                    <label>Process Type</label>
                                    <select class="form-control" v-model='overview.process_type'>
                                        <option value="">-- Select Options --</option>
                                        @foreach ($processTypes as $item)
                                            <option value="{{ $item->value }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>Status</label>
                                    <select class="form-control" v-model='overview.status'>
                                        <option value="">-- Select Options --</option>
                                        @foreach ($statuses as $item)
                                            <option value="{{ $item->value }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2" v-if='overview.status'>
                                    <label>Status Date: @{{ overview.status }}</label>
                                    <input type="date" class="form-control" v-model='overview.status_date'>
                                </div>
                                <div class="col-md-12"></div>
                                <div class="col-md-4 mb-2">
                                    <label>Remarks</label>
                                    <textarea class="form-control" v-model='overview.remarks'></textarea>
                                </div>
                                <div class="col-md-12 mt-3 d-flex">
                                    <div>
                                        <button type="button" class="btn btn-sm btn-success mr-2" v-on:click="addProduct">
                                            <i class="fa fa-plus"></i>
                                        </button>
                                    </div>
                                    <h2>PRODUCTS</h2>
                                </div>
                                <div class="col-12">
                                    <div class="row">
                                        <div class="col-md-3 font-weight-bold">Product</div>
                                        <div class="col-md-1 font-weight-bold">Qty</div>
                                        <div class="col-md-2 font-weight-bold">Serial Number</div>
                                        <div class="col-md-3 font-weight-bold">Physical Appearance</div>
                                        <div class="col-md-2 font-weight-bold">Product Status</div>
                                        <div class="col-md-1 font-weight-bold">Actions</div>
                                    </div>
                                    <div class="row mt-2" v-for="product, idx in overview.products">
                                        <div class="col-md-3">
                                            <textarea type="text" class="form-control products" v-model="product.product"></textarea>
                                        </div>
                                        <div class="col-md-1"><input type="number" class="form-control"
                                                v-model="product.qty"></div>
                                        <div class="col-md-2"><input type="text" class="form-control"
                                                v-model="product.serial_number"></div>
                                        <div class="col-md-3"><textarea type="text" class="form-control"
                                                v-model="product.physical_appearance"></textarea></div>
                                        <div class="col-md-2"><textarea type="text" class="form-control"
                                                v-model="product.product_status"></textarea></div>
                                        <div class="col-md-1">
                                            <button type="button" v-on:click="removeProduct(idx, product.product)"
                                                class="btn btn btn-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12 mb-2 mt-2">
                                    <div class="d-flex flex-row w-auto">
                                        <button v-if="overview.id" type="button" class="btn btn-block btn-primary mx-2"
                                            @click="update()">Update</button>
                                        <button v-else type="button" class="btn btn-block btn-primary m-0"
                                            @click="save()">Submit</button>
                                        <a href="{{ route('job-order') }}"mobile_no
                                            class="btn btn-block btn-secondary m-0 mx-2">Cancel</a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div id="productModal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Find a Product</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Products</label>
                                    <select class="form-control select2-product">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="addProduct()">Insert
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
                    @if ($overview ?? null)
                        overview: @json($overview)
                    @else
                        overview: {
                            'job_no': "{{ $jobNo }}",
                            'customer_name': "",
                            'process_type': "",
                            'date_of_purchased': "",
                            'so_no': "",
                            'contact_person': "",
                            'mobile_no': "",
                            'status': "",
                            'remarks': "",
                            'agent': "",
                            'created_by': {{ auth()->id() }},
                            'products': []
                        }
                    @endif
                }
            },
            methods: {
                update() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('job-order.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('job-order') }}'
                                }
                            })
                        },
                        error(e) {
                            console.log(e);
                            Swal.fire(
                                e.statusText,
                                e.responseJSON.messaproduct.productge,
                                'warning'
                            );
                        }
                    });
                },
                save() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('job-order.store') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('job-order') }}'
                                }
                            })
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
                    product.product
                },
                addProduct() {
                    var $this = this;
                    //var prod_id = $('.select2-product').find(':selected').val();

                    $this.overview.products.push({
                                'product': '',
                                'qty': 0,
                                'serial_number': '',
                                'physical_appearance': '',
                                'product_status': ''
                            });
                    // $.ajax({
                    //     url: '{{ route('product.find') }}',
                    //     method: 'POST',
                    //     data: {
                    //         product_id: prod_id
                    //     },
                    //     success: function(value) {
                    //         $this.overview.products.push({
                    //             'product': value.name,
                    //             'qty': 0,
                    //             'serial_number': '',
                    //             'physical_appearance': '',
                    //             'product_status': ''
                    //         });
                    //         $('.select2-product').val(null).trigger('change');
                    //     }
                    // });
                },
                removeProduct(index, product) {
                    this.overview.products.splice(index, 1);
                }
            },
            mounted() {
                var $this = this;

                $('.select2-product').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('product.list') }}',
                        method: 'POST',
                        dataType: 'json',
                        data: function(params) {
                            return params;
                        }
                    }
                });

                $('.customer').autocomplete({
                    minLength: 2,
                    select: function(event, ui) {
                        $this.overview.customer_name = ui.item.value;
                    },
                    source: {!! $customers !!}
                });
            }
        });
    </script>
@endsection
