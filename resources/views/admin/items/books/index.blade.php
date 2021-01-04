@extends('admin.layouts.master')
@section('title', 'Books')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
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
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Books</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-book"></i>
                        Books
                    </div>
                </div>
            </div>

            @if (session('success'))
                <div class="alert alert-primary alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>×</span>
                        </button>
                        {!! session('success') !!}
                    </div>
                </div>
            @endif

            <div class="section-body">
                <div class="card card-primary">
                    @if(auth()->user()->isLibrarian())
                        <div class="card-header">
                            <button class="btn btn-danger" id="btn-delete-all">
                                <i class="fas fa-trash-alt"></i>
                                Delete All Selected
                            </button>
                            <a class="btn btn-primary ml-auto" href="{{ route('admin.items.books.create') }}">
                                <i class="fas fa-plus-circle"></i>
                                Add Book
                            </a>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="book-table">
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
                                        <th>ISBN</th>
                                        <th>Title</th>
                                        <th>Category</th>
                                        <th>Rack</th>
                                        <th>Action</th>
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

            $('body').on('click', '#btn-detail', function() {
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
            $('#book-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.items.books.index') }}',
                columns: [
                    {
                        data: 'check',
                        name: 'check',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'isbn',
                        name: 'isbn',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'title',
                        name: 'title'
                    },
                    {
                        data: 'category',
                        name: 'category',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'rack',
                        name: 'rack',
                        className: 'text-nowrap'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        className: 'text-center',
                        orderable: false,
                        searchable: false,
                    }
                ],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'print',
                        title: 'LMS - Books (' + date + ')',
                        filename: 'LMS - Books (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'csv',
                        title: 'LMS - Books (' + date + ')',
                        filename: 'LMS - Books (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'LMS - Books (' + date + ')',
                        filename: 'LMS - Books (' + date + ')',
                        autoFilter: true,
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'LMS - Books (' + date + ')',
                        filename: 'LMS - Books (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3, 4]
                        }
                    },
                ],
                order: []
            });

            $('#book-table').DataTable().on('draw', function() {
                $('[data-toggle="tooltip"]').tooltip();
            });

            $('body').on('click', '#checkbox-all', function() {
                if($(this).is(':checked', true)) {
                    $(".sub-checkbox").prop('checked', true);
                } else {
                    $(".sub-checkbox").prop('checked', false);
                }
            });

            // Delete one item
            $('body').on('click', '#btn-delete', function(){
                var id = $(this).val();
                var book = $(this).data('book');
                swal("Whoops!", "Are you sure want to delete? \n (title : " + book + ")", "warning", {
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
                                url: '{{ url('admin/items') }}' + '/' + id,
                                success: function(data) {
                                    $('#book-table').DataTable().draw(false);
                                    $('#book-table').DataTable().on('draw', function() {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    });

                                    swal({
                                        title: "Well Done!",
                                        text: "Item was successfully deleted \n (title : " + book + ")",
                                        icon: "success",
                                        timer: 3000
                                    });
                                },
                                error: function(data) {
                                    swal({
                                        title: "Hooray!",
                                        text: "Something goes wrong \n (title : " + book + ")",
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

            // Delete all selected item books
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
                                    url: '{{ route('admin.items.deleteAllSelected') }}',
                                    data: {
                                        ids: ids,
                                    },
                                    success: function(data) {
                                        $('#book-table').DataTable().draw(false);
                                        $('#book-table').DataTable().on('draw', function() {
                                            $('[data-toggle="tooltip"]').tooltip();
                                        });

                                        swal({
                                            title: "Well Done!",
                                            text: "Selected books were successfully deleted",
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
