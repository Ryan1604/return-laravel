<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, shrink-to-fit=no" name="viewport">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <title>Change Password</title>

    <!--Bootsrap 4 CDN-->
    <link href="{{ asset('frontend/css/bootstrap.min.css') }}" rel="stylesheet" id="bootstrap-css">

    <!--Custom styles-->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/login.css') }}">
    <link rel="icon" href="{{ asset('img/npic-favicon.ico') }}">

    <!-- Fonts and icons -->
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend\plugins\fontawesome-free-5.0.1\css\fontawesome-all.css') }}">
</head>

<body>
    <div class="container">
        <div class="d-flex justify-content-center h-100">
            <div class="card">
                <div class="card-header">
                    <h3>Change Password</h3>

                </div>
                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    <form action="{{ route('public.updatepassword') }}" method="POST">
                        @csrf
                        <input type="hidden" name="token" value="{{ $token }}">
                        <input type="hidden" name="email" value="{{ $email }}">
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control @error('current-password') is-invalid @enderror"
                                placeholder="current password" name="current-password" autocomplete="current-password" autofocus>
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control @error('password') is-invalid @enderror"
                                placeholder="new password" name="password" autocomplete="new-password">
                        </div>
                        <div class="input-group form-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text"><i class="fas fa-key"></i></span>
                            </div>
                            <input type="password" class="form-control"
                                placeholder="confirm password" name="password_confirmation" autocomplete="new-password">
                        </div>
                        <div class="form-group">
                            <a href="{{ route('password.request') }}" class="float-left btn btn-link text-white">Forget Password?</a>
                            <button type="submit" class="btn float-right btn-warning">Change Password</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
