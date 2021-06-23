@extends('admin.layouts.master')
@section('title', 'Add Data')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/select2/dist/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Tambah Data</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.products.index') }}">
                            <i class="fa fa-file-pdf"></i>
                            Data Claim Customer
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-plus-circle"></i>
                        Create
                    </div>
                </div>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Tentang Produk</h4>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" name="type" value="e-book">
                                    <div class="text-danger" id="valid-type">{{ $errors->first('type') }}</div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="no_assy">No Assy <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control form-control-sm @error('no_assy') is-invalid @enderror" name="no_assy" id="no_assy" value="{{ old('no_assy') }}" placeholder="Input here...">
                                                <div class="invalid-feedback" id="valid-title">{{ $errors->first('no_assy') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="name">Nama Produk <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control form-control-sm @error('name') is-invalid @enderror" name="name" id="name" value="{{ old('name') }}" placeholder="Input here...">
                                                <div class="invalid-feedback" id="valid-title">{{ $errors->first('name') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="description">Deskripsi</label>
                                                <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" name="description" id="description" placeholder="Input here..." rows="5">{{ old('description') }}</textarea>
                                                <small>Contoh: Masalah - Tanggal Produksi</small>
                                                <div class="invalid-feedback" id="valid-description">{{ $errors->first('description') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12 col-sm-12">
                                            <div class="form-group">
                                                <label for="photo">Foto <sup class="text-danger">max : 2MB</sup></label>
                                                <input type="file" class="form-control-file @error('photo') is-invalid @enderror" id="photo" name="photo">
                                                <div class="invalid-feedback" id="valid-book_cover_url">{{ $errors->first('photo') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Lainnya</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="company">Perusahaan</label>
                                        <select class="select2 form-control form-control-sm @error('companies_id') is-invalid @enderror" name="companies_id" id="company">
                                            <option value="" selected>-- Pilih Perusahaan --</option>
                                                @foreach ($companies as $company)
                                                    <option value="{{ $company->id }}" {{ old('companies_id') == $company->id ? 'selected' : '' }}>{{ $company->name }}</option>
                                                @endforeach
                                        </select>
                                        <div class="invalid-feedback" id="valid-category">{{ $errors->first('companies_id') }}</div>
                                    </div>
                                    
                                </div>
                            </div>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-link float-left">
                                <i class="fas fa-arrow-left"></i>
                                Kembali
                            </a>
                            <button type="submit" class="btn btn-primary btn-round float-right" id="btn-submit">
                                <i class="fas fa-check"></i>
                                Simpan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/modules/select2/dist/js/select2.full.min.js') }}"></script>
    <script src="{{ asset('backend/js/jquery.mask.min.js') }}"></script>
    <script src="{{ asset('backend/modules/sweetalert/sweetalert.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            // Setup AJAX CSRF
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            $('.select2').on('select2:selecting', function() {
                $(this).removeClass('is-invalid');
            });


            $('body').on('keyup', '#name, #description, #table_of_contents', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            function filePreview2(input) {
                if(input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#book_cover_url + img').remove();
                        $('#book_cover_url').after('<img src="' + e.target.result + '" class="img-thumbnail">');
                    };
                    reader.readAsDataURL(input.files[0]);
                };
            }

            $('#book_cover_url').change(function() {
                filePreview2(this);
                $('#valid-book_cover_url').html('');
            });

            $('form').submit(function() {
                $('#btn-submit').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);
            });
        })
    </script>
@endsection
