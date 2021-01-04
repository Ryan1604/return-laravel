@extends('admin.layouts.master')
@section('title', 'Returns')

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

    <div class="modal fade" id="user-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">User Detail</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row justify-content-center mb-2">
                        <div class="avatar avatar-xl">
                            <img src="" alt="User Profile" id="user_profile" class="avatar-img rounded-circle">
                        </div>
                    </div>
                    <div class="d-flex justify-content-center mb-2">
                        <p id="role" class="badge badge-primary mx-auto"></p>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Serial Number</h6>
                            <p id="sn"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Username</h6>
                            <p id="username"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Name</h6>
                            <p id="name"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Email</h6>
                            <p id="email"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Date of Birth</h6>
                            <p id="dob"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Phone Number</h6>
                            <p id="phone"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Gender</h6>
                            <p id="gender"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Address</h6>
                            <p id="address"></p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Faculty</h6>
                            <p id="faculty"></p>
                        </div>
                        <div class="col-md-6 col-sm-6 col-6">
                            <h6 class="font-weight-bold">Status</h6>
                            <p class="badge badge-pill" id="status"></p>
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
                <h1>Returns</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fas fa-exchange-alt"></i>
                        Returns
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="card card-primary">
                    <div class="card-body">
                        <div class="table-responsive">
                            <div class="row">
                                <div class="col-md-3 col-sm-5">
                                    <select class="select2 form-control" id="filter">
                                        <option value="" selected>Show All</option>
                                        @foreach ($filter as $date)
                                            <option value="{{ $date['return_date'] }}">{{ date('d/m/Y', strtotime($date['return_date'])) }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 col-sm-3">
                                    <button type="button" id="btn-filter" class="btn btn-primary">Filter</button>
                                </div>
                            </div>
                            <br>
                            <table class="table table-sm table-hover" id="issue-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>User</th>
                                        <th>Book</th>
                                        <th>Borrow Date</th>
                                        <th>Return Date</th>
                                        <th>Status</th>
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
    <script src="{{ asset('backend/modules/select2/dist/js/select2.full.min.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%',
            });

            var d = new Date();

            var day = d.getDate();
            var month = d.getMonth();
            var year = d.getFullYear();

            var date = (day<10 ? '0' : '') + day + '-' + (month<10 ? '0' : '') + month + '-' + year;

            $('body').on('click', '#btn-book', function() {
                var id = $(this).val();
                var book = $(this).data('book');
                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin.issues.fetchBook') }}',
                    data: {
                        book_id: id,
                    },
                    dataType: 'json',
                    success: function(res) {
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
                    },
                    error: function(data) {
                        swal({
                            title: "Hooray!",
                            text: "Failed to get Book (title : " + book + ")",
                            icon: "error",
                            timer: 3000
                        });
                    }
                });
            });

            $('body').on('click', '#btn-user', function() {
                var id = $(this).val();
                var name = $(this).data('user');
                $.ajax({
                    type: 'GET',
                    url: '{{ route('admin.issues.fetchUser') }}',
                    data: {
                        user_id: id,
                    },
                    dataType: 'json',
                    success: function(data) {
                        $('.modal-title').html('User Detail');
                        $('#user_profile').attr('src', '{{ asset('img/users') }}' + '/' + data.profile_url);
                        $('#sn').html(data.sn);
                        $('#username').html(data.username);
                        $('#name').html(data.name);
                        $('#email').html(data.email);
                        $('#dob').html(data.dob);
                        $('#phone').html(data.phone_number);
                        if(data.gender == 'M') {
                            $('#gender').html('Male');
                        }
                        if(data.gender == 'F') {
                            $('#gender').html('Female');
                        }
                        $('#address').html(data.address);
                        if(data.faculty_id) {
                            $('#faculty').html(data.faculty.name);
                        } else {
                            $('#faculty').html('UNCLASSIFIED');
                        }
                        $('#role').html(data.role.name);
                        if(data.disabled === '0') {
                            $('#user-modal #status').removeClass('badge-danger').addClass('badge-success').html('Active');
                        } else {
                            $('#user-modal #status').removeClass('badge-success').addClass('badge-danger').html('Inactive');
                        }
                        $('#user-modal').modal('show');
                    },
                    error: function(data) {
                        swal({
                            title: "Hooray!",
                            text: "Failed to get User (name : " + name + ")",
                            icon: "error",
                            timer: 3000
                        });
                    }
                });
            });

            $('#btn-filter').click(function() {
                $('#issue-table').DataTable().destroy();

                $('#issue-table').DataTable({
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: '{{ route('admin.issues.returns.index') }}',
                        type: 'GET',
                        data: {
                            filter: $('#filter').val()
                        },
                    },
                    columns: [
                        {
                            data: 'user',
                            name: 'user'
                        },
                        {
                            data: 'book',
                            name: 'book'
                        },
                        {
                            data: 'borrow',
                            name: 'borrow'
                        },
                        {
                            data: 'return',
                            name: 'return'
                        },
                        {
                            data: 'status',
                            name: 'status',
                            className: 'text-center',
                            orderable: false,
                            searchable: false
                        },
                    ],
                    dom: 'lBfrtip',
                    buttons: [
                        {
                            extend: 'print',
                            title: 'LMS - Return (' + date + ')',
                            filename: 'LMS - Return (' + date + ')',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'csv',
                            title: 'LMS - Return (' + date + ')',
                            filename: 'LMS - Return (' + date + ')',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'excel',
                            title: 'LMS - Return (' + date + ')',
                            filename: 'LMS - Return (' + date + ')',
                            autoFilter: true,
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                        {
                            extend: 'pdf',
                            title: 'LMS - Return (' + date + ')',
                            filename: 'LMS - Return (' + date + ')',
                            exportOptions: {
                                columns: ':visible'
                            }
                        },
                    ],
                    order: [
                        [ 3, "desc" ]
                    ]
                });
            });

            // Initializing DataTable
            $('#issue-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.issues.returns.index') }}',
                columns: [
                    {
                        data: 'user',
                        name: 'user'
                    },
                    {
                        data: 'book',
                        name: 'book'
                    },
                    {
                        data: 'borrow',
                        name: 'borrow'
                    },
                    {
                        data: 'return',
                        name: 'return'
                    },
                    {
                        data: 'status',
                        name: 'status',
                        className: 'text-center',
                        orderable: false,
                        searchable: false
                    },
                ],
                dom: 'lBfrtip',
                buttons: [
                    {
                        extend: 'print',
                        title: 'LMS - Return (' + date + ')',
                        filename: 'LMS - Return (' + date + ')',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'csv',
                        title: 'LMS - Return (' + date + ')',
                        filename: 'LMS - Return (' + date + ')',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'excel',
                        title: 'LMS - Return (' + date + ')',
                        filename: 'LMS - Return (' + date + ')',
                        autoFilter: true,
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                    {
                        extend: 'pdf',
                        title: 'LMS - Return (' + date + ')',
                        filename: 'LMS - Return (' + date + ')',
                        exportOptions: {
                            columns: ':visible'
                        }
                    },
                ],
                order: [
                    [ 3, "desc" ]
                ]
            });
        });
    </script>
@endsection
