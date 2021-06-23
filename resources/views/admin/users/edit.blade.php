@extends('admin.layouts.master')
@section('title', 'Edit User')

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
                <h1>Edit User</h1>
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
                        <i class="fa fa-edit"></i>
                        Edit
                    </div>
                </div>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('admin.users.update', $user->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Account Information</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="name">Name <sup class="text-danger">*</sup></label>
                                        <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="@error('name'){{ old('name') }}@else{{ $user->name }}@enderror" placeholder="Enter name..." autocomplete="off">
                                        <div class="invalid-feedback" id="valid-name">{{ $errors->first('name') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="email">E-mail Address <sup class="text-danger">*</sup></label>
                                        <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="@error('email'){{ old('email') }}@else{{ $user->email }}@enderror" placeholder="Enter email address..." autocomplete="off">
                                        <div class="invalid-feedback" id="valid-email">{{ $errors->first('email') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="profile_url">Profile URL <sup class="text-danger">max : 2MB</sup></label>
                                        <input type="file" class="form-control-file @error('profile_url') is-invalid @enderror" id="profile_url" name="profile_url">
                                        <img src="{{ asset('img/users/' . $user->profile_url) }}" class="img-thumbnail mt-1">
                                        <div class="invalid-feedback" id="valid-profile_url">{{ $errors->first('profile_url') }}</div>
                                    </div>
                                    <div id="role-parent">
                                        <div class="form-group" id="role-select">
                                            <label for="role">Role <sup class="text-danger">*</sup></label>
                                            <select class="select2 form-control form-control-sm @error('role_id') is-invalid @enderror" id="role" name="role_id">
                                                <option value="" selected disabled></option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}" {{ old('role_id') == $role->id || $user->role_id == $role->id ? 'selected' : '' }}>{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="valid-role">{{ $errors->first('role_id') }}</div>
                                        </div>
                                    </div>
                                    <label>Active <sup class="text-danger">*</sup></label>
                                    <br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="disabled" id="yes" value="0" {{ old('disabled') == '0' || $user->disabled == '0' ? 'checked' : ''}}>
                                        <span class="form-radio-sign">Yes</span>
                                    </label>
                                    <label class="form-radio-label ml-4">
                                        <input class="form-radio-input" type="radio" name="disabled" id="no" value="1" {{ old('disabled') == '1' || $user->disabled == '1' ? 'checked' : ''}}>
                                        <span class="form-radio-sign">No</span>
                                    </label>
                                    <div class="text-danger" id="valid-disabled">{{ $errors->first('disabled') }}</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.users.index') }}" class="btn btn-link float-left">
                                <i class="fas fa-arrow-left"></i>
                                Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-round float-right" id="btn-submit">
                                <i class="fas fa-check"></i>
                                Update
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

            $('.select2').select2({
                width: '100%',
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

            $('body').on('keyup', '#role_name, #name, #email', function() {
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

            $('#yes, #no').click(function() {
                $('#valid-disabled').html('');
            });

            $('form').submit(function() {
                $('#btn-submit').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
            });
        })
    </script>
@endsection
