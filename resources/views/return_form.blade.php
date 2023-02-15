@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Return Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Product Return Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <select class="form-control form-control-sm select2-customer">
                                    </select>
                                    <input v-show="viewType == 2" class="form-control form-control-sm"
                                           v-model="overview.customer_name">
                                </div>
                                <div class="form-group">
                                    <label>Return Type</label>
                                    <select class="form-control form-control-sm" v-model="overview.return_type">
                                        <option value="">-- Select Type --</option>
                                        <option value="For Warranty">For Warranty</option>
                                        <option value="For Change Item">For Change Item</option>
                                        <option value="Refund">Refund</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sales Order (Shipped)</label>
                                    <select class="form-control form-control-sm select2-sales-order">
                                    </select>
                                    <input v-show="viewType == 2" class="form-control form-control-sm"
                                           v-model="overview.sales_order_id">
                                </div>
                                <div class="form-group">
                                    <label>Contact Person</label>
                                    <input class="form-control form-control-sm" v-model="overview.contact_person">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product Return No.</label>
                                    <input class="form-control form-control-sm" v-model="overview.pr_no">
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select class="form-control form-control-sm" v-model="overview.status">
                                        <option value="">-- Select Type --</option>
                                        <option value="received">Received</option>
                                        <option value="rma pull out">RMA Pull Out</option>
                                        <option value="refund">RMA Returned</option>
                                        <option value="released">Released</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Product Details</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <caption v-if="viewType != 2">
                                            <button class="btn btn-sm btn-success" data-toggle="modal"
                                                    data-target="#productModal">Add Product
                                            </button>
                                            <button class="btn btn-sm btn-warning" data-toggle="modal"
                                                    data-target="#categoryModal">Add Category
                                            </button>
                                        </caption>
                                        <thead>
                                        <th v-for="column in columns" v-if="column != 'Action' || viewType != 2">@{{ column }}</th>
                                        </thead>
                                        <tbody>
                                        <tr v-for="(product, index) in products">
                                            <td v-if="product.product_name"><input readonly type="text"
                                                                                   class="form-control-plaintext form-control-sm"
                                                                                   v-model="product.product_name"></td>
                                            <td v-else colspan="3" style="background-color: rgba(61, 61, 61, 0.925);">
                                                <h5 style="margin-top: 5px;"><strong>@{{ product.category }}</strong>
                                                </h5>
                                            </td>
                                            <td v-if="product.product_name">
                                                <input type="text"
                                                    class="form-control form-control-sm"
                                                    style="width: 100px;"
                                                    v-model="product.qty">
                                            </td>
                                            <td v-if="product.product_name">
                                                <input type="text"
                                                        class="form-control form-control-sm"
                                                        style="width: 100px;"
                                                        v-model="product.selling_price">
                                            </td>
                                            <td v-if="viewType != 2">
                                                <button class="btn btn-sm btn-block btn-danger" @click="remove(index)">
                                                    <i class="fa fa-ban"></i></button>
                                            </td>
                                        </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Reason</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea type="text" class="form-control form-control-sm"
                                                  v-model="overview.reason" rows="5" style="height: 80px;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Remarks</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea type="text" class="form-control form-control-sm"
                                                  v-model="overview.remarks" rows="5"
                                                  style="height: 80px;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a href="{{ route('return') }}" class="btn btn-warning">Back</a>
                                <button class="btn btn-info" v-if="viewType == 1" @click="store">Save New</button>
                                <button class="btn btn-primary" v-if="viewType == 0" @click="update">Update Now</button>
                                <a href="{{ route('return.print', isset($product_return->id)?$product_return->id: '') }}"
                                   class="btn btn-primary" v-if="viewType == 2">Product Return</a>
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
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="addRow()">Insert
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <div id="categoryModal" class="modal fade" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Find a Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Category</label>
                                    <select id="select2-category" class="form-control">
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal" @click="addCategory()">Insert
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
                    viewType: 0,
                    columns: [
                        'Product', 'Qty', 'Unit Cost'
                    ],
                    sub_total: 0,
                    overview: {!! $product_return !!},
                    products: {!! $product_details !!},
                    selling_price: 0,
                    vendor_price: 0,
                }
            },
            watch: {
            },
            methods: {
                store() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('return.store') }}',
                        method: 'POST',
                        data: {
                            overview: $this.overview,
                            products: $this.products,
                        },
                        success: function (value) {
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('return') }}'
                                }
                            })
                        }
                    })

                },
                update() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('purchase.update') }}',
                        method: 'POST',
                        data: {
                            overview: $this.overview,
                            products: $this.products,
                            summary: $this.summary,
                        },
                        success: function (value) {
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('purchase') }}'
                                }
                            })
                        }
                    })
                },
                remove(product) {
                    this.products.splice(product, 1);
                },
                addRow() {
                    var $this = this;
                    $.ajax({
                        url: '{{route('product.find')}}',
                        method: 'POST',
                        data: {
                            product_id: $('.select2-product').find(':selected').val()
                        },
                        success: function (value) {
                            $this.selling_price = parseFloat(value.selling_price);
                            $this.vendor_price = parseFloat(value.vendor_price);
                            $this.products.push(
                                {
                                    product_id: $('.select2-product').find(':selected').val(),
                                    product_name: $('.select2-product').find(':selected').text(),
                                    notes: '',
                                    quantity: value.quantity,
                                    qty: 0,
                                    selling_price: $this.selling_price,
                                    labor_cost: 0,
                                    vendor_price: $this.vendor_price,
                                    discount_item: 0,
                                    category: value.category
                                }
                            );
                            $('.select2-product').val(null).trigger('change');
                        }
                    });
                },
                addCategory() {
                    var $this = this;
                    $this.products.push(
                        {
                            category: $('#select2-category').find(':selected').text(),
                        }
                    );
                    $('#select2-category').val(null).trigger('change');
                },
                getCurrentCategory() {
                    var category = '';
                    var $this = this;
                    $.each($this.products, function (x, y) {
                        category = y.category;
                        console.log(y.category);
                    });

                    return category;
                },
            },
            mounted() {
                var $this = this;

                $('#select2-category').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('category.list') }}',
                        method: 'POST',
                        dataType: 'json'
                    }
                });

                $('.select2-sales-order').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('sales.shipped.list') }}',
                        method: 'POST',
                        dataType: 'json'
                    }
                }).on('select2:select', function (e) {
                    var data = e.params.data;
                    $this.overview.sales_order_id = data.id;
                });

                $('.select2-product').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('product.so.list') }}',
                        method: 'POST',
                        dataType: 'json',
                        data: function (params) {
                            params.category = $this.getCurrentCategory();
                            params.so_no = $this.overview.sales_order_id;

                            return params;
                        }
                    }
                });

                $('.select2-customer').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('customer.list') }}',
                        method: 'POST',
                        dataType: 'json'
                    }
                }).on('select2:select', function (e) {
                    var data = e.params.data;
                    $this.overview.customer_id = data.id;
                });

                var newOption = new Option($this.overview.customer_name, $this.overview.customer_id, true, true);
                $('.select2-customer').append(newOption).trigger('change');

                var newOption = new Option($this.overview.so_no, $this.overview.sales_order_id, true, true);
                $('.select2-sales-order').append(newOption).trigger('change');

                if ('{{ Route::currentRouteName() }}' == 'return.detail') {
                    this.viewType = 0;
                }
                else if ('{{ Route::currentRouteName() }}' == 'return.create') {
                    this.viewType = 1;
                }
                else if ('{{ Route::currentRouteName() }}' == 'return.view') {
                    this.viewType = 2;
                    $('label').addClass('font-weight-bold');
                    $('.form-control').addClass('form-control-plaintext').removeClass('form-control');
                    $('.select2').attr('hidden', 'hidden');
                }
            }
        });
    </script>
@endsection
