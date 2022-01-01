@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">
        <!-- Content Row -->
        <div class="row">
            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Product Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                                <h4>Product Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" class="form-control form-control-sm name"
                                           v-model="overview.name">
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label>Is this Fast Moving?</label>
                                    <div class="dropdown show">
                                        <a class="btn btn-primary dropdown-toggle"
                                           href="#!" role="button" id="dropdownMenuLink" data-toggle="dropdown"
                                           aria-haspopup="true" aria-expanded="false">
                                            <span v-if="fast_moving"> @{{ fast_moving }}</span>
                                            <span v-else>No</span>
                                        </a>
                                        <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                                            <a class="dropdown-item" href="#" @click="setTag">Fast Moving Item</a>
                                            <a class="dropdown-item" href="#" @click="removeTag">No</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product Model</label>
                                    <input type="text" class="form-control form-control-sm" v-model="overview.code">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Category</label>
                                    <select class="form-control form-control-sm" v-model="overview.category">
                                        @foreach(\App\Category::all() as $value)
                                            <option value="{{ $value->name }}">{{ $value->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Brand</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="overview.manufacturer">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Unit of Measurement</label>
                                    <input type="text" class="form-control form-control-sm" v-model="overview.unit">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Vendor Price</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="overview.vendor_price">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Selling Price</label>
                                    <input type="text" class="form-control form-control-sm"
                                           v-model="overview.selling_price">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Product Type</label>
                                    <select class="form-control form-control-sm" v-model="overview.type">
                                        <option value="limited">Limited</option>
                                        <option value="unlimited">unlimited</option>
                                    </select>
                                </div>
                            </div>
                            @if(env('PRODUCT_BATCH') == 'show')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Batch</label>
                                        <input type="text" class="form-control form-control-sm"
                                               v-model="overview.batch">
                                    </div>
                                </div>
                            @endif
                            @if(env('PRODUCT_SIZE') == 'show')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Size</label>
                                        <input type="text" class="form-control form-control-sm" v-model="overview.size">
                                    </div>
                                </div>
                            @endif
                            @if(env('PRODUCT_WEIGHT') == 'show')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Weight</label>
                                        <input type="text" class="form-control form-control-sm"
                                               v-model="overview.weight">
                                    </div>
                                </div>
                            @endif
                            @if(env('PRODUCT_COLOR') == 'show')
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Color</label>
                                        <input type="text" class="form-control form-control-sm"
                                               v-model="overview.color">
                                    </div>
                                </div>
                            @endif
                            <div class="col-md-12">
                                <h4>Description Information</h4>
                                <hr>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>Description</label>
                                    <textarea rows="6" type="text" class="form-control form-control-sm"
                                              v-model="overview.description">
                                    </textarea>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <h4>Product Image</h4>
                                <hr>
                            </div>
                            <div class="col-md-4" v-if="viewType != 2">
                                <form>
                                    <div class="form-group">
                                        <label for="exampleFormControlFile1">Image Upload</label>
                                        <input type="file" class="form-control-file" name="image-file">
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12">
                                <div class="text-center" v-for="image in images">
                                    <img v-bind:src="'/app/public/' + image.path" class="rounded" width="200px">
                                </div>
                                <div v-if="images.length == 0" class="text-center">
                                    <img src="{{ asset('img/storage/product-placeholder.jpeg') }}" class="rounded"
                                         width="200px">
                                </div>
                            </div>
                            <div class="col-md-12">
                                <a href="{{ route('products') }}" class="btn btn-warning">Back</a>
                                <button class="btn btn-info" v-if="viewType == 1" @click="store">Save New</button>
                                <button class="btn btn-primary" v-if="viewType == 0" @click="update">Update Now</button>
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
                    viewType: 0,
                    overview: {!! $product !!},
                    images: {!! $gallery !!},
                    fast_moving: '',
                }
            },
            watch: {
                'overview.vendor_price': function () {
                    this.overview.selling_price = Math.round(parseFloat(this.overview.vendor_price) + parseFloat((this.overview.vendor_price * .20)))
                }
            },
            methods: {
                setTag() {
                    this.fast_moving = 'Fast Moving'
                },
                removeTag() {
                    this.fast_moving = null;
                },
                store() {
                    var $this = this;
                    $this.overview.fast_moving = $this.fast_moving;
                    $.ajax({
                        url: '{{ route('product.store') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function (value) {
                            $this.imageUpload(value.id);
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('products') }}'
                                }
                            })
                        }
                    })
                },
                update() {
                    var $this = this;
                    $this.overview.fast_moving = $this.fast_moving;
                    $.ajax({
                        url: '{{ route('product.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function (value) {
                            $this.imageUpload($this.overview.id);
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('products') }}'
                                }
                            })
                        }
                    });
                },
                imageUpload(id) {
                    var formData = new FormData();
                    formData.append('id', id);
                    formData.append('image', $('input[type=file]')[0].files[0]);
                    $.ajax({
                        url: '{{ route('product.image.upload') }}',
                        method: 'POST',
                        data: formData,
                        contentType: false,
                        processData: false,
                        success: function (value) {

                        }
                    });
                }
            },
            mounted() {
                var $this = this;
                $this.fast_moving = this.overview.tags ? this.overview.tags[0]['name']['en'] : '';
                if ('{{ Route::currentRouteName() }}' == 'product.detail') {
                    this.viewType = 0;
                    $('.name').addClass('form-control-plaintext').removeClass('form-control');
                } else if ('{{ Route::currentRouteName() }}' == 'product.create') {
                    this.viewType = 1;
                } else if ('{{ Route::currentRouteName() }}' == 'product.view') {
                    this.viewType = 2;
                    $('label').addClass('font-weight-bold');
                    $('.form-control').addClass('form-control-plaintext').removeClass('form-control');
                }
            }
        });
    </script>
@endsection
