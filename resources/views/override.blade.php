@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Override</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12 mt-3">
                                <h4>Data Wipe Out</h4>
                                <hr>
                            </div>
                            <div class="row col-md-12 mt-2">
                                <div class="col-md-12">
                                    <form method="POST" class="row" action="{{ route('override.wipe') }}">
                                        @csrf
                                        <div class="col-md-3">
                                            <button type="submit" class="btn btn-block btn-danger">
                                                <i class="fas fa-trash"></i>
                                                Wipe this data
                                            </button>
                                        </div>
                                        <div class="col-md-5">
                                            <select name="db" class="form-control">
                                                <option value="" selected>
                                                    <strong>-- Select Data --</strong>
                                                </option>
                                                <option value="po">
                                                    <strong>Purchase Orders</strong>
                                                </option>
                                                <option value="so">
                                                    <strong>Sales Orders</strong>
                                                </option>
                                                <option value="customers">
                                                    <strong>Customers</strong>
                                                </option>
                                                <option value="products_inventory">
                                                    <strong>Products and Inventory</strong>
                                                </option>
                                                <option value="vendors">
                                                    <strong>Vendors</strong>
                                                </option>
                                                <option value="expenses">
                                                    <strong>Expenses</strong>
                                                </option>
                                                <option value="supplies">
                                                    <strong>Supplies</strong>
                                                </option>
                                                <option value="job_order">
                                                    <strong>Job Order</strong>
                                                </option>
                                            </select>
                                        </div>
                                    </form>
                                </div>
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
                return {};
            },
            methods: {
                addCategory() {
                },
                deleteCategory(idx) {
                },
                destroy() {
                }
            },
            mounted() {
            }
        });
    </script>
@endsection
