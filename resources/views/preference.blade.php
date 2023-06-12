@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Preference</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Payment Methods --}}
                            @livewire('component.payment-method-livewire')
                            {{--Editables--}}
                            <div class="col-md-12 mt-3 row">
                                <div class="col-md-12">
                                    <h3>Editables</h3>
                                    <hr>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" v-model="overview.so_textbox">
                                        <label class="form-check-label">SO Textbox</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" v-model="overview.po_textbox">
                                        <label class="form-check-label">PO Textbox</label>
                                    </div>
                                </div>
                            </div>
                            {{--Auto ID Generated--}}
                            <div class="col-md-12 mt-3 row">
                                <div class="col-md-12">
                                    <h3>Auto Generated</h3>
                                    <hr>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" v-model="overview.so_auto">
                                        <label class="form-check-label">SO No.</label>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group form-check">
                                        <input type="checkbox" class="form-check-input" v-model="overview.po_auto">
                                        <label class="form-check-label">PO No.</label>
                                    </div>
                                </div>
                            </div>
                            {{--Auto Fills--}}
                            <div class="col-md-12 mt-3 row">
                                <div class="col-md-12">
                                    <h3>Auto Fills</h3>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Billing Address</label>
                                        <textarea class="form-control" v-model="overview.billing_address_fill"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Delivery Address</label>
                                        <textarea class="form-control" v-model="overview.delivery_address_fill"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Company Details</label>
                                        <textarea class="form-control" v-model="overview.company_details_fill"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Terms and Conditions SO</label>
                                        <textarea class="form-control" v-model="overview.tac_so_fill"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Terms and Conditions PO</label>
                                        <textarea class="form-control" v-model="overview.tac_po_fill"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Account Name</label>
                                        <textarea class="form-control" v-model="overview.account_name"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Account No</label>
                                        <textarea class="form-control" v-model="overview.account_no"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>TIN No.</label>
                                        <textarea class="form-control" v-model="overview.tin_no"></textarea>
                                    </div>
                                </div>
                            </div>
                            {{--General--}}
                            <div class="col-md-12 mt-3 row">
                                <div class="col-md-12">
                                    <h3>General Information</h3>
                                    <hr>
                                </div>
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Company Name</label>
                                        <input type="text" class="form-control" v-model="overview.company_name"/>
                                    </div>
                                </div>
                                @can('subscription')
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Expiration</label>
                                        <input type="date" class="form-control" v-model="overview.expiration"/>
                                    </div>
                                </div>
                                @endcan
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
                    overview: {!!  $build !!},
                }
            },
            methods: {
                update() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('preference.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function (value) {

                        }
                    });
                }
            },
            watch: {
                'overview': {
                    deep: true,
                    handler() {
                        this.update()
                    }
                }
            },
            mounted() {
            }
        });
    </script>
@endsection
