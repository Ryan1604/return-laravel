@extends('admin.layouts.master')
@section('title', 'Data Claim Customer')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/datatables/datatables.min.css') }}">
@endsection

@section('content')

    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Laporan</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-file-pdf"></i>
                        Laporan
                    </div>
                </div>
            </div>

            <div class="section-body">
                <div class="card card-primary">  
                    <div class="card-header">
                        <a class="btn btn-primary ml-auto" href="{{ route('admin.pdf') }}">
                            <i class="fas fa-download"></i>
                            Download PDF
                        </a>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover" id="ebook-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Customer</th>
                                        <th>No Assy</th>
                                        <th>Part Name</th>
                                        <th>Description</th>
                                        <th>Tanggal Claim</th>
                                        <th>Foto</th>
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
            $('#ebook-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: '{{ route('admin.products.index') }}',
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false,
                        autoWidth: true
                    },
                    {
                        data: 'company',
                        name: 'company',
                        className: 'text-nowrap',
                        autoWidth: true
                    },
                    {
                        data: 'assy_number',
                        name: 'assy_number',
                        autoWidth: true
                    },
                    {
                        data: 'name',
                        name: 'name',
                        autoWidth: true
                    },
                    {
                        data: 'desc',
                        name: 'desc',
                        autoWidth: true
                    },
                    {
                        data: 'created_at',
                        name: 'date',
                        autoWidth: true
                    },
                    {
                        data: 'photo',
                        name: 'photo'
                    },
                ],
                
            });
        });
    </script>
@endsection
