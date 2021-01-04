@extends('admin.layouts.master')
@section('title', 'Roles')

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
                    <form id="role-form">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name">Name <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Enter role name..." autocomplete="off">
                            <div class="invalid-feedback" id="valid-name"></div>
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
                <h1>Roles</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-address-book"></i>
                        Roles
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
                                Add Role
                            </button>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="role-table">
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
            $('#role-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.roles.index') }}',
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
                        title: 'LMS - Roles (' + date + ')',
                        filename: 'LMS - Roles (' + date + ')',
                        exportOptions: {
                            columns: [1, 2]
                        }
                    },
                    {
                        extend: 'csv',
                        title: 'LMS - Roles (' + date + ')',
                        filename: 'LMS - Roles (' + date + ')',
                        exportOptions: {
                            columns: [1, 2]
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'LMS - Roles (' + date + ')',
                        filename: 'LMS - Roles (' + date + ')',
                        autoFilter: true,
                        exportOptions: {
                            columns: [1, 2]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'LMS - Roles (' + date + ')',
                        filename: 'LMS - Roles (' + date + ')',
                        exportOptions: {
                            columns: [1, 2]
                        }
                    },
                ],
                order: []
            });

            $('#role-table').DataTable().on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('body').on('click', '#checkbox-all', function() {
                if($(this).is(':checked', true)) {
                    $(".sub-checkbox").prop('checked', true);
                } else {
                    $(".sub-checkbox").prop('checked', false);
                }
            });

            // key up function on form name
            $('body').on('keyup', '#name', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Open Modal to Add new Role
            $('#btn-add').click(function() {
                $('#formModal').modal('show');
                $('.modal-title').html('Add Role');
                $('#role-form').trigger('reset');
                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                $('#role-form').find('.form-control').removeClass('is-invalid is-valid');
                $('#btn-save').val('save').removeAttr('disabled');
            });

            // Edit Role
            $('body').on('click', '#btn-edit', function() {
                var id = $(this).val();
                var name = $(this).data('name');
                $.get('{{ route('admin.roles.index') }}' + '/' + id + '/edit', function(data) {
                    $('#role-form').find('.form-control').removeClass('is-invalid is-valid');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#btn-save').val('update').removeAttr('disabled');
                    $('#formModal').modal('show');
                    $('.modal-title').html('Update Role');
                    $('#btn-save').html('<i class="fas fa-check"></i> Update');
                }).fail(function() {
                    swal({
                        title: "Hooray!",
                        text: "Failed to get Role \n (name : " + name + ")",
                        icon: "error",
                        timer: 3000
                    });
                });
            });

            // Store new Role or update Role
            $('#btn-save').click(function() {
                var formData = {
                    name: $('#name').val(),
                };

                var state = $('#btn-save').val();
                var type = "POST";
                var ajaxurl = '{{ route('admin.roles.store') }}';
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);

                if (state == "update") {
                    $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                    var id = $('#id').val();
                    type = "PUT";
                    ajaxurl = '{{ route('admin.roles.store') }}' + '/' + id;
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
                                text: "Role was successfully added \n (name : " + formData.name + ")",
                                icon: "success",
                                timer: 3000
                            });

                            $('#role-table').DataTable().draw(false);
                            $('#role-table').DataTable().on('draw', function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        } else {
                            swal({
                                title: "Good Job!",
                                text: "Role was successfully updated \n (name : " + formData.name + ")",
                                icon: "success",
                                timer: 3000
                            });

                            $('#role-table').DataTable().draw(false);
                            $('#role-table').DataTable().on('draw', function() {
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

                                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                                $('#btn-save').removeAttr('disabled');
                            } else {
                                if (data.responseJSON.errors.name) {
                                    $('#name').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-name').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-name').html(data.responseJSON.errors.name);
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

            // Delete one Role
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
                                url: '{{ route('admin.roles.store') }}' + '/' + id,
                                success: function(data) {
                                    $('#role-table').DataTable().draw(false);
                                    $('#role-table').DataTable().on('draw', function() {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    });

                                    swal({
                                        title: "Well Done!",
                                        text: "Role was successfully deleted \n (name : " + name + ")",
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

            // Delete all selected Roles
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
                                    url: '{{ route('admin.roles.deleteAllSelected') }}',
                                    data: {
                                        ids: ids,
                                    },
                                    success: function(data) {
                                        $('#role-table').DataTable().draw(false);
                                        $('#role-table').DataTable().on('draw', function() {
                                            $('[data-toggle="tooltip"]').tooltip();
                                        });

                                        swal({
                                            title: "Well Done!",
                                            text: "Selected roles were successfully deleted",
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
