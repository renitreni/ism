@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">
            <div class="row">
                <!-- Total Assets -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-primary shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Assets</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($assets,2,'.','') }}</div>
                        </div>
                        <div class="col-auto">
                                <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
    
                <!-- Product Stocks -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-success shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Product Stocks</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $stocks }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-boxes fa-2x text-gray-300"></i>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
    
                <!-- Earnings (Monthly) Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-info shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Total PO</div>
                        <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $po_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
    
                <!-- Pending Requests Card Example -->
                <div class="col-xl-3 col-md-6 mb-4">
                    <div class="card border-left-warning shadow h-100 py-2">
                    <div class="card-body">
                        <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">Total SO</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800"> {{ $so_count }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-clipboard-list fa-2x text-gray-300"></i>
                        </div>
                        </div>
                    </div>
                    </div>
                </div>
                </div>
        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">In Stock</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-in-stock" class="table table-striped nowrap" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Out of Stock</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-out-of-stock" class="table table-striped nowrap" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">PO in Ordered Status</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-po" class="table table-striped nowrap" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">SO Quotation</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-so" class="table table-striped nowrap" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Returned SO</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-12">
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-so-returned" class="table table-striped nowrap" style="width:100%"></table>
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
    mounted() {
        var $this = this;
        $this.dt = $('#table-in-stock').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            responsive: true,
            lengthChange: false,
            order: [[0, 'desc']],
            pageLength: 5,
            ajax: {
                url: "{{ route('home.instock') }}",
                method: "POST",
            },
            columns: [
                {data: 'id', name:'supplies.id', title: 'ID'},
                {data: 'product_name', name:"products.name", title: 'Product'},
                {data: 'quantity', title: 'Quantity'},
            ],
            drawCallback: function () {
                $('table .btn').on('click', function(){
                    let data = $(this).parent().parent().parent();
                    let hold = $this.dt.row(data).data();
                    $this.overview = hold;
                    console.log(hold);
                });
            }
        });


        $this.dt = $('#table-out-of-stock').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            responsive: true,
            lengthChange: false,
            order: [[0, 'desc']],
            pageLength: 5,
            ajax: {
                url: "{{ route('home.outofstock') }}",
                method: "POST",
            },
            columns: [
                {data: 'id', name:'supplies.id', title: 'ID'},
                {data: 'product_name', name:"products.name", title: 'Product'},
                {data: 'quantity', title: 'Quantity'},
            ],
            drawCallback: function () {
                $('table .btn').on('click', function(){
                    let data = $(this).parent().parent().parent();
                    let hold = $this.dt.row(data).data();
                    $this.overview = hold;
                    console.log(hold);
                });
            }
        });

        $this.dt = $('#table-po').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            responsive: true,
            lengthChange: false,
            order: [[0, 'desc']],
            pageLength: 5,
            ajax: {
                url: "{{ route('home.po') }}",
                method: "POST",
            },
            columns: [
                {data: 'id', name:'id', title: 'ID'},
                {data: 'subject', name:"subject", title: 'Subject'},
                {data: 'status', title: 'Status'},
            ],
            drawCallback: function () {
                $('table .btn').on('click', function(){
                    let data = $(this).parent().parent().parent();
                    let hold = $this.dt.row(data).data();
                    $this.overview = hold;
                    console.log(hold);
                });
            }
        });

        $this.dt = $('#table-so-returned').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            responsive: true,
            lengthChange: false,
            order: [[0, 'desc']],
            pageLength: 5,
            ajax: {
                url: "{{ route('home.returned') }}",
                method: "POST",
            },
            columns: [
                {data: 'id', name:'id', title: 'ID'},
                {data: 'subject', name:"subject", title: 'Subject'},
                {data: 'status', title: 'Status'},
            ],
            drawCallback: function () {
                $('table .btn').on('click', function(){
                    let data = $(this).parent().parent().parent();
                    let hold = $this.dt.row(data).data();
                    $this.overview = hold;
                    console.log(hold);
                });
            }
        });
        $this.dt = $('#table-so').DataTable({
            processing: true,
            serverSide: true,
            scrollX: true,
            responsive: true,
            lengthChange: false,
            order: [[0, 'desc']],
            pageLength: 5,
            ajax: {
                url: "{{ route('home.so') }}",
                method: "POST",
            },
            columns: [
                {data: 'id', name:'id', title: 'ID'},
                {data: 'subject', name:"subject", title: 'Subject'},
                {data: 'status', title: 'Status'},
            ],
            drawCallback: function () {
                $('table .btn').on('click', function(){
                    let data = $(this).parent().parent().parent();
                    let hold = $this.dt.row(data).data();
                    $this.overview = hold;
                    console.log(hold);
                });
            }
        });
    }
});
</script>
@endsection