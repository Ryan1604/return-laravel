@extends('admin.layouts.master')
@section('title', 'Publishers')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
    <!-- Modal -->
    <div class="modal fade" id="formModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form id="publisher-form">
                        <input type="hidden" name="id" id="id">
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="form-group">
                                    <label for="name">Name <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control" id="name" name="name"
                                        placeholder="Enter publisher name..." autocomplete="off">
                                    <div class="invalid-feedback" id="valid-name"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="form-group">
                                    <label for="email">Email <sup class="text-danger">*</sup></label>
                                    <input type="email" class="form-control" id="email" name="email"
                                        placeholder="Enter publisher email..." autocomplete="off">
                                    <div class="invalid-feedback" id="valid-email"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="form-group">
                                    <label for="phone_number">Phone Number <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                                        placeholder="Enter phone number..." autocomplete="off">
                                    <div class="invalid-feedback" id="valid-phone_number"></div>
                                </div>
                            </div>
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="form-group">
                                    <label for="website">Website <sup class="text-danger">*</sup></label>
                                    <input type="text" class="form-control" id="website" name="website"
                                        placeholder="Enter publisher website..." autocomplete="off">
                                    <div class="invalid-feedback" id="valid-website"></div>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-sm-6 col-6">
                                <div class="form-group">
                                    <label for="address">Address <sup class="text-danger">*</sup></label>
                                    <textarea class="form-control" id="address" name="address" rows="5"
                                        placeholder="Enter publisher address..."></textarea>
                                    <div class="invalid-feedback" id="valid-address"></div>
                                </div>
                            </div>
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

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Publishers</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-newspaper"></i>
                        Publishers
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="card card-primary">
                    @if(auth()->user()->isLibrarian())
                        <div class="card-header">
                            <button class="btn btn-danger" id="btn-delete-all">
                                <i class="fas fa-trash-alt"></i>
                                Delete All Selected
                            </button>
                            <button class="btn btn-primary ml-auto" id="btn-add">
                                <i class="fas fa-plus-circle"></i>
                                Add Publisher
                            </button>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="publisher-table">
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
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Phone Number</th>
                                        <th>Website</th>
                                        <th>Address</th>
                                        <th>
                                            @if(auth()->user()->isLibrarian())
                                                Action
                                            @endif
                                        </th>
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

            var d = new Date();

            var day = d.getDate();
            var month = d.getMonth();
            var year = d.getFullYear();

            var date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + year;

            // Initializing DataTable
            $('#publisher-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('admin.publishers.index') }}',
                    type: 'GET'
                },
                columns: [
                    {
                        data: 'check',
                        name: 'check',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'email',
                        name: 'email',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'phone_number',
                        name: 'phone_number',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'website',
                        name: 'website'
                    },
                    {
                        data: 'address',
                        name: 'address'
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
                        title: 'LMS - Publishers (' + date + ')',
                        filename: 'LMS - Publishers (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'csv',
                        title: 'LMS - Publishers (' + date + ')',
                        filename: 'LMS - Publishers (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'LMS - Publishers (' + date + ')',
                        filename: 'LMS - Publishers (' + date + ')',
                        autoFilter: true,
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'LMS - Publishers (' + date + ')',
                        filename: 'LMS - Publishers (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4, 5]
                        }
                    },
                ],
                order: []
            });

            $('#publisher-table').DataTable().on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('body').on('click', '#checkbox-all', function() {
                if($(this).is(':checked', true)) {
                    $(".sub-checkbox").prop('checked', true);
                } else {
                    $(".sub-checkbox").prop('checked', false);
                }
            });

            $('body').on('keyup', '#name, #email, #address, #website', function() {
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

            // form phone only number
            $('body').on('keypress', '#phone_number', function(e) {
                var keyCode = e.which ? e.which : e.keyCode;
                if(!(keyCode >= 48 && keyCode <= 57)) {
                    return false;
                } else {
                    return true;
                }
            });

            // Open Modal to Add new Publisher
            $('#btn-add').click(function() {
                $('#formModal').modal('show');
                $('.modal-title').html('Add Publisher');
                $('#publisher-form').trigger('reset');
                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                $('#publisher-form').find('.form-control').removeClass('is-invalid is-valid');
                $('#btn-save').val('save').removeAttr('disabled');
            });

            // Edit Publisher
            $('body').on('click', '#btn-edit', function() {
                var id = $(this).val();
                var publisher = $(this).data('name');
                $.get('{{ route('admin.publishers.index') }}' + '/' + id + '/edit', function(data) {
                    $('#publisher-form').find('.form-control').removeClass('is-invalid is-valid');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#email').val(data.email);
                    $('#address').val(data.address);
                    $('#phone_number').val(data.phone_number);
                    $('#website').val(data.website);
                    $('#btn-save').val('update').removeAttr('disabled');
                    $('#formModal').modal('show');
                    $('.modal-title').html('Update Publisher');
                    $('#btn-save').html('<i class="fas fa-check"></i> Update');
                }).fail(function() {
                    swal({
                        title: "Hooray!",
                        text: "Failed to get Publisher \n (name : " + publisher + ")",
                        icon: "error",
                        timer: 3000
                    });
                });
            });

            // Store new Publisher or update Publisher
            $('#btn-save').click(function() {
                var formData = {
                    name: $('#name').val(),
                    email: $('#email').val(),
                    address: $('#address').val(),
                    phone_number: $('#phone_number').val(),
                    website: $('#website').val(),
                };

                var state = $('#btn-save').val();
                var type = "POST";
                var ajaxurl = '{{ route('admin.publishers.store') }}';
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);

                if (state == "update") {
                    $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                    var id = $('#id').val();
                    type = "PUT";
                    ajaxurl = '{{ route('admin.publishers.store') }}' + '/' + id;
                }

                $.ajax({
                    type: type,
                    url: ajaxurl,
                    data: formData,
                    dataType: 'json',
                    success: function(data) {
                        if (state == "save") {
                            swal({
                                title: "Good Job!",
                                text: "Publisher was successfully added \n (name : " + formData.name + ")",
                                icon: "success",
                                timer: 3000
                            });

                            $('#publisher-table').DataTable().draw(false);
                            $('#publisher-table').DataTable().on('draw', function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        } else {
                            swal({
                                title: "Good Job!",
                                text: "Publisher was successfully updated \n (name : " + formData.name + ")",
                                icon: "success",
                                timer: 3000
                            });

                            $('#publisher-table').DataTable().draw(false);
                            $('#publisher-table').DataTable().on('draw', function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        }

                        $('#formModal').modal('hide');
                    },
                    error: function(data) {
                        try {
                            if (state == "save") {
                                if (data.responseJSON.errors.name) {
                                    $('#name').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-name').html(data.responseJSON.errors.name);
                                }

                                if (data.responseJSON.errors.email) {
                                    $('#email').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-email').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-email').html(data.responseJSON.errors.email);
                                }

                                if (data.responseJSON.errors.address) {
                                    $('#address').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-address').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-address').html(data.responseJSON.errors.address);
                                }

                                if (data.responseJSON.errors.phone_number) {
                                    $('#phone_number').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-phone_number').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-phone_number').html(data.responseJSON.errors.phone_number);
                                }

                                if (data.responseJSON.errors.website) {
                                    $('#website').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-website').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-website').html(data.responseJSON.errors.website);
                                }

                                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                                $('#btn-save').removeAttr('disabled');
                            } else {
                                if (data.responseJSON.errors.name) {
                                    $('#name').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-name').html(data.responseJSON.errors.name);
                                }

                                if (data.responseJSON.errors.email) {
                                    $('#email').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-email').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-email').html(data.responseJSON.errors.email);
                                }

                                if (data.responseJSON.errors.address) {
                                    $('#address').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-address').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-address').html(data.responseJSON.errors.address);
                                }

                                if (data.responseJSON.errors.phone_number) {
                                    $('#phone_number').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-phone_number').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-phone_number').html(data.responseJSON.errors.phone_number);
                                }

                                if (data.responseJSON.errors.website) {
                                    $('#website').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-website').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-website').html(data.responseJSON.errors.website);
                                }

                                $('#btn-save').html('<i class="fas fa-check"></i> Update');
                                $('#btn-save').removeAttr('disabled');
                            }
                        } catch {
                            if (state == "save") {
                                swal({
                                    title: "Hooray!",
                                    text: "Unknown error, reload the page",
                                    icon: "error",
                                    timer: 3000
                                });
                            } else {
                                swal({
                                    title: "Hooray!",
                                    text: "Something goes wrong \n (name : " + formData.name + ")",
                                    icon: "error",
                                    timer: 3000
                                });
                            }

                            $('#formModal').modal('hide');
                        }
                    }
                });
            });

            // Delete one Publisher
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
                                url: '{{ route('admin.publishers.store') }}' + '/' + id,
                                success: function(data) {
                                    $('#publisher-table').DataTable().draw(false);
                                    $('#publisher-table').DataTable().on('draw', function() {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    });

                                    swal({
                                        title: "Well Done!",
                                        text: "Publisher was successfully deleted \n (name : " + name + ")",
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

            // Delete all selected Publisher
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
                                    url: '{{ route('admin.publishers.deleteAllSelected') }}',
                                    data: {
                                        ids: ids,
                                    },
                                    success: function(data) {
                                        $('#publisher-table').DataTable().draw(false);
                                        $('#publisher-table').DataTable().on('draw', function() {
                                            $('[data-toggle="tooltip"]').tooltip();
                                        });

                                        swal({
                                            title: "Well Done!",
                                            text: "Selected publishers were successfully deleted",
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
