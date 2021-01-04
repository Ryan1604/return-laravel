@extends('admin.layouts.master')
@section('title', 'Profile')

@section('content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Profile</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fa fa-user"></i>
                    Profile
                </div>
            </div>
        </div>
        <div class="section-body">
            <div class="row mt-sm-4">
                <div class="col-12 col-sm-12 col-lg-6">
                    <div class="card author-box card-primary">
                        <div class="card-body">
                            <div class="author-box-left">
                                <img alt="image" src="{{ asset('img/users/' . $user->profile_url) }}" class="rounded-circle author-box-picture" height="100px" width="100px">
                                <div class="clearfix"></div>
                                <span class="btn btn-primary mt-3">
                                    {{ $user->gender == 'M' ? 'Male' : 'Female' }}
                                </span>
                            </div>
                            <div class="author-box-details">
                                <div class="author-box-name">
                                    <a href="#">{{ $user->sn }} - {{ $user->name }}</a>
                                </div>
                                <div class="author-box-job">{{ $user->role->name }}</div>
                                <div class="author-box-description">
                                    <p>{{ $user->phone_number }}</p>
                                </div>
                                <div class="author-box-description">
                                    <p>{{ \Carbon\Carbon::parse($user->dob)->format('j F Y') }}</p>
                                </div>
                                <div class="author-box-description">
                                    <p>{{ $user->address }}</p>
                                </div>
                                <div class="mb-2 mt-3">
                                    <div class="text-small font-weight-bold">{{ $user->email }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-12 col-lg-6">
                    <div class="card card-primary">
                        <form method="POST" action="{{ route('admin.updateProfile') }}" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            <div class="card-header">
                                <h4>Edit Profile</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="username">Username <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control form-control-sm @error('username') is-invalid @enderror" id="username" name="username" value="@error('username'){{ old('username') }}@else{{ $user->username }}@enderror" placeholder="Enter username..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-username">{{ $errors->first('username') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="email">E-mail Address <sup class="text-danger">*</sup></label>
                                            <input type="email" class="form-control form-control-sm @error('email') is-invalid @enderror" id="email" name="email" value="@error('email'){{ old('email') }}@else{{ $user->email }}@enderror" placeholder="Enter email address..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-email">{{ $errors->first('email') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="profile_url">Profile URL <sup class="text-danger">max : 2MB</sup></label>
                                            <input type="file" class="form-control-file @error('profile_url') is-invalid @enderror" id="profile_url" name="profile_url">
                                            <img src="{{ asset('/img/users/' . $user->profile_url) }}" class="img-thumbnail">
                                            <div class="invalid-feedback" id="valid-profile_url">{{ $errors->first('profile_url') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <label>Gender <sup class="text-danger">*</sup></label>
                                        <br>
                                        <label class="form-radio-label">
                                            <input class="form-radio-input" type="radio" name="gender" id="male" value="M" {{ old('gender') == 'M' || $user->gender == 'M' ? 'checked' : ''}}>
                                            <span class="form-radio-sign">Male</span>
                                        </label>
                                        <label class="form-radio-label ml-4">
                                            <input class="form-radio-input" type="radio" name="gender" id="female" value="F" {{ old('gender') == 'F' || $user->gender == 'F' ? 'checked' : ''}}>
                                            <span class="form-radio-sign">Female</span>
                                        </label>
                                        <div class="text-danger" id="valid-gender">{{ $errors->first('gender') }}</div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="sn">Serial Number <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control form-control-sm @error('sn') is-invalid @enderror" id="sn" name="sn" value="@error('sn'){{ old('sn') }}@else{{ $user->sn }}@enderror" placeholder="Enter Serial Number..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-sn">{{ $errors->first('sn') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="name">Name <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" id="name" name="name" value="@error('name'){{ old('name') }}@else{{ $user->name }}@enderror" placeholder="Enter name..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-name">{{ $errors->first('name') }}</div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="phone_number">Phone Number <sup class="text-danger">*</sup></label>
                                            <input type="text" class="form-control form-control-sm @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" value="@error('phone_number'){{ old('phone_number') }}@else{{ $user->phone_number }}@enderror" placeholder="Enter phone number..." autocomplete="off">
                                            <div class="invalid-feedback" id="valid-phone_number">{{ $errors->first('phone_number') }}</div>
                                        </div>
                                        <div class="form-group">
                                            <label>Date of Birth <sup class="text-danger">*</sup></label>
                                            <input type="date" class="form-control form-control-sm @error('dob') is-invalid @enderror" id="dob" name="dob" value="@error('dob'){{ old('dob') }}@else{{ $user->dob }}@enderror">
                                            <div class="invalid-feedback" id="valid-dob">{{ $errors->first('dob') }}</div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-6">
                                        <div class="form-group">
                                            <label for="address">Address <sup class="text-danger">*</sup></label>
                                            <textarea class="form-control form-control-sm @error('address') is-invalid @enderror" name="address" id="address" placeholder="Input here...">@error('address'){{ old('address') }}@else{{ $user->address }}@enderror</textarea>
                                            <div class="invalid-feedback" id="valid-address">{{ $errors->first('address') }}</div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <button class="btn btn-primary btn-round" id="btn-save">
                                    <i class="fa fa-check"></i>
                                    Update
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
@endsection

@section('js')
    <script>
        $(document).ready(function() {
            $('body').on('keyup', '#sn, #name, #username, #email, #address', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // key up function on form phone_number
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

            // key up function on form dob
            $('body').on('change keyup', '#dob', function() {
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

            // change function on profile_url
            $('#profile_url').change(function() {
                filePreview(this);
                $('#valid-profile_url').html('');
            });

            $('#male, #female').click(function() {
                $('#valid-gender').html('');
            });

            $('form').submit(function() {
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
            });
        });
    </script>
@endsection
