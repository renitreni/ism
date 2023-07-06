@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Role Details</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-auto">
                                <div class="form-group">
                                    <label>Role</label>
                                    <input class="form-control form-control-sm" v-model="role">
                                </div>
                            </div>
                            {{-- BATCH PROCESS --}}
                            @if (env('SECTION_BATCHING') == 'show')
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Order Form</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.orderform">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Order Form</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.orderformcreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.orderformretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.orderformdestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Process Order --}}
                            @if (env('SECTION_INVENTORY') == 'show')
                                {{-- Purchase Order --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Purchase Order</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.purchaseorder">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Purchase Order</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.purchaseordercreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.purchaseorderretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.purchaseorderupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.purchaseorderdestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.purchasestatusupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Status Update</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Sales Order --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Sales Order</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salesorder">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Sales Order</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salesordercreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salesorderretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salesorderupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salesorderdestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salesstatusupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Status Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salespaymentupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Status Payment</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salesvatupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Status Vat</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.salesdeliveryupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Status Delivery</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Customer --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Customer</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.customers">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Customer</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.customercreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.customerretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.customerupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.customerdestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Vendors --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Vendors</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.vendors">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Vendors</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.vendorscreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.vendorsretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.vendorsupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.vendorsdestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Supplies --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Supplies</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.supplies">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Supplies</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.suppliesoverride">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Override</label>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                {{-- Products --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Products</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.products">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Products</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.productscreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.productsretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.productsupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.productsdestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Roles --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Roles</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.securityview">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Roles</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.securitycreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.securityretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.securityupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.securitydestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- User Accounts --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>User Accounts</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.useraccounts">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">User Accounts</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.useraccountscreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.useraccountschangepass">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Change Pass</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.useraccountsupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.useraccountsdestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.userassign">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">User Assign</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Preference --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Preference</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.preference">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Preference</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.override">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Override</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Proce List --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Price List</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.pricelist">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Price List</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.pricelistupload">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Upload</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.pricelistdestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Product Return --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Product Return</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.productreturn">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Product Return</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.productreturncreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.productreturndelete">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Audit Logs --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Audit Logs</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.auditlogs">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Audit Logs</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Expenses --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Expenses</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.expenses">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Expenses</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.expensescreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Expenses Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.expensesupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Expenses Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.expensesdelete">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Expenses Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                {{-- Quote --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Quote</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.quote">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Quote</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.quotecreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Quote Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.quoteretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Quote Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.quoteupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Quote Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.quotedestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Quote Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                                {{-- Job Order --}}
                                <div class="col-md-12 row">
                                    <div class="col-md-12 mt-4">
                                        <h3>Job Order</h3>
                                        <hr>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.joborder">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Job Order</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.jobordercreate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Job Order Create</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.joborderretrieve">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Job Order Retrieve</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.joborderupdate">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Job Order Update</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group row">
                                            <div class="col-md-auto">
                                                <label class="switch">
                                                    <input type="checkbox" v-model="abilities.quotedestroy">
                                                    <span class="slider"></span>
                                                </label>
                                            </div>
                                            <div class="col-md-auto">
                                                <label class="switch-label">Quote Delete</label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <div class="col-md-12">
                                <a href="{{ route('role') }}" class="btn btn-warning">Back</a>
                                <button class="btn btn-success" v-if="viewType != 2" @click="store">Save</button>
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
                    role: '{!! $role !!}',
                    abilities: {!! $abilities !!},
                }
            },
            methods: {
                store() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('role.store') }}',
                        method: 'POST',
                        data: {
                            role: $this.role,
                            abilities: $this.abilities
                        },
                        success: function(value) {
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('role') }}'
                                }
                            })
                        }
                    })

                },
                update() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('role.abilities') }}',
                        method: 'POST',
                        data: {
                            role: $this.role,
                            abilities: $this.abilities
                        },
                        success: function(value) {
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('role') }}'
                                }
                            })
                        }
                    })
                },
            },
            mounted() {
                var $this = this;

                if ('{{ Route::currentRouteName() }}' == 'role.detail') {
                    this.viewType = 0;
                } else if ('{{ Route::currentRouteName() }}' == 'role.create') {
                    this.viewType = 1;
                } else if ('{{ Route::currentRouteName() }}' == 'role.view') {
                    this.viewType = 2;
                    $('label').addClass('font-weight-bold');
                    $('.form-control').addClass('form-control-plaintext').removeClass('form-control');
                }
            }
        });
    </script>
@endsection
