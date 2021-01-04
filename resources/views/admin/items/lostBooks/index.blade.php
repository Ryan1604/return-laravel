@extends('admin.layouts.master')
@section('title', 'Books')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
    <link rel="stylesheet" href="{{ asset('backend/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="modal fade" id="book-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Book Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4 col-sm-4 col-4">
                            <img class="img-thumbnail" id="book_cover" src="" alt="Book Cover">
                        </div>
                        <div class="col-md-8 col-sm-8 col-8">
                            <p id="isbn">ISBN 2331324532</p>
                            <h4 id="title" class="font-weight-bold">Judul</h4>
                            <p id="authors">Authors</p>
                            <p class="badge badge-pill" id="status"></p>
                            <hr>
                            <div class="row user-stats text-center">
                                <div class="col" id="stocks_parent">
                                    <div class="number" id="stocks">25K</div>
                                    <div class="title">Stocks</div>
                                </div>
                                <div class="col" id="borrowed_parent">
                                    <div class="number" id="borrowed">134</div>
                                    <div class="title">Borrowed</div>
                                </div>
                            </div>
                            <hr>
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-home-tab-nobd" data-toggle="pill" href="#pills-home-nobd">
                                        <i class="fa fa-info-circle"></i>
                                        About this book
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-profile-tab-nobd" data-toggle="pill" href="#pills-profile-nobd">
                                        <i class="fa fa-ellipsis-h"></i>
                                        More info
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content mt-2 mb-3" id="pills-without-border-tabContent">
                                <div class="tab-pane fade" id="pills-home-nobd" role="tabpanel" aria-labelledby="pills-home-tab-nobd">
                                    <div class="row">
                                        <div id="available" class="col-md-6">
                                            <h6 class="font-weight-bold">Ebook Available</h6>
                                            <p></p>
                                        </div>
                                        <div id="category" class="col-md-6">
                                            <h6 class="font-weight-bold">Category</h6>
                                            <p></p>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div id="publisher" class="col-md-6">
                                            <h6 class="font-weight-bold">Publisher</h6>
                                            <p></p>
                                        </div>
                                        <div id="rack" class="col-md-6">
                                            <h6 class="font-weight-bold">Rack</h6>
                                            <p></p>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="pills-profile-nobd" role="tabpanel" aria-labelledby="pills-profile-tab-nobd">
                                    <div id="desc">
                                        <h6 class="font-weight-bold">Description</h6>
                                        <p></p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="formModal" role="dialog" aria-hidden="true">
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
                    <form id="lost-book-form">
                        <div class="form-group">
                            <label for="book">Book <sup class="text-danger">*</sup></label>
                            <select class="select2 form-control" id="book" name="book">
                                @foreach ($books as $book)
                                    <option value="{{ $book->id }}">ISBN {{ $book->isbn }} {{ $book->code ? ' - ' . $book->code : '' }} - {{ $book->title }}</option>
                                @endforeach
                            </select>
                            <div class="invalid-feedback" id="valid-book"></div>
                        </div>
                        <div class="form-group">
                            <label for="qty_lost">Qty Lost</label>
                            <input type="number" class="form-control" id="qty_lost" name="qty_lost" placeholder="Enter QTY lost book..." autocomplete="off" min="0">
                            <div class="invalid-feedback" id="valid-qty_lost"></div>
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
                <h1>Lost Books</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-book"></i>
                        Lost Books
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="card card-primary">
                    @if(auth()->user()->isLibrarian())
                        <div class="card-header">
                            <button class="btn btn-primary" id="btn-add-new-lost-book">
                                <i class="fas fa-plus-circle"></i>
                                Add New Lost Book
                            </button>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="lost-book-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>#</th>
                                        <th>Book</th>
                                        <th>Qty Lost</th>
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
    <script src="{{ asset('backend/modules/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('body').on('click', '#btn-book', function() {
                var id = $(this).val();
                var book = $(this).data('book');
                $.get('{{ route('admin.items.books.index') }}' + '/' + id, function(res) {
                    $('#pills-home-tab-nobd').addClass('active show');
                    $('#pills-home-nobd').addClass('active show');
                    $('#pills-profile-tab-nobd').removeClass('active show');
                    $('#pills-profile-nobd').removeClass('active show');
                    $('#book_cover').attr('src', '{{ asset('img/books') }}' + '/' + res.data.book_cover_url);
                    $('#isbn').html('ISBN ' + res.data.isbn);
                    $('#title').html(res.data.title);

                    if(res.data.code) {
                        if($('#code').length === 0) {
                            $('#isbn').after('<p id="code">' + res.data.code + '</p>');
                        }
                    } else {
                        $('#code').remove();
                    }

                    if(res.data.year) {
                        if($('#year_parent').length === 0) {
                            $('.user-stats').find('#stocks_parent').before('<div class="col" id="year_parent"><div class="number" id="year">' + res.data.year + '</div><div class="title">Year</div></div>');
                        }
                    } else {
                        $('.user-stats #year_parent').remove();
                    }

                    if(res.data.pages) {
                        if($('#pages_parent').length === 0) {
                            $('.user-stats').find('#stocks_parent').before('<div class="col" id="pages_parent"><div class="number" id="pages">' + res.data.pages + '</div><div class="title">Pages</div></div>');
                        }
                    } else {
                        $('.user-stats #pages_parent').remove();
                    }

                    if(res.data.edition) {
                        if($('#edition_parent').length === 0) {
                            $('.user-stats').find('#stocks_parent').before('<div class="col" id="edition_parent"><div class="number" id="edition">' + res.data.edition + '</div><div class="title">Edition</div></div>');
                        }
                    } else {
                        $('.user-stats #edition_parent').remove();
                    }

                    if(res.data.ebook_available === '0') {
                        $('#available p').html('No');
                    } else {
                        var url = '{{ url('') }}';
                        $('#available p').html('Yes, <a target="_blank" href="' + url + '/pdfs/' + res.data.ebook_url + '"><i class="fas fa-external-link-alt"></i> Read PDF</a>');
                    }

                    if(res.data.description) {
                        $('#desc p').html(res.data.description);
                    } else {
                        $('#desc p').html('No Description');
                    }

                    if(res.data.table_of_contents) {
                        if($('#toc').length === 0) {
                            $('#desc').after('<div id="toc"><h6 class="font-weight-bold">Table of Contents</h6><p>' + res.data.table_of_contents + '</p></div>');
                        }
                    } else {
                        $('#toc').remove();
                    }

                    if(res.data.disabled === '0') {
                        $('#status').removeClass('badge-danger').addClass('badge-success').html('Active');
                    } else {
                        $('#status').removeClass('badge-success').addClass('badge-danger').html('Inactive');
                    }

                    $('#stocks').html(res.data.total_qty - res.data.qty_lost);
                    $('#borrowed').html(res.borrowed);

                    var authors = '';
                    for (var i = 0; i < res.data.authors.length; i++) {
                        authors += res.data.authors[i].name + ', ';
                    }

                    $('#authors').html(authors);

                    if(res.data.category_id !== null) {
                        $('#category p').html(res.data.category.name);
                    } else {
                        $('#category p').html('UNCLASSIFIED');
                    }

                    if(res.data.publisher_id !== null) {
                        $('#publisher p').html(res.data.publisher.name);
                    } else {
                        $('#publisher p').html('UNCLASSIFIED');
                    }

                    if(res.data.rack_id !== null) {
                        $('#rack p').html(res.data.rack.position + ' - ' + res.data.rack.category.name);
                    } else {
                        $('#rack p').html('UNCLASSIFIED');
                    }

                    $('#book-modal').modal('show');
                }).fail(function() {
                    swal({
                        title: "Hooray!",
                        text: "Failed to get Book \n (title : " + book + ")",
                        icon: "error",
                        timer: 3000
                    });
                });
            });

            var d = new Date();

            var day = d.getDate();
            var month = d.getMonth();
            var year = d.getFullYear();

            var date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + year;

            // Initializing DataTable
            $('#lost-book-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.items.lostBooks.index') }}',
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'book',
                        name: 'book'
                    },
                    {
                        data: 'qty_lost',
                        name: 'qty_lost',
                        className: 'text-center',
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
                        title: 'LMS - Lost Books (' + date + ')',
                        filename: 'LMS - Lost Books (' + date + ')',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'csv',
                        title: 'LMS - Lost Books (' + date + ')',
                        filename: 'LMS - Lost Books (' + date + ')',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'LMS - Lost Books (' + date + ')',
                        filename: 'LMS - Lost Books (' + date + ')',
                        autoFilter: true,
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'LMS - Lost Books (' + date + ')',
                        filename: 'LMS - Lost Books (' + date + ')',
                        exportOptions: {
                            columns: [0, 1, 2]
                        }
                    },
                ],
                order: []
            });

            $('#lost-book-table').DataTable().on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            // Add lost book
            $('body').on('click', '#btn-add-new-lost-book', function() {
                $('#formModal').modal('show');
                $('.modal-title').html('Add New Lost Book');
                $('#lost-book-form').trigger('reset');
                $('#book').val('');
                $('#book').select2({
                    dropdownParent: $('#formModal'),
                    width: '100%'
                });
                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                $('#lost-book-form').find('.form-control').removeClass('is-invalid is-valid');
                $('#btn-save').val('save').removeAttr('disabled');
            });

            // Edit Lost book
            $('body').on('click', '#btn-edit-lost-book', function() {
                var id = $(this).val();
                var title = $(this).data('book');
                $.get('{{ route('admin.items.lostBooks.index') }}' + '/' + id + '/edit', function(data) {
                    $('#lost-book-form').find('.form-control').removeClass('is-invalid is-valid');
                    $('#book').val(data.id);
                    $('#book').select2({
                        dropdownParent: $('#formModal'),
                        width: '100%'
                    });
                    $('#qty_lost').val(data.qty_lost);
                    $('#btn-save').val('update').removeAttr('disabled');
                    $('#formModal').modal('show');
                    $('.modal-title').html('Edit Lost Book');
                    $('#btn-save').html('<i class="fas fa-check"></i> Update');
                }).fail(function() {
                    swal({
                        title: "Hooray!",
                        text: "Failed to get Book \n (title : " + title + ")",
                        icon: "error",
                        timer: 3000
                    });
                });
            });

            // Store new Lost Book or update Lost Book
            $('#btn-save').click(function() {
                var title = $('#btn-edit-lost-book').data('book');
                var state = $('#btn-save').val();
                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);

                if (state == "update") {
                    $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
                }

                $.ajax({
                    type: 'PUT',
                    url: '{{ route('admin.items.lostBooks.index') }}',
                    data: {
                        book_id: $('#book').val(),
                        qty_lost: $('#qty_lost').val(),
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (state == "save") {
                            swal({
                                title: "Good Job!",
                                text: "Lost Book was successfully added \n (title : " + data.title + ")",
                                icon: "success",
                                timer: 3000
                            });

                            $('#lost-book-table').DataTable().draw(false);
                            $('#lost-book-table').DataTable().on('draw', function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        } else {
                            swal({
                                title: "Good Job!",
                                text: "Lost Book was successfully updated \n (title : " + data.title + ")",
                                icon: "success",
                                timer: 3000
                            });

                            $('#lost-book-table').DataTable().draw(false);
                            $('#lost-book-table').DataTable().on('draw', function() {
                                $('[data-toggle="tooltip"]').tooltip();
                            });
                        }

                        $('#formModal').modal('hide');
                    },
                    error: function(data) {
                        try {
                            if (state == "save") {
                                if (data.responseJSON.errors.book_id) {
                                    $('#book').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-book').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-book').html(data.responseJSON.errors.book_id);
                                }

                                if (data.responseJSON.errors.qty_lost) {
                                    $('#qty_lost').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-qty_lost').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-qty_lost').html(data.responseJSON.errors.qty_lost);
                                }

                                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                                $('#btn-save').removeAttr('disabled');
                            } else {
                                if (data.responseJSON.errors.book_id) {
                                    $('#book').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-book').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-book').html(data.responseJSON.errors.book_id);
                                }

                                if (data.responseJSON.errors.qty_lost) {
                                    $('#qty_lost').removeClass('is-valid').addClass('is-invalid');
                                    $('#valid-qty_lost').removeClass('valid-feedback').addClass('invalid-feedback');
                                    $('#valid-qty_lost').html(data.responseJSON.errors.qty_lost);
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
                                    text: "Something goes wrong \n (title : " + title + ")",
                                    icon: "error",
                                    timer: 3000
                                });
                            }

                            $('#formModal').modal('hide');
                        }
                    }
                });
            });
        });
    </script>
@endsection
