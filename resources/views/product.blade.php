@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Products Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-auto">
                                <div class="btn-group" role="group" aria-label="Basic example">
                                    <a href="{{ route('product.create') }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-plus"></i> New Product</a>
                                    <a href="#" class="btn btn-sm btn-info" data-toggle="modal"
                                       data-target="#categoryModal">
                                        <i class="fa fa-list-ol"></i> Categories</a>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-product" class="table table-striped" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div id="categoryModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Product Category</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="input-group mb-3">
                                    <input type="text" class="form-control" placeholder="Insert new Category..."
                                           aria-label="Recipient's username" v-model="category_new"
                                           aria-describedby="button-addon2">
                                    <div class="input-group-append">
                                        <button class="btn btn-success" @click="addCategory" type="button"
                                                id="button-addon2">
                                            <i class="fa fa-plus-circle"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12">
                                <ul class="list-group">
                                    <li v-for="(category, idx) in categories"
                                        class="list-group-item d-flex justify-content-between align-items-center bg-dark">
                                        @{{ category }}
                                        @can('productsdestroy')
                                            <button class="btn btn-sm btn-danger" @click="deleteCategory(idx)"><i
                                                        class="fa fa-times"></i>
                                            </button>
                                        @endcan
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
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
                    overview: {id: ''},
                    categories: {!! $category !!},
                    category_new: ''
                }
            },
            methods: {
                addCategory() {
                    var $this = this;
                    $.ajax({
                        url: "{{ route('category.store') }}",
                        method: 'POST',
                        data: {category: $this.category_new},
                        success(value) {
                            $this.categories = value.categories;
                            $this.category_new = '';
                            Swal.fire('Success!', 'A category has been added.', 'success');
                        }
                    });
                },
                deleteCategory(idx) {
                    var $this = this;
                    $this.categories.splice(idx, 1);
                    $.ajax({
                        url: "{{ route('category.delete') }}",
                        method: 'POST',
                        data: {category: $this.categories},
                        success(value) {
                            $this.categories = value.categories;
                            Swal.fire('Deleted!', 'A category has been deleted.', 'success');
                        }
                    });
                },
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
                                url: "{{ route('product.destroy') }}",
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
                $this.dt = $('#table-product').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    pageLength: 100,
                    order: [[1, 'desc']],
                    ajax: {
                        url: "{{ route('product.table') }}",
                        method: "POST",
                    },
                    columns: [
                        {
                            data: function (value) {
                                return '<div class="btn-group btn-group-sm shadow-sm" role="group" aria-label="Basic example">' +
                                    '<a href="/product/view/' + value.id + '" class="btn btn-primary btn-view"><i class="fa fa-eye"></i></a>' +
                                    '<a href="/product/detail/' + value.id + '" class="btn btn-info btn-view"><i class="fa fa-pen"></i></a>' +
                                    '<button type="button" class="btn btn-danger btn-destroy"><i class="fa fa-trash"></i></button>' +
                                    '</div>'
                            },
                            searchable: false,
                            bSortable: false,
                            title: 'Action'
                        },
                        {data: 'name', name: 'products.name', title: 'Name',width: '30%'},
                        // {data: 'code', name: 'products.code', title: 'Product Model'},
                        {data: 'selling_price', name: 'products.selling_price', title: 'Selling Price'},
                        @if(env('PRODUCT_BATCH_COL') == 'show')
                        {data: 'batch', name: 'products.batch', title: 'Batch No.'},
                        @endif
                        // {data: 'manufacturer', name: 'products.manufacturer', title: 'Brand'},
                        {data: 'category', name: 'products.category', title: 'Category'},
                        {data: 'username', name: 'users.name', title: 'Created By'},
                        {
                            data: 'created_at',
                            name: 'products.created_at',
                            title: 'Created At',
                            render: function (data) {
                                if (data !== null) {
                                    return new Date(data).toLocaleString('en-US', {
                                        year: 'numeric',
                                        month: 'short',
                                        day: 'numeric',
                                        hour: '2-digit',
                                        minute: '2-digit',
                                        second: '2-digit'
                                    });
                                } else {
                                    return '';
                                }
                            }
                        },
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
