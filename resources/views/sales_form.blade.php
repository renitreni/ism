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
        @include('partials.loading')
        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Sales Order Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Sales Order Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Subject</label>
                                    <input type="text" class="form-control form-control-sm" v-model="overview.subject">
                                </div>
                                <div class="form-group">
                                    <label>Phone</label>
                                    <input type="text" class="form-control form-control-sm" v-model="overview.phone">
                                </div>
                                <div class="form-group">
                                    <label>Agent</label>
                                    <select class="form-control form-control-sm select2-agent">
                                    </select>
                                    <input v-show="viewType == 2" type="text" class="form-control form-control-sm"
                                        v-model="overview.agent">
                                </div>
                                <div class="form-group">
                                    <label>Date of Purchased</label>
                                    <input type="date" class="form-control form-control-sm" v-model="overview.due_date">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Sales Order</label>
                                    <input type="text" name="so_no" readonly class="form-control form-control-sm"
                                        v-model="overview.so_no">
                                </div>
                                <div class="form-group">
                                    <label>Fax</label>
                                    <input type="text" class="form-control form-control-sm" v-model="overview.fax">
                                </div>
                                <div class="form-group">
                                    <label>Customer Name</label>
                                    <select class="form-control form-control-sm select2-customer">
                                    </select>
                                    <input v-show="viewType == 2" type="text" class="form-control form-control-sm"
                                        v-model="overview.customer_name">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>VAT Type</label>
                                    <select type="text" class="form-control form-control-sm" v-model="overview.vat_type"
                                        v-on:change="grandTotal(parseFloat(summary.sub_total) - parseFloat(summary.discount))">
                                        <option value="">-- Select Options --</option>
                                        <option value="VAT EX">VAT EX</option>
                                        <option value="VAT INC">VAT INC</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Status</label>
                                    <select type="text" class="form-control form-control-sm" v-model="overview.status">
                                        <option value="Quote">Quote</option>
                                        <option value="Sales">Sales</option>
                                        <option value="Project">Project</option>
                                    </select>
                                </div>
                                {{--                                <div class="form-group"> --}}
                                {{--                                    <label>Delivery Status</label> --}}
                                {{--                                    <select type="text" class="form-control form-control-sm" v-model="overview.delivery_status"> --}}
                                {{--                                        <option value="Not Shipped">Not Shipped</option> --}}
                                {{--                                        @can('salesstatusupdate') --}}
                                {{--                                            <option value="Shipped">Shipped</option> --}}
                                {{--                                        @endcan --}}
                                {{--                                    </select> --}}
                                {{--                                </div> --}}
                                <div class="form-group" v-if="overview.status == 'Shipped'">
                                    <label>Shipped Date</label>
                                    <input type="date" class="ui-datepicker form-control form-control-sm"
                                        v-model="overview.updated_at.replace(' 00:00:00', '')">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Sales Order Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Account Name</label>
                                    <input type="text" class="form-control form-control-sm"
                                        v-model="overview.account_name">
                                </div>
                                <div class="form-group">
                                    <label>Account No.</label>
                                    <input type="text" class="form-control form-control-sm"
                                        v-model="overview.account_no">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Payment Method</label>
                                    <select class="form-control" v-model="overview.payment_method">
                                        @foreach ($paymentMethods as $methods)
                                            <option value="{{ $methods->name }}">{{ $methods->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                {{--                                <div class="form-group"> --}}
                                {{--                                    <label>Payment Status</label> --}}
                                {{--                                    <select type="text" class="form-control form-control-sm" --}}
                                {{--                                            v-model="overview.payment_status"> --}}
                                {{--                                        <option value="PAID">PAID</option> --}}
                                {{--                                        <option value="UNPAID">UNPAID</option> --}}
                                {{--                                        <option value="PAID WITH BALANCE">PAID WITH BALANCE</option> --}}
                                {{--                                    </select> --}}
                                {{--                                </div> --}}
                            </div>
                            {{-- <div class="col-md-12">
                                <h4>Address Information</h4>
                                <hr>
                            </div> --}}
                            {{-- <div class="col-md-6">
                                <div class="form-group">
                                    <label>Location of Service</label>
                                    <textarea type="text" class="form-control form-control-sm" rows="5"
                                              v-model="overview.address" style="height: 80px;">
                                    </textarea>
                                </div>
                            </div> --}}
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
                                            <th v-for="column in columns" v-if="column != 'Action' || viewType != 2">
                                                @{{ column }}
                                            </th>
                                        </thead>
                                        <tbody>
                                            <tr v-for="(product, index) in products">
                                                <td v-if="product.product_name">
                                                    <input readonly type="text"
                                                        class="form-control-plaintext form-control-sm"
                                                        style="width: 180px;" v-model="product.product_name">
                                                </td>
                                                <td v-else colspan="7"
                                                    style="background-color: rgba(61, 61, 61, 0.925);">
                                                    <h5 style="margin-top: 5px;"><strong>@{{ product.category }}</strong>
                                                    </h5>
                                                </td>
                                                <td v-if="product.product_name">
                                                    <input type="text" class="form-control-plaintext form-control-sm"
                                                        style="width: 100px;" v-model="product.quantity">
                                                </td>
                                                <td v-if="product.product_name">
                                                    <input type="number" class="form-control form-control-sm"
                                                        style="width: 100px;" v-model="product.qty">
                                                </td>
                                                <td v-if="product.product_name">
                                                    <input type="number" class="form-control form-control-sm"
                                                        style="width: 100px;" v-model="product.selling_price">
                                                </td>
                                                {{-- <td v-if="product.product_name">
                                                    <input type="text" class="form-control form-control-sm"
                                                        style="width: 100px;" v-model="product.discount_item">
                                                </td> --}}
                                                <td v-if="product.product_name">
                                                    <input type="text" class="form-control form-control-sm"
                                                        style="width: 100px;" v-model="product.notes">
                                                </td>
                                                <td v-if="product.product_name">
                                                    <input readonly type="text"
                                                        class="form-control-plaintext form-control-sm total-grid"
                                                        style="width: 100px;"
                                                        v-bind:value="(product.selling_price * product.qty)">
                                                </td>
                                                <td v-if="viewType != 2">
                                                    <button class="btn btn-sm btn-block btn-danger"
                                                        @click="remove(index)">
                                                        <i class="fa fa-ban"></i></button>
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <div class="col-md-auto">
                                        <label class="switch">
                                            <input type="checkbox" v-model="displayShipping">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="col-md-auto">
                                        <label class="switch-label">Shipping</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-auto">
                                        <label class="switch">
                                            <input type="checkbox" v-model="displayDiscount">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="col-md-auto">
                                        <label class="switch-label">Discount</label>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <div class="col-md-auto">
                                        <label class="switch">
                                            <input type="checkbox" v-model="displayVat">
                                            <span class="slider"></span>
                                        </label>
                                    </div>
                                    <div class="col-md-auto">
                                        <label class="switch-label">Vat</label>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-4"></div>
                            <div class="col-md-4">
                                <div class="form-group row">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Sub Total</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control-plaintext form-control-sm"
                                            v-model="summary.sub_total">
                                    </div>
                                </div>
                                <div class="form-group row"  v-show="displayShipping">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Shipping</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control form-control-sm"
                                            v-model="summary.shipping">
                                    </div>
                                </div>
                                <div class="form-group row"  v-show="displayDiscount">
                                 <label class="col-form-label col-md-4 col-form-label-sm">Discount</label>
                                    <div class="col-md-4">
                                        <input type="number" class="form-control form-control-sm"
                                                v-model="summary.discount">
                                    </div>
                                </div>
                                {{-- <div class="form-group row" v-show="displayVat">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Sales Tax %</label>
                                    <div class="input-group col-md-4">
                                        <input type="number"class="form-control form-control-sm"
                                            v-model="summary.sales_tax">
                                        <div class="input-group-append">
                                            <span class="input-group-text"  id="basic-addon2"><i
                                                    class="fa fa-percentage"></i></span>
                                        </div>
                                    </div>
                                </div> --}}
                                <div class="form-group row" v-show="displayVat">
                                    <label class="col-form-label col-md-4 col-form-label-sm">Sales Tax</label>
                                    <div class="col-md-4">
                                        <input type="text" class="form-control-plaintext form-control-sm"
                                            v-bind:value="summary.sales_actual">
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
                            <div class="col-md-6">
                                <h4>Terms & Conditions</h4>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea type="text" class="form-control form-control-sm" v-model="overview.tac" rows="8"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <h4>Warranty Policy</h4>
                                <hr>
                                <div class="form-group row">
                                    <div class="col-md-12">
                                        <textarea type="text" class="form-control form-control-sm" v-model="overview.warranty" rows="8"></textarea>
                                    </div>
                                </div>

                            </div>
                            <div class="col-md-12">
                                <a href="{{ URL::previous() }}" class="btn btn-warning">Back</a>
                                <button class="btn btn-info" v-if="viewType == 1" @click="store">Save New</button>
                                <button class="btn btn-primary" v-if="viewType == 0" @click="update">Update Now</button>
                                <a href="{{ route('sales.print', isset($sales_order->id) ? $sales_order->id : '') }}"
                                    class="btn btn-primary" v-if="viewType == 2">Warranty Slip</a>
                                <a href="{{ route('sales.quote', isset($sales_order->id) ? $sales_order->id : '') }}"
                                    class="btn btn-info" v-if="viewType == 2">Quote</a>
                                <a href="{{ route('sales.deliver', isset($sales_order->id) ? $sales_order->id : '') }}"
                                    class="btn btn-success" v-if="viewType == 2">Delivery Receipt</a>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="productModal" class="modal" role="dialog">
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
                                <form>
                                    <div class="form-group">
                                        <label class="control-label">Products</label>
                                        <select class="form-control select2-product">
                                        </select>
                                    </div>
                                </form>
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

        <div id="categoryModal" class="modal" role="dialog">
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
                                <form>
                                    <div class="form-group">
                                        <label class="control-label">Category</label>
                                        <select class="form-control select2-category">
                                        </select>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" data-dismiss="modal"
                            @click="addCategory()">Insert
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
                    displayDiscount: false,
                    displayShipping: false,
                    displayVat: false,
                    loading: false,
                    viewType: 0,
                    columns: [
                        'Product', 'Stock', 'Qty', 'Unit Cost', 'Serial No.', 'Total Cost', 'Action'
                    ],
                    sub_total: 0,
                    overview: {!! $sales_order !!},
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
                        $.each(products, function(x, product) {
                            if (product.product_name) {
                                hold += (product.selling_price * product.qty)
                            }
                        });

                        $this.summary.sub_total = hold;
                        this.grandTotal()
                    }
                },
                displayDiscount(newValue) {
                    if (!newValue) {
                        this.summary.discount = 0;
                    }
                },

                displayShipping(newValue) {
                    if (!newValue) {
                        this.summary.shipping = 0;
                    }
                },
                displayVat(newValue) {
                    this.summary.sales_tax = 12;
                    if (!newValue) {
                        this.summary.sales_tax = 0;
                    }
                },
                'summary.discount': function(value) {
                    this.grandTotal()
                },
                'summary.sales_tax': function(value) {
                    this.grandTotal()
                },
                'summary.shipping': function(value) {
                    this.grandTotal()
                },
            },
            methods: {

                grandTotal() {

                    var $this = this;
                    var sales_tax = parseFloat($this.summary.sales_tax);
                    $this.summary.sales_actual = 0;
                    $this.summary.grand_total = parseFloat($this.summary.sub_total) + parseFloat($this.summary.shipping) - parseFloat($this.summary.discount) ;
                    if ($this.overview.vat_type == 'VAT INC' || $this.overview.vat_type == 'VAT EX' ) {
                        var hold = 0
                        hold = ($this.summary.grand_total  * (1 + (sales_tax / 100)))
                        $this.summary.sales_actual = (hold - $this.summary.grand_total).toFixed(2)
                        $this.summary.grand_total = (hold).toFixed(2)
                    }
                    // $this.summary.grand_total = parseFloat($this.summary.grand_total) + parseFloat($this.summary.shipping)
                    $this.summary.grand_total = parseFloat($this.summary.grand_total)
                },
                store() {
                    var $this = this;
                    if ($this.summary.discount === '' || $this.summary.shipping === '') {
                        Swal.fire(
                            'Sorry! Please try again!',
                            'Discount and shipping is blank.',
                            'warning'
                        ).then((result) => {});
                        return false;
                    }
                    $this.loading = true;

                    $.ajax({
                        url: '{{ route('sales.store') }}',
                        method: 'POST',
                        data: {
                            overview: $this.overview,
                            products: $this.products,
                            summary: $this.summary,
                        },
                        success: function(value) {
                            $this.loading = false;
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    if ($this.overview.status == 'Quote') {
                                        window.location = '{{ route('quote') }}'
                                    } else {
                                        window.location = '{{ route('sales') }}'
                                    }
                                }
                            })
                        }
                    })
                },
                update() {
                    var $this = this;

                    if ($this.summary.discount === '' || $this.summary.shipping === '') {
                        Swal.fire(
                            'Sorry! Please try again!',
                            'Discount and shipping is blank.',
                            'warning'
                        ).then((result) => {
                            if (result.value) {}
                        });
                        return false;
                    }
                    $this.loading = true;
                    $.ajax({
                        url: '{{ route('sales.update') }}',
                        method: 'POST',
                        data: {
                            overview: $this.overview,
                            products: $this.products,
                            summary: $this.summary,
                        },
                        success: function(value) {
                            $this.loading = false;
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if ($this.overview.status == 'Quote') {
                                    window.location = '{{ route('quote') }}'
                                } else {
                                    window.location = '{{ route('sales') }}'
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
                        url: '{{ route('product.find') }}',
                        method: 'POST',
                        data: {
                            product_id: $('.select2-product').find(':selected').val()
                        },
                        success: function(value) {
                            $this.selling_price = parseFloat(value.selling_price);
                            $this.vendor_price = parseFloat(value.vendor_price);
                            $this.quantity = parseInt(value.quantity);
                            $this.products.push({
                                product_id: $('.select2-product').find(':selected').val(),
                                product_name: $('.select2-product').find(':selected').text(),
                                notes: '',
                                qty: 0,
                                quantity: $this.quantity,
                                selling_price: $this.selling_price,
                                vendor_price: $this.vendor_price,
                                discount_item: 0
                            });
                            $('.select2-product').val(null).trigger('change');
                        }
                    });
                },
                addCategory() {
                    var $this = this;
                    $this.products.push({
                        category: $('.select2-category').find(':selected').text(),
                    });
                    $('.select2-category').val(null).trigger('change');
                },
                getCurrentCategory() {
                    var category = '';
                    var $this = this;
                    $.each($this.products, function(x, y) {
                        category = y.category
                    });

                    return category;
                },
                subTotal() {
                    var $this = this;
                    $this.summary.sub_total = 0;
                    $.each($this.products, function(x, product) {
                        if (product.product_name) {
                            $this.summary.sub_total += (product.selling_price * product.qty) + parseFloat(
                                product.discount_item)
                        }
                    });
                    $this.summary.grand_total = $this.summary.sub_total - $this.summary.discount;
                }
            },
            mounted() {
                var $this = this;

                if(this.summary.discount != 0){
                    this.displayDiscount = true;
                }

                if(this.summary.shipping != 0){
                    this.displayShipping = true;
                }

                if(this.summary.sales_tax != 0){
                    this.displayVat = true;
                }
                //$this.grandTotal();
                $('.select2-category').select2({
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
                        data: function(params) {
                            params.category = $this.getCurrentCategory();
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
                }).on('select2:select', function(e) {
                    var data = e.params.data;
                    $this.overview.customer_id = data.id;
                    $this.overview.phone = data.phone;
                    $this.overview.address = data.address;
                });

                $('.select2-agent').select2({
                    width: '100%',
                    ajax: {
                        url: '{{ route('user.list') }}',
                        method: 'POST',
                        dataType: 'json'
                    }
                }).on('select2:select', function(e) {
                    var data = e.params.data;
                    $this.overview.agent = data.text;
                });

                var newOption = new Option($this.overview.customer_name, $this.overview.customer_id, true, true);
                $('.select2-customer').append(newOption).trigger('change');

                var newOption = new Option($this.overview.agent, $this.overview.agent, true, true);
                $('.select2-agent').append(newOption).trigger('change');

                if ('{{ Route::currentRouteName() }}' == 'sales.detail') {
                    this.viewType = 0;
                    if ('{{ \App\Preference::verify('so_textbox') }}' == '0') {
                        $("[name='so_no']").addClass('form-control-plaintext').removeClass('form-control');
                        $("[name='so_no']").attr('readonly', 'readonly');
                    }
                } else if ('{{ Route::currentRouteName() }}' == 'sales.create') {
                    this.viewType = 1;
                    if ('{{ \App\Preference::verify('so_textbox') }}' == '0') {
                        $("[name='so_no']").addClass('form-control-plaintext').removeClass('form-control');
                        $("[name='so_no']").attr('readonly', 'readonly');
                    }
                } else if ('{{ Route::currentRouteName() }}' == 'sales.view') {
                    this.viewType = 2;
                    $('label').addClass('font-weight-bold');
                    $('.form-control').attr('readonly', 'readonly');
                    $('.form-control').addClass('form-control-plaintext').removeClass('form-control');
                    $('.select2').attr('hidden', 'hidden');
                }

            }
        });
    </script>
@endsection
