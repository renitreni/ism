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

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Job Order Form</h6>
                    </div>
                    <div class="card-body">
                        <div>
                            <div class="row">
                                <div class="col-md-2 mb-2">
                                    <label>Job No.</label>
                                    <input class="form-control" v-model='overview.job_no'>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label>Customer Name</label>
                                    <input class="form-control" v-model='overview.customer_name'>
                                </div>
                                <div class="col-md-4 mb-2">
                                    <label>Contact Person</label>
                                    <input class="form-control" v-model='overview.contact_person'>
                                </div>
                                <div class="col-md-12"></div>
                                <div class="col-md-2 mb-2">
                                    <label>Date Of Purchased</label>
                                    <input type="date" class="form-control" v-model='overview.date_of_purchased'>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>SO No.</label>
                                    <input class="form-control" v-model='overview.so_no'>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>Mobile No.</label>
                                    <input class="form-control" v-model='overview.mobile_no'>
                                </div>
                                <div class="col-md-12"></div>
                                <div class="col-md-2 mb-2">
                                    <label>Process Type</label>
                                    <select class="form-control" v-model='overview.process_type'>
                                        <option value="">-- Select Options --</option>
                                        @foreach ($processTypes as $item)
                                            <option value="{{ $item->value }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2">
                                    <label>Status</label>
                                    <select class="form-control" v-model='overview.status'>
                                        <option value="">-- Select Options --</option>
                                        @foreach ($statuses as $item)
                                            <option value="{{ $item->value }}">{{ $item->value }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2 mb-2" v-if='overview.status'>
                                    <label>Status Date: @{{ overview.status }}</label>
                                    <input type="date" class="form-control" v-model='overview.status_date'>
                                </div>
                                <div class="col-md-12"></div>
                                <div class="col-md-4 mb-2">
                                    <label>Remarks</label>
                                    <textarea class="form-control" v-model='overview.remarks'></textarea>
                                </div>
                                <div class="col-md-12 mb-2 mt-2">
                                    <div class="d-flex flex-row w-auto">
                                        <button v-if="overview.id" type="button" class="btn btn-block btn-primary mx-2"
                                            @click="update()">Update</button>
                                        <button v-else type="button" class="btn btn-block btn-primary m-0"
                                            @click="save()">Submit</button>
                                        <a href="{{ route('job-order') }}"mobile_no
                                            class="btn btn-block btn-secondary m-0 mx-2">Cancel</a>
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
                    dt: null,
                    overview: @isset($overview)
                        {!! $overview !!}
                    @else
                        {
                            'job_no': "{{ $jobNo }}",
                            'customer_name': "",
                            'process_type': "",
                            'date_of_purchased': "",
                            'so_no': "",
                            'contact_person': "",
                            'mobile_no': "",
                            'status': "",
                            'remarks': "",
                            'created_by': {{ auth()->id() }}
                        }
                    @endisset
                }
            },
            methods: {
                update() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('expenses.update') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('expenses') }}'
                                }
                            })
                        },
                        error(e) {
                            console.log(e);
                            Swal.fire(
                                e.statusText,
                                e.responseJSON.message,
                                'warning'
                            );
                        }
                    });
                },
                save() {
                    var $this = this;
                    $.ajax({
                        url: '{{ route('job-order.store') }}',
                        method: 'POST',
                        data: $this.overview,
                        success: function(value) {
                            Swal.fire(
                                'Good job!',
                                'Operation is successful.',
                                'success'
                            ).then((result) => {
                                if (result.value) {
                                    window.location = '{{ route('job-order') }}'
                                }
                            })
                        },
                        error(e) {
                            console.log(e);
                            Swal.fire(
                                e.statusText,
                                e.responseJSON.message,
                                'warning'
                            );
                        }
                    });
                }
            },
            mounted() {
                var $this = this;
                $('[name="description"]').autocomplete({
                    minLength: 2,
                    select: function(event, ui) {
                        $this.overview.description = ui.item.value;
                    },
                    source: {!! $customers !!}
                });
            }
        });
    </script>
@endsection
