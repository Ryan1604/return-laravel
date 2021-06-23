@extends('admin.layouts.master')
@section('title', 'Data Perusahaan')

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
                    <form id="company-form">
                        <input type="hidden" name="id" id="id">
                        <div class="form-group">
                            <label for="name">Nama Perusahaan <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="name" name="name"
                                placeholder="Masukkan nama perusahaan..." autocomplete="off">
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
                <h1>Data Perusahaan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-industry"></i>
                        Data Perusahaan
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="card card-primary">
                    @if(auth()->user()->role_id === 1)
                        <div class="card-header">
                            <button class="btn btn-primary ml-auto" id="btn-add">
                                <i class="fas fa-plus-circle"></i>
                                Tambah Data
                            </button>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="company-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Nama Perusahaan</th>
                                        <th>Aksi</th>
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
            $('#company-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.company.index') }}',
                columns: [
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
                buttons: [],
                order: []
            });

            $('#company-table').DataTable().on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
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

            // Open Modal to Add new Company
            $('#btn-add').click(function() {
                $('#formModal').modal('show');
                $('.modal-title').html('Tambah Data');
                $('#company-form').trigger('reset');
                $('#btn-save').html('<i class="fas fa-check"></i> Simpan');
                $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                $('#btn-save').val('save').removeAttr('disabled');
            });

            // Edit company
            $('body').on('click', '#btn-edit', function() {
                var id = $(this).val();
                var name = $(this).data('name');
                $.get('{{ route('admin.company.index') }}' + '/' + id + '/edit', function(data) {
                    $('#company-form').find('.form-control').removeClass('is-invalid is-valid');
                    $('#id').val(data.id);
                    $('#name').val(data.name);
                    $('#btn-save').val('update').removeAttr('disabled');
                    $('#formModal').modal('show');
                    $('.modal-title').html('Edit Data');
                    $('#btn-save').html('<i class="fas fa-check"></i> Edit');
                }).fail(function() {
                    swal({
                        title: "Hooray!",
                        text: "Failed to get company \n (name : " + name + ")",
                        icon: "error",
                        timer: 3000
                    });
                });
            });

            // Store new company or update company
            $('#btn-save').click(function() {
                var formData = {
                    name: $('#name').val(),
                };

                var state = $('#btn-save').val();
                var type = "POST";
                var ajaxurl = '{{ route('admin.company.store') }}';
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);

                if (state == "update") {
                    $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                    var id = $('#id').val();
                    type = "PUT";
                    ajaxurl = '{{ route('admin.company.store') }}' + '/' + id;
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
                                text: "Company was successfully added \n (name : " + formData.name + ")",
                                icon: "success",
                                timer: 3000
                            });

                            $('#company-table').DataTable().draw(false);
                            $('#company-table').DataTable().on('draw', function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        } else {
                            swal({
                                title: "Good Job!",
                                text: "Company was successfully updated \n (name : " + formData.name + ")",
                                icon: "success",
                                timer: 3000
                            });

                            $('#company-table').DataTable().draw(false);
                            $('#company-table').DataTable().on('draw', function() {
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

            // Delete company
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
                                url: '{{ route('admin.company.store') }}' + '/' + id,
                                success: function(data) {
                                    $('#company-table').DataTable().draw(false);
                                    $('#company-table').DataTable().on('draw', function() {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    });

                                    swal({
                                        title: "Well Done!",
                                        text: "Company was successfully deleted \n (name : " + name + ")",
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
        });
    </script>
@endsection
