@extends('admin_layout')

@section('content')
    <div id='app' class="container-fluid">

        <!-- Content Row -->
        <div class="row">

            <div class="col-lg-12 mb-4">
                <!-- Approach -->
                <div class="card shadow mb-4">
                    <div class="card-header py-3">
                        <h6 class="m-0 font-weight-bold text-primary">Users Overview</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-auto">
                                <a href="{{ route('user.create') }}" class="btn btn-sm btn-success"><i
                                            class="fa fa-plus"></i> New User</a>
                            </div>
                            <div class="col-md-auto">
                                <form action="{{ route('user.logo.upload') }}" method="POST"
                                      enctype="multipart/form-data">
                                    @csrf
                                    <div class="form-group row">
                                        <div class="col-md-6">
                                            <input type="file" name="logo" class="form-control-file">
                                        </div>
                                        <div class="col-md-auto">
                                            <button type="submit" class="btn btn-sm btn-primary">Upload Logo</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-md-12 mt-3">
                                <table id="table-user" class="table table-striped nowrap" style="width:100%"></table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="passModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Change Password</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">New Password</label>
                                    <input type="password" class="form-control form-control-sm" v-model="password">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="changePass">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

        <div id="roleModal" class="modal fade" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Assign Role</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label class="control-label">Select a Role</label>
                                    <select class="form-control" v-model="user_role">
                                        @foreach($roles as $item)
                                            <option value="{{$item}}">{{$item}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="assignRole">Assign Role</button>
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
                    password: '',
                    user_role: '',
                    overview: {
                        id: "",
                        subject: "",
                        recipient_email: "",
                        recipient_name: "",
                        message: "",
                    }
                }
            },
            methods: {
                changePass() {
                    var $this = this;
                    $this.overview.password = $this.password;
                    $.ajax({
                        url: "{{ route('user.change.pass') }}",
                        method: 'POST',
                        data: $this.overview,
                        success(value) {
                            Swal.fire('Updated!', 'Password has been updated.', 'success');
                            $this.dt.draw();
                            $('#passModal').modal('hide');
                            $this.password = '';
                        }
                    });
                },
                getUserRole() {
                    var $this = this;
                    $.ajax({
                        url: "{{ route('user.role') }}",
                        method: 'POST',
                        data: $this.overview,
                        success(value) {
                            $this.user_role = value;
                        }
                    });
                },
                assignRole() {
                    var $this = this;
                    $this.overview.role = $this.user_role;
                    $.ajax({
                        url: "{{ route('user.assign') }}",
                        method: 'POST',
                        data: $this.overview,
                        success(value) {
                            Swal.fire('Success!', 'Role has been assigned.', 'success');
                            $('#roleModal').modal('hide');
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
                                url: "{{ route('user.destroy') }}",
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
                $this.dt = $('#table-user').DataTable({
                    processing: true,
                    serverSide: true,
                    scrollX: true,
                    responsive: true,
                    order: [[1, 'desc']],
                    ajax: {
                        url: "{{ route('user.table') }}",
                        method: "POST",
                    },
                    columns: [
                        {
                            data: function (value) {
                                return '<div class="btn-group btn-group-sm shadow-sm" role="group" aria-label="Basic example">' +
                                    '<a href="#" class="btn btn-warning btn-pass" data-toggle="modal" data-target="#passModal"><i class="fa fa-key"></i></a>' +
                                    '<a href="/user/detail/' + value.id + '" class="btn btn-info btn-view"><i class="fa fa-pen"></i></a>' +
                                    '<button type="button" class="btn btn-primary btn-permission" data-toggle="modal" data-target="#roleModal"><i class="fa fa-project-diagram"></i></button>' +
                                    '<button type="button" class="btn btn-danger btn-destroy"><i class="fa fa-trash"></i></button>' +
                                    '</div>'
                            },
                            searchable: false,
                            bSortable: false,
                            title: 'Action'
                        },
                        {data: 'id', name: 'id', title: 'ID'},
                        {data: 'name', title: 'Name'},
                        {data: 'position', title: 'Position'},
                        {data: 'email', title: 'Email'},
                    ],
                    drawCallback: function () {
                        $('table .btn').on('click', function () {
                            let data = $(this).parent().parent().parent();
                            let hold = $this.dt.row(data).data();
                            $this.overview = hold;
                            console.log(hold);
                            $this.getUserRole();
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
