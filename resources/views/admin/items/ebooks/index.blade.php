@extends('admin.layouts.master')
@section('title', 'E-Books')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')
    <div class="modal fade" id="ebook-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">E-Book Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-lg-4 col-md-6">
                            <img class="img-thumbnail" id="book_cover" src="" alt="Book Cover">
                            <a id="ebook-link" class="btn btn-link" target="_blank">
                                <i class="fa fa-external-link-alt"></i>
                                Read PDF
                            </a>
                        </div>
                        <div class="col-lg-8 col-md-6 col-sm-12 col-12">
                            <p id="isbn">ISBN 2331324532</p>
                            <h4 id="title" class="font-weight-bold">Judul</h4>
                            <p id="authors">Authors</p>
                            <p class="badge badge-pill" id="status"></p>
                            <hr>
                            <div class="row user-stats text-center">
                            </div>
                            <hr>
                            <ul class="nav nav-tabs">
                                <li class="nav-item">
                                    <a class="nav-link" id="pills-home-tab-nobd" data-toggle="pill" href="#pills-home-nobd">
                                        <i class="fa fa-info-circle"></i>
                                        About this e-book
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
                                    <div id="category">
                                        <h6 class="font-weight-bold">Category</h6>
                                        <p></p>
                                    </div>
                                    <div id="publisher">
                                        <h6 class="font-weight-bold">Publisher</h6>
                                        <p></p>
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
                <h1>E-Books</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-file-pdf"></i>
                        E-Books
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
                            <a class="btn btn-primary ml-auto" href="{{ route('admin.items.ebooks.create') }}">
                                <i class="fas fa-plus-circle"></i>
                                Add E-Book
                            </a>
                        </div>
                    @endif
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="ebook-table">
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
                var ebook = $(this).data('ebook');
                $.get('{{ route('admin.items.ebooks.index') }}' + '/' + id, function(data) {
                    $('#pills-home-tab-nobd').addClass('active show');
                    $('#pills-home-nobd').addClass('active show');
                    $('#pills-profile-tab-nobd').removeClass('active show');
                    $('#pills-profile-nobd').removeClass('active show');
                    $('#ebook-link').attr('href', '{{ asset('pdfs') }}' + '/' + data.ebook_url);
                    $('#book_cover').attr('src', '{{ asset('img/ebooks') }}' + '/' + data.book_cover_url);
                    $('#isbn').html('ISBN ' + data.isbn);
                    $('#title').html(data.title);

                    if(data.code) {
                        if($('#code').length === 0) {
                            $('#isbn').after('<p id="code">' + data.code + '</p>');
                        }
                    } else {
                        $('#code').remove();
                    }

                    if(data.year) {
                        if($('#year_parent').length === 0) {
                            $('.user-stats').append('<div class="col" id="year_parent"><div class="number" id="year">' + data.year + '</div><div class="title">Year</div></div>');
                        }
                    } else {
                        $('.user-stats #year_parent').remove();
                    }

                    if(data.pages) {
                        if($('#pages_parent').length === 0) {
                            $('.user-stats').append('<div class="col" id="pages_parent"><div class="number" id="pages">' + data.pages + '</div><div class="title">Pages</div></div>');
                        }
                    } else {
                        $('.user-stats #pages_parent').remove();
                    }

                    if(data.edition) {
                        if($('#edition_parent').length === 0) {
                            $('.user-stats').append('<div class="col" id="edition_parent"><div class="number" id="edition">' + data.edition + '</div><div class="title">Edition</div></div>');
                        }
                    } else {
                        $('.user-stats #edition_parent').remove();
                    }

                    if(data.description) {
                        $('#desc p').html(data.description);
                    } else {
                        $('#desc p').html('No Description');
                    }

                    if(data.table_of_contents) {
                        if($('#toc').length === 0) {
                            $('#desc').after('<div id="toc"><h6 class="font-weight-bold">Table of Contents</h6><p>' + data.table_of_contents + '</p></div>');
                        }
                    } else {
                        $('#toc').remove();
                    }

                    if(data.disabled === '0') {
                        $('#status').removeClass('badge-danger').addClass('badge-success').html('Active');
                    } else {
                        $('#status').removeClass('badge-success').addClass('badge-danger').html('Inactive');
                    }

                    var authors = '';
                    for (var i = 0; i < data.authors.length; i++) {
                        authors += data.authors[i].name + ', ';
                    }

                    $('#authors').html(authors);

                    if(data.category_id !== null) {
                        $('#category p').html(data.category.name);
                    } else {
                        $('#category p').html('UNCLASSIFIED');
                    }

                    if(data.publisher_id !== null) {
                        $('#publisher p').html(data.publisher.name);
                    } else {
                        $('#publisher p').html('UNCLASSIFIED');
                    }

                    $('#ebook-modal').modal('show');
                }).fail(function() {
                    swal({
                        title: "Hooray!",
                        text: "Failed to get E-Book \n (title : " + ebook + ")",
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
            $('#ebook-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.items.ebooks.index') }}',
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
                        className: 'text-nowrap',
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
                        title: 'LMS - E-Books (' + date + ')',
                        filename: 'LMS - E-Books (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                    {
                        extend: 'csv',
                        title: 'LMS - E-Books (' + date + ')',
                        filename: 'LMS - E-Books (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'LMS - E-Books (' + date + ')',
                        filename: 'LMS - E-Books (' + date + ')',
                        autoFilter: true,
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'LMS - E-Books (' + date + ')',
                        filename: 'LMS - E-Books (' + date + ')',
                        exportOptions: {
                            columns: [1, 2, 3]
                        }
                    },
                ],
                order: []
            });

            $('#ebook-table').DataTable().on('draw', function() {
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
                var ebook = $(this).data('ebook');
                swal("Whoops!", "Are you sure want to delete? \n (title : " + ebook + ")", "warning", {
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
                                    $('#ebook-table').DataTable().draw(false);
                                    $('#ebook-table').DataTable().on('draw', function() {
                                        $('[data-toggle="tooltip"]').tooltip();
                                    });

                                    swal({
                                        title: "Well Done!",
                                        text: "Item was successfully deleted \n (title : " + ebook + ")",
                                        icon: "success",
                                        timer: 3000
                                    });
                                },
                                error: function(data) {
                                    swal({
                                        title: "Hooray!",
                                        text: "Something goes wrong \n (title : " + ebook + ")",
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

            // Delete all selected item ebooks
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
                                        $('#ebook-table').DataTable().draw(false);
                                        $('#ebook-table').DataTable().on('draw', function() {
                                            $('[data-toggle="tooltip"]').tooltip();
                                        });

                                        swal({
                                            title: "Well Done!",
                                            text: "Selected ebooks were successfully deleted",
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
