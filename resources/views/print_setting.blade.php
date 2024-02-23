@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Print Setting</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            {{-- Payment Methods --}}
                            @livewire('component.payment-method-livewire')

                            {{--Auto Fills--}}
                            <div class="col-md-12 mt-3 row">
                                <div class="col-md-12 pb-3">
                                    <h3>Auto Fills</h3>
                                    <hr>
                                    <button type="button" class="btn btn-primary btn-sm" @click="update">Save changes</button>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea class="form-control" v-model="overview.address"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>For warranty and technical concerns please call our RMA team</label>
                                        <textarea class="form-control" v-model="overview.rma_team"></textarea>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>For Sales inquiries, Please call our Sales Team</label>
                                        <input type="text" class="form-control" v-model="overview.sales1"></input>
                                        <br>
                                        <input type="text" class="form-control" v-model="overview.sales2"></input>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="text" class="form-control" v-model="overview.email"></input>
                                    </div>
                                </div>
                            </div>
                            {{--General--}}
                            <div class="col-md-12 mt-3 row">
                                <div class="col-md-12">
                                    <h3>Header Logo</h3>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Header Logo</label>
                                        <input type="file" class="form-control" @change="handleFileChange_header">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>Header Logo</label>
                                              @php
                                                $logoData = \App\PrintSetting::find(1); // Adjust the query as needed
                                                if($logoData){
                                                    $logoData = $logoData;
                                                }else{
                                                    $logoData = "empty";
                                                }
                                            @endphp
                                            @if ($logoData == "empty")
                                                <img src="" style="max-width: 95%;" height="100" alt="logo" class="img-responsive" />
                                            @else
                                                <img src="{{ asset(''.$logoData['header_logo_path'] . $logoData['header_logo']) }}" style="width: auto; height: 86px;">
                                            @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-12 mt-3 row">
                                <div class="col-md-12">
                                    <h3>System Logo</h3>
                                    <hr>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>System Logo</label>
                                        <input type="file" class="form-control" @change="handleFileChange_system">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>System Logo</label>
                                            @php
                                                $logoData = \App\PrintSetting::find(1); // Adjust the query as needed
                                                if($logoData){
                                                    $logoData = $logoData;
                                                }else{
                                                    $logoData = "empty";
                                                }
                                            @endphp
                                            @if ($logoData == "empty")
                                                <img src="" style="max-width: 95%;" height="100" alt="logo" class="img-responsive" />
                                            @else
                                                <img src="{{ asset(''.$logoData['system_logo_path'] . $logoData['system_logo']) }}" style="width: auto; height: 110px;">
                                            @endif

                                    </div>
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
                return {
                    overview: @json($build),
                }
            },
            methods: {
                update() {
                    var $this = this;
                    var formData = new FormData();

                    // Append all properties from the overview object
                    Object.keys($this.overview).forEach(key => {
                        formData.append(key, $this.overview[key]);
                    });

                    $.ajax({
                        url: '{{ route('print_setting.update') }}',
                        method: 'POST',
                        data: formData,
                        processData: false, // Prevent jQuery from automatically processing the data
                        contentType: false, // Prevent jQuery from automatically setting the Content-Type
                        success: function (value) {
                            // Handle success
                            Swal.fire('Success!', 'Updated Print Setting.', 'success');
                        },
                        error: function (xhr, status, error) {
                            // Handle error
                            console.error(error);
                        }
                    });
                },
                handleFileChange_header(event) {
                // Access the selected file from the event
                const file = event.target.files[0];

                // Now you can do something with the file, for example, set it to the data property
                this.overview.header_logo = file;
                },
                handleFileChange_system(event) {
                // Access the selected file from the event
                const file = event.target.files[0];

                // Now you can do something with the file, for example, set it to the data property
                this.overview.file_system = file;
                },
            },
            watch: {
                // 'overview': {
                //     deep: true,
                //     handler() {
                //         this.update()
                //     }
                // }
            },
            mounted() {
            }
        });
    </script>
@endsection
