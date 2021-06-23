@extends('admin.layouts.master')
@section('title', 'Create User')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
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
                        <div class="col-lg-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Account Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name <sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" placeholder="Enter name..." autocomplete="off">
                                        <div class="invalid-feedback" id="valid-name">{{ $errors->first('name') }}</div>
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

            $('body').on('keyup', '#role_name, #name, #email, #password, #password-confirm, #address', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
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

            $('body').on('keyup change', '#role', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });


            $('form').submit(function() {
                $('#btn-submit').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            });
        })
    </script>
@endsection
