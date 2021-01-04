@extends('admin.layouts.master')
@section('title', 'Borrow Setting')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Borrow Setting</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-exchange-alt"></i>
                        Borrow Setting
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="row justify-content-center">
                    @if(auth()->user()->role_id === 1)
                        <div class="col-md-6 col-sm-12 col-12">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Edit</h4>
                                </div>
                                <div class="card-body">
                                    <form id="issueRule-form">
                                        <input type="hidden" name="id" id="id">
                                        <div class="form-group">
                                            <label for="role">Role <sup class="text-danger">*</sup></label>
                                            <select class="select2 form-control form-control-sm @error('role_id') is-invalid @enderror" id="role" name="role_id">
                                                <option value="" selected disabled></option>
                                                @foreach ($roles as $role)
                                                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                                                @endforeach
                                            </select>
                                            <div class="invalid-feedback" id="valid-role"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="max_borrow_item">Max Borrow Item <sup class="text-danger">*</sup></label>
                                            <input type="number" class="form-control" id="max_borrow_item" name="max_borrow_item" placeholder="Enter max borrow item..." autocomplete="off" min="0">
                                            <div class="invalid-feedback" id="valid-max_borrow_item"></div>
                                        </div>
                                        <div class="form-group">
                                            <label for="max_borrow_day">Max Borrow Day <sup class="text-danger">*</sup></label>
                                            <input type="number" class="form-control" id="max_borrow_day" name="max_borrow_day" placeholder="Enter max borrow day..." autocomplete="off" min="0">
                                            <div class="invalid-feedback" id="valid-max_borrow_day"></div>
                                        </div>
                                        <div class="form-group">
                                            <button type="button" id="btn-save" class="btn btn-primary btn-block">
                                                <i class="fas fa-check"></i>
                                                Update
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endif
                    <div class="col-md-6 col-sm-12 col-12">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h4 class="card-title">Borrow Setting</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table" id="issueRule-table">
                                        <thead class="thead-light">
                                            <tr>
                                                <th>Role</th>
                                                <th>Max Borrow Item</th>
                                                <th>Max Borrow Day</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @section('record')
                                                @foreach($issueRules as $issueRule)
                                                    <tr>
                                                        <td>{{ $issueRule->role->name }}</td>
                                                        <td>{{ $issueRule->max_borrow_item }}</td>
                                                        <td>{{ $issueRule->max_borrow_day }}</td>
                                                    </tr>
                                                @endforeach
                                            @show
                                        </tbody>
                                    </table>
                                </div>
                            </div>
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
    <script src="{{ asset('backend/modules/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('#issueRule-table').DataTable();

            $('.select2').select2({
                width: '100%',
            });

            $('.select2').on('select2:selecting', function() {
                $(this).removeClass('is-invalid');
            });

            // key up function on form role
            $('body').on('keyup change', '#role', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');

                    $.ajax({
                        type: 'GET',
                        url : '{{ route('admin.issues.fetchRule') }}',
                        data: {
                            role_id: $('#role').val(),
                        },
                        dataType: 'json',
                        success: function(data) {
                            $('#max_borrow_item').removeClass('is-invalid').addClass('is-valid').val(data.max_borrow_item);
                            $('#max_borrow_day').removeClass('is-invalid').addClass('is-valid').val(data.max_borrow_day);
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
                }
            });

            $('body').on('keyup change', '#max_borrow_item, #max_borrow_day', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Store new Issue Rule or update Issue Rule
            $('#btn-save').click(function() {
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);

                $.ajax({
                    type: 'PUT',
                    url: '{{ route('admin.issues.borrowUpdate') }}',
                    data: {
                        role_id: $('#role').val(),
                        max_borrow_item: $('#max_borrow_item').val(),
                        max_borrow_day: $('#max_borrow_day').val(),
                    },
                    dataType: 'json',
                    success: function(data) {
                        swal({
                            title: "Good Job!",
                            text: "Issue Rule was successfully updated",
                            icon: "success",
                            timer: 3000
                        });

                        $('#issueRule-table').DataTable().destroy();

                        $('#issueRule-table tbody').html(data);

                        $('#issueRule-table').DataTable();

                        $('#issueRule-form').trigger('reset');
                        $('#issueRule-form').find('.form-control').removeClass('is-invalid is-valid');

                        $('#btn-save').html('<i class="fas fa-check"></i> Update');
                        $('#btn-save').removeAttr('disabled');
                    },
                    error: function(data) {
                        try {
                            if (data.responseJSON.errors.role_id) {
                                $('#role').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-role').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-role').html(data.responseJSON.errors.role_id);
                            }

                            if (data.responseJSON.errors.max_borrow_item) {
                                $('#max_borrow_item').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-max_borrow_item').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-max_borrow_item').html(data.responseJSON.errors.max_borrow_item);
                            }

                            if (data.responseJSON.errors.max_borrow_day) {
                                $('#max_borrow_day').removeClass('is-valid').addClass('is-invalid');
                                $('#valid-max_borrow_day').removeClass('valid-feedback').addClass('invalid-feedback');
                                $('#valid-max_borrow_day').html(data.responseJSON.errors.max_borrow_day);
                            }

                            $('#btn-save').html('<i class="fas fa-check"></i> Update');
                            $('#btn-save').removeAttr('disabled');
                        } catch {
                            swal({
                                title: "Hooray!",
                                text: "Something goes wrong, reload the page",
                                icon: "error",
                                timer: 3000
                            });

                            $('#btn-save').html('<i class="fas fa-check"></i> Update');
                            $('#btn-save').removeAttr('disabled');
                        }
                    }
                });
            });
        });
    </script>
@endsection
