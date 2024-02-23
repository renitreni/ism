<ul class="navbar-nav bg-gradient-warning sidebar sidebar-dark accordion" id="accordionSidebar">
    @php
        $logoData = \App\PrintSetting::find(1); // Adjust the query as needed
        if($logoData){
            $logoData = $logoData;
        }else{
            $logoData = "empty";
        }
    @endphp
    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="{{ route('home') }}">
        <div class="sidebar-brand-icon">
            <div class="row">
                <div class="col-md-12">
                    @if ($logoData == "empty")
                        <img src="" style="max-width: 95%;" height="100" alt="logo" class="img-responsive" />
                    @else
                        <img src="{{ asset(''.$logoData['system_logo_path'] . $logoData['system_logo']) }}" style="max-width: 95%;" height="100">
                    @endif
                </div>
            </div>
        </div>
        {{--<div class="sidebar-brand-text mx-3">MANAGEMENT</div>--}}
    </a>

    <!-- Divider -->
    <hr class="sidebar-divider my-0">

    <!-- Nav Item - Dashboard -->
    <li class="nav-item">
        <a class="nav-link" href="{{ route('home') }}">
            <i class="fas fa-fw fa-tachometer-alt"></i>
            <span>Dashboard</span></a>
    </li>
    <!-- Nav Item - Pages Collapse Menu -->
    @if(env('SECTION_INVENTORY') == 'show')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
                aria-expanded="true" aria-controls="collapseOne">
                <i class="fas fa-fw fa-dolly-flatbed"></i>
                <span>P.O</span>
            </a>
            <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                <ul style="padding:0; list-style:none">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('purchase') }}">
                            <i class="fas fa-fw fa-cash-register"></i>
                            <span>Purchase Order</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('purchase_stockin') }}">
                            <i class="fas fa-fw fa-cash-register"></i>
                            <span>Stock In</span></a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
                aria-expanded="true" aria-controls="collapseTwo">
                <i class="fas fa-fw fa-dolly-flatbed"></i>
                <span>Sales</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingOne" data-parent="#accordionSidebar">
                <ul style="padding:0; list-style:none">
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('quote') }}">
                            <i class="fas fa-fw fa-certificate"></i>
                            <span>Quotes</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sales') }}">
                            <i class="fas fa-fw fa-money-check-alt"></i>
                            <span>Sales Order</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('sales.stockout') }}">
                            <i class="fas fa-fw fa-cash-register"></i>
                            <span>Stock Out</span></a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="{{ route('expenses') }}">
                <i class="fas fa-fw fa-money-bill"></i>
                <span>Expenses</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('job-order') }}">
                <i class="fas fa-fw fa-backward"></i>
                <span>Job Order</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('customer') }}">
                <i class="fas fa-fw fa-users"></i>
                <span>Customer</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="{{ route('vendor') }}">
                <i class="fas fa-fw fa-store"></i>
                <span>Vendors</span></a>
        </li>
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseTwo"
               aria-expanded="true"
               aria-controls="collapseTwo">
                <i class="fas fa-fw fa-dolly-flatbed"></i>
                <span>Inventory</span>
            </a>
            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Components:</h6>
                    <a class="collapse-item" href="{{ route('supply') }}">
                        <i class="fas fa-fw fa-warehouse"></i>
                        <span>Supplies</span>
                    </a>
                    <a class="collapse-item" href="{{ route('products') }}">
                        <i class="fas fa-fw fa-receipt"></i>
                        <span>Products</span>
                    </a>
                    <a class="collapse-item" href="{{ route('pricelist') }}">
                        <i class="fas fa-fw fa-clipboard-list"></i>
                        <span>Price List</span>
                    </a>
                </div>
            </div>
        </li>
    @endif
<!-- Nav Item - Pages Collapse Menu -->
    @if(env('SECTION_BATCHING') == 'show')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseOne"
               aria-expanded="true"
               aria-controls="collapseTwo">
                <i class="fas fa-fw fa-pallet"></i>
                <span>Other Process</span>
            </a>
            <div id="collapseOne" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Components:</h6>
                    {{--<a class="collapse-item" href="#">--}}
                    {{--<i class="fas fa-fw fa-boxes"></i>--}}
                    {{--<span>Batching</span>--}}
                    {{--</a>--}}
                    <a class="collapse-item" href="{{ route('orderform') }}">
                        <i class="fas fa-fw fa-parachute-box"></i>
                        <span>Order Form</span>
                    </a>
                </div>
            </div>
        </li>
    @endif

<!-- Nav Item - Pages Collapse Menu -->
    @if(env('SECTION_SECURITY') == 'show')
        <li class="nav-item">
            <a class="nav-link collapsed" href="#" data-toggle="collapse" data-target="#collapseSecurity"
               aria-expanded="true"
               aria-controls="collapseTwo">
                <i class="fa fa-cogs"></i>
                <span>Settings</span>
            </a>
            <div id="collapseSecurity" class="collapse" aria-labelledby="headingTwo" data-parent="#accordionSidebar">
                <div class="bg-white py-2 collapse-inner rounded">
                    <h6 class="collapse-header">Custom Components:</h6>
                    <a class="collapse-item" href="{{ route('role') }}">
                        <i class="fas fa-fw fa-user-lock"></i>
                        <span>Roles</span>
                    </a>
                    <a class="collapse-item" href="{{ route('preference') }}">
                        <i class="fas fa-fw fa-stream"></i>
                        <span>Preferences</span>
                    </a>
                    <a class="collapse-item" href="{{ route('print.setting') }}">
                        <i class="fas fa-fw fa-stream"></i>
                        <span>Print Setting</span>
                    </a>
                    <a class="collapse-item" href="{{ route('override') }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Override</span>
                    </a>
                    <a class="collapse-item" href="{{ route('users') }}">
                        <i class="fas fa-fw fa-user-alt"></i>
                        <span>User Accounts</span>
                    </a>
                    <a class="collapse-item" href="{{ route('audit') }}">
                        <i class="fas fa-exclamation-triangle"></i>
                        <span>Audit Log</span>
                    </a>
                </div>
            </div>
        </li>
    @endif

    <!-- Divider -->
    <hr class="sidebar-divider d-none d-md-block">

    <!-- Sidebar Toggler (Sidebar) -->
    <div class="text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>

</ul>
