@extends('admin.layouts.master')
@section('title', 'Create User')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"></h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="role-form">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="role_name">Name <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="role_name" name="name"
                                placeholder="Enter role name..." autocomplete="off">
                            <div class="invalid-feedback" id="valid-role_name"></div>
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

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Create User</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.users.index') }}">
                            <i class="fa fa-user"></i>
                            Users
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-plus-circle"></i>
                        Create
                    </div>
                </div>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('admin.users.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Account Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="username">Username <sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control form-control-sm @error('username') is-invalid @enderror" id="username" name="username" value="{{ old('username') }}" placeholder="Enter username..." autocomplete="off">
                                        <div class="invalid-feedback" id="valid-username">{{ $errors->first('username') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">E-mail Address <sup class="text-danger">*</sup></label>
                                        <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email') }}" placeholder="Enter email address..." autocomplete="off">
                                        <div class="invalid-feedback" id="valid-email">{{ $errors->first('email') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password">Password <sup class="text-danger">*</sup></label>
                                        <input type="password" class="form-control form-control-sm @error('password') is-invalid @enderror" id="password" name="password" placeholder="Enter password..." autocomplete="off">
                                        <div class="invalid-feedback" id="valid-password">{{ $errors->first('password') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="password-confirm">Confirm Password <sup class="text-danger">*</sup></label>
                                        <input type="password" class="form-control form-control-sm" id="password-confirm" name="password_confirmation" placeholder="Enter password confirmation..." autocomplete="off">
                                    </div>
                                    <div class="form-group">
                                        <label for="profile_url">Profile URL <sup class="text-danger">max : 2MB</sup></label>
                                        <input type="file" class="form-control-file @error('profile_url') is-invalid @enderror" id="profile_url" name="profile_url">
                                        <div class="invalid-feedback" id="valid-profile_url">{{ $errors->first('profile_url') }}</div>
                                    </div>
                                    <div id="role-parent">
                                        <div class="form-group" id="role-select">
                                            <button class="btn btn-link btn-sm float-right" id="btn-add">
                                                Add
                                            </button>
                                            <label for="role">Role <sup class="text-danger">*</sup></label>
                                            <select class="select2 form-control form-control-sm @error('role_id') is-invalid @enderror" id="role" name="role_id">
                                                <option value="" selected disabled></option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="valid-role">{{ $errors->first('role_id') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Personal Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="sn">Serial Number <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control @error('sn') is-invalid @enderror" id="sn" name="sn" value="{{ old('sn') }}" placeholder="Enter Serial Number..." autocomplete="off">
                                                <div class="invalid-feedback" id="valid-sn">{{ $errors->first('sn') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="name">Name <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter name..." autocomplete="off">
                                                <div class="invalid-feedback" id="valid-name">{{ $errors->first('name') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="phone_number">Phone Number <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="{{ old('phone_number') }}" placeholder="Enter phone number..." autocomplete="off">
                                                <div class="invalid-feedback" id="valid-phone_number">{{ $errors->first('phone_number') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label>Date of Birth <sup class="text-danger">*</sup></label>
                                                <input type="date" class="form-control @error('dob') is-invalid @enderror" id="dob" name="dob" value="{{ old('dob') }}">
                                                <div class="invalid-feedback" id="valid-dob">{{ $errors->first('dob') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="faculty">Faculty</label>
                                                <select class="select2 form-control form-control-sm @error('faculty_id') is-invalid @enderror" name="faculty_id" id="faculty">
                                                    <option value="" selected>-- UNCLASSIFIED --</option>
                                                        @foreach ($faculties as $faculty)
                                                            <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>{{ $faculty->name }}</option>
                                                        @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="valid-faculty">{{ $errors->first('faculty_id') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="address">Address <sup class="text-danger">*</sup></label>
                                                <textarea class="form-control @error('address') is-invalid @enderror" name="address" id="address" placeholder="Input here..." rows="5">{{ old('address') }}</textarea>
                                                <div class="invalid-feedback" id="valid-address">{{ $errors->first('address') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <label>Gender <sup class="text-danger">*</sup></label>
                                            <br>
                                            <label class="form-radio-label">
                                                <input class="form-radio-input" type="radio" name="gender" id="male" value="M" {{ old('gender') == 'M' ? 'checked' : ''}}>
                                                <span class="form-radio-sign">Male</span>
                                            </label>
                                            <label class="form-radio-label ml-4">
                                                <input class="form-radio-input" type="radio" name="gender" id="female" value="F" {{ old('gender') == 'F' ? 'checked' : ''}}>
                                                <span class="form-radio-sign">Female</span>
                                            </label>
                                            <div class="text-danger" id="valid-gender">{{ $errors->first('gender') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-link float-left">
                                <i class="fas fa-arrow-left"></i>
                                Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-round float-right" id="btn-submit">
                                <i class="fas fa-check"></i>
                                Save Changes
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2').on('select2:selecting', function() {
                $(this).removeClass('is-invalid');
            });

            // Open Modal to Add new Role
            $('#btn-add').click(function(e) {
                e.preventDefault();
                $('#formModal').modal('show');
                $('.modal-title').html('Add Role');
                $('#role-form').trigger('reset');
                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                $('#role-form').find('.form-control').removeClass('is-invalid is-valid');
                $('#btn-save').val('save').removeAttr('disabled');
            });

            $('body').on('keyup', '#role_name, #sn, #name, #username, #email, #password, #password-confirm, #address', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Store new Role or update Role
            $('#btn-save').click(function() {
                var formData = {
                    name: $('#role_name').val(),
                };

                var type = "POST";
                var ajaxurl = '{{ route('admin.roles.store') }}';
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);

                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        swal({
                            title: "Good Job!",
                            text: "Role was successfully added \n (name : " + formData.name + ")",
                            icon: "success",
                            timer: 3000
                        });

                        $('#role-parent').load('{{ route('admin.users.create') }} ' + '#role-select', function() {
                            // Open Modal to Add new role
                            $('#btn-add').click(function(e) {
                                e.preventDefault();
                                $('#formModal').modal('show');
                                $('.modal-title').html('Add role');
                                $('#role-form').trigger('reset');
                                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                                $('#role-form').find('.form-control').removeClass('is-invalid is-valid');
                                $('#btn-save').val('save').removeAttr('disabled');
                            });

                            $('.select2').select2().trigger('load');

                            $('.select2').on('select2:selecting', function() {
                                $(this).removeClass('is-invalid');
                            });
                        });

                        $('#formModal').modal('hide');
                    },
                    error: function(data) {
                        try {
                            if (data.responseJSON.errors.name) {
                                $('#role_name').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-role_name').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-role_name').html(data.responseJSON.errors.name);
                            }

                            $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                            $('#btn-save').removeAttr('disabled');
                        } catch {
                            swal({
                                title: "Hooray!",
                                text: "Unknown error, reload the page",
                                icon: "error",
                                timer: 3000
                            });

                            $('#formModal').modal('hide');
                        }
                    }
                });
            });

            $('body').on('keyup', '#phone_number', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');

                    test = test.split('-').join('');
                    test = test.match(/.{1,4}/g).join('-');

                    $(this).val(test);
                }
            });

            $('body').on('keypress', '#phone_number', function(e) {
                var keyCode = e.which ? e.which : e.keyCode;
                if(!(keyCode >= 48 && keyCode <= 57)) {
                    return false;
                } else {
                    return true;
                }
            });

            function filePreview(input) {
                if(input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#profile_url + img').remove();
                        $('#profile_url').after('<img src="' + e.target.result + '" class="img-thumbnail">');
                    };
                    reader.readAsDataURL(input.files[0]);
                };
            }

            $('#profile_url').change(function() {
                filePreview(this);
                $('#valid-profile_url').html('');
            });

            $('body').on('keyup change', '#role, #dob, #faculty', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            $('#male, #female').click(function() {
                $('#valid-gender').html('');
            });

            $('form').submit(function() {
                $('#btn-submit').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            });
        })
    </script>
@endsection
