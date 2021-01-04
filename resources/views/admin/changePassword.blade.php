@extends('admin.layouts.master')
@section('title', 'Change Password')

@section('content')
<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Change Password</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">
                        <i class="fa fa-home"></i>
                        Dashboard
                    </a>
                </div>
                <div class="breadcrumb-item">
                    <i class="fas fa-lock"></i>
                    Change Password
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
                        <div class="card-body">
                            <form method="POST" action="{{ route('admin.updatePassword') }}">
                                @csrf
                                <div class="form-group">
                                    <label for="current_password">Current Password</label>
                                    <input type="password" class="form-control @error('current_password') is-invalid @enderror" id="current_password" name="current_password">
                                    <div class="invalid-feedback" id="valid-current_password">{{ $errors->first('current_password') }}</div>
                                </div>
                                <div class="form-group">
                                    <label for="password">Password</label>
                                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password">
                                    <div class="invalid-feedback" id="valid-password">{{ $errors->first('password') }}</div>
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Confirm Password</label>
                                    <input type="password" class="form-control" id="password_confirmation" name="password_confirmation">
                                    <div class="invalid-feedback" id="valid-password_confirmation"></div>
                                </div>
                                <button type="submit" class="btn btn-primary btn-round float-right" id="btn-save">
                                    <i class="fas fa-check"></i>
                                    Save Changes
                                </button>
                            </form>
                        </div>
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
            // key up function on form password
            $('body').on('keyup', '#current_password, #password, #password_confirmation', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            $('form').submit(function() {
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            });
        });
    </script>
@endsection
