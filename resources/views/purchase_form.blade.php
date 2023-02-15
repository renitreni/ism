@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">
        @include('partials.loading')
        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Purchase Order Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Purchase Order Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>DR Number | SI Number</label>
                                    <input class="form-control form-control-sm" v-model="overview.subject">
                                </div>
                                <div class="form-group">
                                    <label>Contact Name</label>
                                    <input class="form-control form-control-sm"
                                           v-model="overview.contact_name">
                                </div>
                                <div class="form-group">
                                    <label>Tracking No.</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="overview.tracking_number">
                                </div>
                                <div class="form-group">
                                    <label>Due Date</label>
                                    <input type="date" class="form-control form-control-sm" v-model="overview.due_date">
                                </div>
                                @can('purchasestatusupdate')
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select type="text" class="form-control form-control-sm"
                                                v-model="overview.status">
                                            <option value="Ordered">Ordered</option>
                                            <option value="Received">Received</option>
                                        </select>
                                    </div>
                                @endcan
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Purchase Order</label>
                                    <input type="text" name="po_no" class="form-control form-control-sm"
                                           v-model="overview.po_no">
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control form-control-sm" v-model="overview.phone">
                                </div>
                                <div class="form-group">
                                    <label>Shipping Method</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="overview.shipping_method">
                                </div>
                                <div class="form-group">
                                    <label>Requisition No.</label>
                                    <input class="form-control form-control-sm"
                                           v-model="overview.requisition_no">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vendor Name</label>
                                    <select class="form-control form-control-sm select2-vendor">
                                    </select>
                                    <input v-show="viewType == 2" class="form-control form-control-sm"
                                           v-model="overview.vendor_name">
                                </div>
                                <div class="form-group">
                                    <label>Fax</label>
                                    <input type="text" class="form-control form-control-sm" v-model="overview.fax">
                                </div>
                                <div class="form-group">
                                    <label>VAT Type</label>
                                    <select type="text" class="form-control form-control-sm"
                                            v-model="overview.vat_type" v-on:change="grandTotal(parseFloat(summary.sub_total) - parseFloat(summary.discount))">
                                        <option value="">-- Select Options --</option>
                                        <option value="VAT EX">VAT EX</option>
                                        <option value="VAT INC">VAT INC</option>
                                    </select>
                                </div>
                                <div class="form-group"  v-if="overview.status == 'Received'">
                                    <label>Received Date</label>
                                    <input type="date" class="form-control form-control-sm"
                                           v-model="overview.updated_at.replace(' 00:00:00', '')">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Payment Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select type="text" class="form-control form-control-sm"
                                            v-model="overview.payment_method">
                                        <option value="">-- Select Options --</option>
                                        <option value="Check">Check</option>
                                        <option value="Cash">Cash</option>
                                        <option value="Credit">Credit</option>
                                        <option value="Online Banking">Online Banking</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Payment Status</label>
                                    <select type="text" class="form-control form-control-sm"
                                            v-model="overview.payment_status">
                                        <option value="">-- Select Options --</option>
                                        <option value="PAID">PAID</option>
                                        <option value="UNPAID">UNPAID</option>
                                        <option value="PAID WITH BALANCE">PAID WITH BALANCE</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" v-if="overview.payment_method == 'Check'">
                                    <label>Check Number</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="overview.check_number">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group" v-if="overview.payment_method == 'Check'">
                                    <label>Check Writer</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="overview.check_writer">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Address Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Billing Address</label>
                                    <textarea type="text" class="form-control form-control-sm" rows="5"
                                              v-model="overview.delivery_address" style="height: 80px;">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label>Delivery Address</label>
                                    <textarea type="text" class="form-control form-control-sm" rows="5"
                                              v-model="overview.billing_address" style="height: 80px;">
                                    </textarea>
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
                                            <td v-else colspan="7" style="background-color: rgba(61, 61, 61, 0.925);">
                                                <h5 style="margin-top: 5px;"><strong>@{{ product.category }}</strong>
                                                </h5>
                                            </td>
                                            <td v-if="product.product_name"><input type="text"
                                                                                   class="form-control form-control-sm"
                                                                                   style="width: 230px;"
                                                                                   v-model="product.notes"></td>
                                            <td v-if="product.product_name"><input type="text"
                                                                                   class="form-control form-control-sm"
                                                                                   style="width: 100px;"
                                                                                    v-model="product.qty"></td>
                                            <td v-if="product.product_name"><input type="text"
                                                                                   class="form-control form-control-sm"
                                                                                   style="width: 100px;"
                                                                                   v-model="product.selling_price"></td>
                                            <td v-if="product.product_name"><input type="text"
                                                                                   class="form-control form-control-sm"
                                                                                   style="width: 120px;"
                                                                                   v-model="product.vendor_price"></td>
                                            <td v-if="product.product_name"><input type="text"
                                                                                   class="form-control form-control-sm"
                                                                                   v-model="product.discount_item"></td>
                                            <td v-if="product.product_name">@{{ (product.vendor_price * product.qty) - ((product.vendor_price * product.qty)*(product.discount_item/100)) }}
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
                            <div class="offset-md-9 col-md-4">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Sub Total</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control-plaintext form-control-sm" v-model="summary.sub_total">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Discount</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control form-control-sm"
                                               v-model="summary.discount">
                                    </div>
                                </div>
                                <div class="form-group row" v-show="overview.vat_type == 'VAT INC'">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Sales Tax %</label>
                                    <div class="input-group col-md-4">
                                        <input type="number" class="form-control form-control-sm" v-model="summary.sales_tax">
                                        <div class="input-group-append">
                                          <span class="input-group-text" id="basic-addon2"><i class="fa fa-percentage"></i></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group row" v-show="overview.vat_type == 'VAT INC'">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Sales Tax</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control-plaintext form-control-sm"
                                               v-bind:value="summary.sales_actual">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Shipping</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control form-control-sm"
                                               v-model="summary.shipping">
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Grand Total</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control-plaintext form-control-sm"
                                            v-model="summary.grand_total">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Terms & Conditions</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea type="text" class="form-control form-control-sm"
                                                  v-model="overview.tac" rows="8"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Description Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-12">
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea type="text" class="form-control form-control-sm"
                                                  v-model="overview.description" rows="5"
                                                  style="height: 80px;"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a href="{{ route('purchase') }}" class="btn btn-warning">Back</a>
                                <button class="btn btn-info" v-if="viewType == 1" @click="store">Save New</button>
                                <button class="btn btn-primary" v-if="viewType == 0" @click="update">Update Now</button>
                                <a href="{{ route('purchase.print', isset($purchase_info->id)?$purchase_info->id: '') }}"
                                   class="btn btn-primary" v-if="viewType == 2">Purchase Order</a>
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
                    loading: false,
                    viewType: 0,
                    columns: [
                        'Product', 'Notes', 'Qty', 'Unit Cost', 'Vendor Price', 'Discount', 'Total', 'Action'
                    ],
                    overview: {!! $purchase_info !!},
                    products: {!! $product_details !!},
                    summary: {!! $summary !!},
                    selling_price: 0,
                    vendor_price: 0,
                }
            },
            watch: {
                'products': {
                    deep: true,
                    handler(products) {
                        console.log(products);
                        var $this = this;
                        var hold = 0;
                        $.each(products, function (x, product) {
                            if (product.product_name) {
                                hold += (product.vendor_price * product.qty)
                            }
                        });

                        $this.summary.sub_total = hold;
                        this.grandTotal()
                    }
                },
                'summary.discount': function(value){
                    this.grandTotal()
                },
                'summary.sales_tax': function(value){
                    this.grandTotal()
                },
                'summary.shipping': function(value){
                    this.grandTotal()
                },
            },
            methods: {
                grandTotal() {
                    var $this = this;
                    var sales_tax = parseFloat($this.summary.sales_tax);
                    $this.summary.sales_actual = 0;
                    $this.summary.grand_total = $this.summary.sub_total - $this.summary.discount
                    if($this.overview.vat_type == 'VAT INC') {
                        var hold = 0
                        hold = ($this.summary.grand_total * (1+(sales_tax/100)))
                        $this.summary.sales_actual = (hold - $this.summary.grand_total).toFixed(2)
                        $this.summary.grand_total = (hold).toFixed(2)
                    }
                    $this.summary.grand_total = parseFloat($this.summary.grand_total) + parseFloat($this.summary.shipping)
                },
                store() {
                    var $this = this;

                    if($this.summary.discount === '' || $this.summary.shipping === '') {
                        Swal.fire(
                            'Sorry! Please try again!',
                            'Discount and shipping is blank.',
                            'warning'
                        ).then((result) => {
                            if (result.value) {
                            }
                        });
                        return false;
                    }

                    $this.loading = true;
                    $.ajax({
                        url: '{{ route('purchase.store') }}',
                        method: 'POST',
                        data: {
                            overview: $this.overview,
                            products: $this.products,
                            summary: $this.summary,
                        },
                        success: function (value) {
                            $this.loading = false;
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
                update() {
                    var $this = this;
                    if($this.summary.discount === '' || $this.summary.shipping === '') {
                        Swal.fire(
                            'Sorry! Please try again!',
                            'Discount and shipping is blank.',
                            'warning'
                        ).then((result) => {
                            if (result.value) {
                            }
                        });
                        return false;
                    }
                    $this.loading = true;

                    $.ajax({
                        url: '{{ route('purchase.update') }}',
                        method: 'POST',
                        data: {
                            overview: $this.overview,
                            products: $this.products,
                            summary: $this.summary,
                        },
                        success: function (value) {
                            $this.loading = false;
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
                    var prod_id = $('.select2-product').find(':selected').val();
                    $.ajax({
                        url: '{{route('product.find')}}',
                        method: 'POST',
                        data: {
                            product_id: prod_id
                        },
                        success: function (value) {
                            $this.products.push(
                                {
                                    product_id: value.product_id,
                                    product_name: value.name,
                                    notes: '',
                                    quantity: value.quantity,
                                    qty: 0,
                                    selling_price: value.selling_price,
                                    labor_cost: 0,
                                    vendor_price: value.vendor_price,
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
                subTotal() {
                    var $this = this;
                    $this.summary.sub_total = '0';
                    var hold = 0;
                    $.each($this.products, function (x, product) {
                        if (product.product_name) {
                            hold += (product.vendor_price * product.qty)
                        }
                    });
                    $this.summary.sub_total = hold.toString();
                },
            },
            mounted() {
                var $this = this;
                $this.subTotal();

                $this.grandTotal();

                $('#select2-category').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('category.list') }}',
                        method: 'POST',
                        dataType: 'json'
                    }
                });

                $('.select2-product').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('product.list') }}',
                        method: 'POST',
                        dataType: 'json',
                        data: function (params) {
                            params.category = $this.getCurrentCategory();
                            return params;
                        }
                    }
                });

                $('.select2-vendor').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('vendor.list') }}',
                        method: 'POST',
                        dataType: 'json'
                    }
                }).on('select2:select', function (e) {
                    var data = e.params.data;
                    $this.overview.vendor_id = data.id;
                    $this.overview.shipping_method = data.shipping_method;
                    $this.overview.contact_name = data.contact_name;
                });

                var newOption = new Option($this.overview.vendor_name, $this.overview.vendor_id, true, true);
                $('.select2-vendor').append(newOption).trigger('change');

                if ('{{ Route::currentRouteName() }}' == 'purchase.detail') {
                    this.viewType = 0;
                    if ('{{ \App\Preference::verify('po_textbox') }}' == '0') {
                        $("[name='po_no']").addClass('form-control-plaintext').removeClass('form-control');
                        $("[name='po_no']").attr('readonly', 'readonly');
                    }
                }
                else if ('{{ Route::currentRouteName() }}' == 'purchase.create') {
                    this.viewType = 1;
                    if ('{{ \App\Preference::verify('po_textbox') }}' == '0') {
                        $("[name='po_no']").addClass('form-control-plaintext').removeClass('form-control');
                        $("[name='po_no']").attr('readonly', 'readonly');
                    }
                }
                else if ('{{ Route::currentRouteName() }}' == 'purchase.view') {
                    this.viewType = 2;
                    $('label').addClass('font-weight-bold');
                    $('.form-control').addClass('form-control-plaintext').removeClass('form-control');
                    $('.select2').attr('hidden', 'hidden');
                }
            }
        });
    </script>
@endsection
