@extends('admin.layouts.master')
@section('title', 'Users')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h6 class="modal-title"></h6>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="form-password">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="current_password">Current Password</label>
                            <input type="password" class="form-control" id="current_password" name="current_password">
                            <div class="invalid-feedback" id="valid-current_password"></div>
                        </div>
                        <div class="form-group">
                            <label for="password">Password</label>
                            <input type="password" class="form-control" id="password" name="password">
                            <div class="invalid-feedback" id="valid-password"></div>
                        </div>
                        <div class="form-group">
                            <label for="password_confirmation">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation"
                                name="password_confirmation">
                            <div class="invalid-feedback" id="valid-password_confirmation"></div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer no-bd">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                        <i class="fas fa-times"></i>
                        Close
                    </button>
                    <button type="button" id="btn-save" class="btn btn-primary">
                        <i class="fas fa-check"></i>
                        Save Changes
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">User Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center mb-2">
                        <div class="avatar avatar-xl">
                            <img src="" alt="User Profile" id="user_profile" class="avatar-img rounded-circle">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mb-2">
                        <p id="role" class="badge badge-primary mx-auto"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Serial Number</h6>
                            <p id="sn"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Username</h6>
                            <p id="username"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Name</h6>
                            <p id="name"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Email</h6>
                            <p id="email"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Date of Birth</h6>
                            <p id="dob"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Phone Number</h6>
                            <p id="phone"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Gender</h6>
                            <p id="gender"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Address</h6>
                            <p id="address"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Faculty</h6>
                            <p id="faculty"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Status</h6>
                            <p class="badge badge-pill" id="status"></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Users</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-user"></i>
                        Users
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-primary alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        {!! session('success') !!}
                    </div>
                </div>
            @endif

            <div class="section-body">
                <div class="card card-primary">
                    @if(auth()->user()->isLibrarian())
                        <div class="card-header">
                            <button class="btn btn-danger" id="btn-delete-all">
                                <i class="fas fa-trash-alt"></i>
                                Delete All Selected
                            </button>
                            <a class="btn btn-primary ml-auto" href="{{ route('admin.users.create') }}">
                                <i class="fas fa-plus-circle"></i>
                                Add User
                            </a>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="user-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>
                                            @if(auth()->user()->isLibrarian())
                                                <div class="custom-checkbox custom-control">
                                                    <input type="checkbox" class="custom-control-input" id="checkbox-all">
                                                    <label for="checkbox-all" class="custom-control-label">&nbsp;</label>
                                                </div>
                                            @endif
                                        </th>
                                        <th>#</th>
                                        <th>Name</th>
                                        <th>Role</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/modules/datatables/datatables.min.js') }}"></script>
    <script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '#btn-detail', function() {
                var id = $(this).val();
                var name = $(this).data('name');
                $.get('{{ route('admin.users.index') }}' + '/' + id, function(data) {
                    $('.modal-title').html('User Detail');
                    $('#user_profile').attr('src', '{{ asset('img/users') }}' + '/' + data.profile_url);
                    $('#sn').html(data.sn);
                    $('#username').html(data.username);
                    $('#name').html(data.name);
                    $('#email').html(data.email);
                    $('#dob').html(data.dob);
                    $('#phone').html(data.phone_number);
                    if(data.gender == 'M') {
                        $('#gender').html('Male');
                    }
                    if(data.gender == 'F') {
                        $('#gender').html('Female');
                    }
                    $('#address').html(data.address);
                    if(data.faculty_id) {
                        $('#faculty').html(data.faculty.name);
                    } else {
                        $('#faculty').html('UNCLASSIFIED');
                    }
                    $('#role').html(data.role.name);
                    if(data.disabled === '0') {
                        $('#status').removeClass('badge-danger').addClass('badge-success').html('Active');
                    } else {
                        $('#status').removeClass('badge-success').addClass('badge-danger').html('Inactive');
                    }
                    $('#user-modal').modal('show');
                }).fail(function() {
                    swal({
                        title: "Hooray!",
                        text: "Failed to get User (name : " + name + ")",
                        icon: "error",
                        timer: 3000
                    });
                });
            });

            var d = new Date();

            var day = d.getDate();
            var month = d.getMonth();
            var year = d.getFullYear();

            var date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + year;

            // Initializing DataTable
            $('#user-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.users.index') }}',
                columns: [
                    {
                        data: 'check',
                        name: 'check',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'role.name',
                        name: 'role.name'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    }
                ],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'print',
                        title: 'LMS - Users (' + date + ')',
                        filename: 'LMS - Users (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csv',
                        title: 'LMS - Users (' + date + ')',
                        filename: 'LMS - Users (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'LMS - Users (' + date + ')',
                        filename: 'LMS - Users (' + date + ')',
                        autoFilter: true,
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'LMS - Users (' + date + ')',
                        filename: 'LMS - Users (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                ],
                order: []
            });

            $('#user-table').DataTable().on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('body').on('click', '#checkbox-all', function() {
                if($(this).is(':checked', true)) {
                    $(".sub-checkbox").prop('checked', true);
                } else {
                    $(".sub-checkbox").prop('checked', false);
                }
            });

            // click function on btn change password
            $('body').on('click', '#btn-change-password', function() {
                var id = $(this).val();
                var name = $(this).data('name');
                $.get('{{ route('admin.users.index') }}' + '/' + id + '/changePassword', function(data) {
                    $('.modal-title').html('Change Password - ' + data.name);
                    $('#id').val(data.id);
                    $('#formModal').modal('show');
                    $('#form-password').trigger('reset');
                    $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                    $('#form-password').find('.form-control').removeClass('is-invalid is-valid');
                    $('#btn-save').removeAttr('disabled');
                }).fail(function() {
                    swal({
                        title: "Hooray!",
                        text: "Something goes wrong \n (name : " + name + ")",
                        icon: "error",
                        timer: 3000
                    });
                });
            });

            $('body').on('keyup', '#current_password, #password, #password_confirmation', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // btn save to Post Change Password
            $('#btn-save').click(function() {
                var id = $('#id').val();
                var name = $('#btn-change-password').data('name');
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('admin.users.index') }}' + '/' + id + '/postChangePassword',
                    data: {
                        current_password: $('#current_password').val(),
                        password: $('#password').val(),
                        password_confirmation: $('#password_confirmation').val(),
                    },
                    dataType: 'json',
                    success: function(data) {
                        swal({
                            title: "Good Job!",
                            text: "User password was successfully changed \n (name : " + name + ")",
                            icon: "success",
                            timer: 3000
                        });

                        $('#user-table').DataTable().draw(false);
                        $('#user-table').DataTable().on('draw', function() {
                            $('[data-toggle="tooltip"]').tooltip();
                        });

                        $('#formModal').modal('hide');
                    },
                    error: function(data) {
                        try {
                            if (data.responseJSON.errors.current_password) {
                                $('#current_password').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-current_password').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-current_password').html(data.responseJSON.errors.current_password);
                            }

                            if (data.responseJSON.errors.password) {
                                $('#password').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-password').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-password').html(data.responseJSON.errors.password);
                            }

                            $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                            $('#btn-save').removeAttr('disabled');
                        } catch {
                            swal({
                                title: "Hooray!",
                                text: "Failed to change user password \n (name : " + name + ")",
                                icon: "error",
                                timer: 3000
                            });

                            $('#formModal').modal('hide');
                        }
                    }
                })
            });

            // Delete one user
            $('body').on('click', '#btn-delete', function(){
                var id = $(this).val();
                var name = $(this).data('name');
                swal("Whoops!", "Are you sure want to delete? \n (name : " + name + ")", "warning", {
                    buttons: {
                        cancel: "No, just keep it exists!",
                        ok: {
                            text: "Yes, delete it!",
                            value: "ok"
                        }
                    },
                }).then((value) => {
                    switch (value) {
                        case "ok" :
                            $.ajax({
                                type: "DELETE",
                                url: '{{ route('admin.users.store') }}' + '/' + id,
                                success: function(data) {
                                    $('#user-table').DataTable().draw(false);
                                    $('#user-table').DataTable().on('draw', function() {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    });

                                    swal({
                                        title: "Well Done!",
                                        text: "User was successfully deleted \n (name : " + name + ")",
                                        icon: "success",
                                        timer: 3000
                                    });
                                },
                                error: function(data) {
                                    swal({
                                        title: "Hooray!",
                                        text: "Something goes wrong \n (name : " + name + ")",
                                        icon: "error",
                                        timer: 3000
                                    });
                                }
                            });
                        break;

                        default :
                            swal({
                                title: "Oh Yeah!",
                                text: "It's safe, don't worry",
                                icon: "info",
                                timer: 3000
                            });
                        break;
                    }
                });
            });

            // Delete all selected user
            $('#btn-delete-all').click(function() {
                var ids = [];
                $('.sub-checkbox:checked').each(function() {
                    ids.push($(this).val());
                });

                if(ids.length <= 0) {
                    swal({
                        title: "Whoops!",
                        text: "Calm down, select at least one row first",
                        icon: "warning",
                        timer: 3000
                    });
                } else {
                    swal("Whoops!", "Are you sure want to delete these selected rows?", "warning", {
                        buttons: {
                            cancel: "No, just keep it exists!",
                            ok: {
                                text: "Yes, delete it!",
                                value: "ok"
                            }
                        },
                    }).then((value) => {
                        switch (value) {
                            case "ok" :
                                $.ajax({
                                    type: "POST",
                                    url: '{{ route('admin.users.deleteAllSelected') }}',
                                    data: {
                                        ids: ids,
                                    },
                                    success: function(data) {
                                        $('#user-table').DataTable().draw(false);
                                        $('#user-table').DataTable().on('draw', function() {
                                            $('[data-toggle="tooltip"]').tooltip();
                                        });

                                        swal({
                                            title: "Well Done!",
                                            text: "Selected users were successfully deleted",
                                            icon: "success",
                                            timer: 3000
                                        });
                                    },
                                    error: function(data) {
                                        swal({
                                            title: "Hooray!",
                                            text: "Something goes wrong",
                                            icon: "error",
                                            timer: 3000
                                        });
                                    }
                                });
                            break;

                            default :
                                swal({
                                    title: "Oh Yeah!",
                                    text: "It's safe, don't worry",
                                    icon: "info",
                                    timer: 3000
                                });
                            break;
                        }
                    });
                }
            });
        });
    </script>
@endsection
