@extends('admin.layouts.master')
@section('title', 'Add Issue')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Add Issue</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-exchange-alt"></i>
                        Add Issue
                    </div>
                </div>
            </div>
            <div class="section-body">
                <div class="card card-primary">
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.issues.borrows.store') }}" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="user">User <sup class="text-danger">*</sup></label>
                                <select class="select2 form-control @error('user_id') is-invalid @enderror" id="user" name="user_id">
                                    <option value="" selected disabled></option>
                                    @foreach($roles as $role)
                                        <optgroup label="{{ $role->name }}">
                                            @foreach ($users->orderBy('name')->where('role_id', $role->id)->where('disabled', '0')->get() as $user)
                                                <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                                    {{ $user->sn }} - {{ $user->name }}
                                                </option>
                                            @endforeach
                                        </optgroup>
                                    @endforeach
                                </select>
                                <br>
                                <div id="user-detail" style="display:none" class="mt-4">
                                    <div class="row">
                                        <div class="col-6 col-sm-4">
                                            <div id="user-picture">
                                                <img src="" alt="image profile" class="img-thumbnail">
                                            </div>
                                        </div>
                                        <div class="col-6 col-sm-8">
                                            <div id="user-profile">
                                                <div class="row">
                                                    <div class="col-sm-6 col-md-4">
                                                        <h6>Name</h6>
                                                        <p id="name"></p>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <h6>E-mail Address</h6>
                                                        <p id="email"></p>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <h6>Phone Number</h6>
                                                        <p id="phone"></p>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <h6>Gender</h6>
                                                        <p id="gender"></p>
                                                    </div>
                                                    <div class="col-sm-6 col-md-4">
                                                        <h6>Faculty</h6>
                                                        <p id="faculty"></p>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="invalid-feedback" id="valid-user">{{ $errors->first('user_id') }}</div>
                            </div>
                            <div class="form-group">
                                <label for="book">Book <sup class="text-danger">*</sup></label>
                                <select class="select2 form-control {{ $errors->has('book_id') || count($errors->get('book_id.*')) > 0 ? 'is-invalid' : '' }}" id="book" name="book_id[]" multiple>
                                    @foreach ($items as $item)
                                        <option value="{{ $item->id }}"
                                            @if($errors->any() && old('book_id') !== NULL)
                                                @for ($idx = 0; $idx < count(old('book_id')); $idx++)
                                                    {{ old('book_id')[$idx] == $item->id ? 'selected' : '' }}
                                                @endfor
                                            @endif
                                            >ISBN {{ $item->isbn }} {{ $item->code ? ' - ' . $item->code : '' }} - {{ $item->title }}</option>
                                    @endforeach
                                </select>
                                <div class="invalid-feedback" id="valid-book">{!! $errors->first('book_id') ?? 'Something error, please select again.' !!}</div>
                            </div>
                            <div class="form-group">
                                <label>Borrow Date <sup class="text-danger">*</sup></label>
                                <input type="date" class="form-control @error('borrow_date') is-invalid @enderror" id="borrow_date" name="borrow_date" value="{{ $date->toDateString() }}" readOnly>
                                <div class="invalid-feedback" id="valid-borrow_date">{{ $errors->first('borrow_date') }}</div>
                            </div>
                            <div class="form-group">
                                <label>Due Date <sup class="text-danger">*</sup></label>
                                <input type="date" class="form-control @error('due_date') is-invalid @enderror" id="due_date" name="due_date" value="{{ $date->addWeeks(2)->toDateString() }}" readOnly>
                                <div class="invalid-feedback" id="valid-due_date">{{ $errors->first('due_date') }}</div>
                            </div>
                            <br><br>
                            <a href="{{ route('admin.issues.borrows.index') }}" class="btn btn-link">
                                <i class="fas fa-arrow-left"></i>
                                Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-round float-right" id="btn-submit">
                                <i class="fas fa-check"></i>
                                Save Changes
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
            });

            $('.select2').on('select2:selecting', function() {
                $(this).removeClass('is-invalid');
            });

            $('body').on('focus', '#borrow_date, #due_date', function() {
                $(this).removeAttr('readOnly');
            });

            $('body').on('keyup change', '#borrow_date, #due_date, #user, #book', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // change function on user to display user detail
            $('#user').change(function() {
                var id = $(this).val();

                if(id !== null) {
                    $.ajax({
                        type: 'GET',
                        url: '{{ route('admin.issues.fetchUser') }}',
                        data: {
                            user_id: id,
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#user-detail').removeAttr('style');
                            $('#user-picture img').attr('src', '{{ asset('img/users') }}' + '/' + data.profile_url);
                            $('#user-profile #name').html(data.name);
                            $('#user-profile #email').html(data.email);
                            $('#user-profile #phone').html(data.phone_number);
                            if(data.gender === 'M') {
                                $('#user-profile #gender').html('Male');
                            }
                            if(data.gender === 'F') {
                                $('#user-profile #gender').html('Female');
                            }
                            $('#user-profile #faculty').html(data.faculty.name);
                        },
                        error: function(data) {
                            swal({
                                title: "Hooray!",
                                text: "Failed to get User (id : " + id + ")",
                                icon: "error",
                                timer: 3000
                            });
                        }
                    });
                } else {
                    $('#user-detail').css('display', 'none');
                }
            });

            $('form').submit(function() {
                $('#btn-submit').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            });
        })
    </script>
@endsection
