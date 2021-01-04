@extends('admin.layouts.master')
@section('title', 'Edit Book')

@section('css')
    <link rel="stylesheet" href="{{ asset('backend/modules/select2/dist/css/select2.min.css') }}">
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
                    <form id="author-form">
                        <div class="form-group">
                            <label for="name">Name <sup class="text-danger">*</sup></label>
                            <input type="text" class="form-control" id="name" name="name" placeholder="Enter author name..."
                                autocomplete="off">
                            <div class="invalid-feedback" id="valid-name"></div>
                        </div>
                        <div class="form-group">
                            <label for="email">Email <sup class="text-danger">*</sup></label>
                            <input type="email" class="form-control" id="email" name="email"
                                placeholder="Enter author email..." autocomplete="off">
                            <div class="invalid-feedback" id="valid-email"></div>
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

    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Edit Book</h1>
                <div class="section-header-breadcrumb">
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.dashboard') }}">
                            <i class="fa fa-home"></i>
                            Dashboard
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <a href="{{ route('admin.items.books.index') }}">
                            <i class="fa fa-book"></i>
                            Books
                        </a>
                    </div>
                    <div class="breadcrumb-item">
                        <i class="fa fa-edit"></i>
                        Edit
                    </div>
                </div>
            </div>
            <div class="section-body">
                <form method="POST" action="{{ route('admin.items.books.update', $book->id) }}" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-lg-8">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">About Book</h4>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" id="book_id" value="{{ $book->id }}">
                                    <input type="hidden" name="type" value="book">
                                    <div class="text-danger" id="valid-type">{{ $errors->first('type') }}</div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="isbn">ISBN <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control form-control-sm @error('isbn') is-invalid @enderror" name="isbn" id="isbn" value="@error('isbn'){{ old('isbn') }}@else{{ $book->isbn }}@enderror" placeholder="XXX X XXX XXXXX X" autocomplete="off">
                                                <div class="invalid-feedback" id="valid-isbn">{{ $errors->first('isbn') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="title">Title <sup class="text-danger">*</sup></label>
                                                <input type="text" class="form-control form-control-sm @error('title') is-invalid @enderror" name="title" id="title" value="@error('title'){{ old('title') }}@else{{ $book->title }}@enderror" placeholder="Input here...">
                                                <div class="invalid-feedback" id="valid-title">{{ $errors->first('title') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="code">Code</label>
                                                <input type="text" class="form-control form-control-sm @error('code') is-invalid @enderror" id="code" name="code" value="@error('code'){{ old('code') }}@else{{ $book->code }}@enderror" placeholder="Input here..." autocomplete="off">
                                                <div class="invalid-feedback" id="valid-code">{{ $errors->first('code') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="year">Year</label>
                                                <input type="number" class="form-control form-control-sm @error('year') is-invalid @enderror" name="year" id="year" value="@error('year'){{ old('year') }}@else{{ $book->year }}@enderror" placeholder="Input here...">
                                                <div class="invalid-feedback" id="valid-year">{{ $errors->first('year') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="pages">Pages</label>
                                                <input type="number" class="form-control form-control-sm @error('pages') is-invalid @enderror" name="pages" id="pages" value="@error('pages'){{ old('pages') }}@else{{ $book->pages }}@enderror" placeholder="Input here...">
                                                <div class="invalid-feedback" id="valid-pages">{{ $errors->first('pages') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="edition">Edition</label>
                                                <input type="number" class="form-control form-control-sm @error('edition') is-invalid @enderror" name="edition" id="edition" value="@error('edition'){{ old('edition') }}@else{{ $book->edition }}@enderror" placeholder="Input here...">
                                                <div class="invalid-feedback" id="valid-edition">{{ $errors->first('edition') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <label>E-Book Available</label>
                                            <br>
                                            <label class="form-radio-label">
                                                <input class="form-radio-input" type="radio" name="ebook_available" id="available" value="1" {{ old('ebook_available') == '1' || $book->ebook_available == '1' ? 'checked' : '' }}>
                                                <span class="form-radio-sign">Available</span>
                                            </label>
                                            <label class="form-radio-label ml-4">
                                                <input class="form-radio-input" type="radio" name="ebook_available" id="unavailable" value="0" {{ old('ebook_available') == '0' || $book->ebook_available == '0' ? 'checked' : '' }}>
                                                <span class="form-radio-sign">Unavailable</span>
                                            </label>
                                            <div class="text-danger" id="valid-ebook_available">{{ $errors->first('ebook_available') }}</div>

                                            <div class="form-group" id="ebook-parent" style="{{ old('ebook_available') == '0' || $book->ebook_available == '0' ? 'display:none' : '' }}">
                                                <label for="ebook_url">E-Book URL <sup class="text-danger">*</sup></label>
                                                <input type="file" class="form-control-file @error('ebook_url') is-invalid @enderror" id="ebook_url" name="ebook_url">
                                                @if(old('ebook_available') == '1' || $book->ebook_available == '1')
                                                <iframe src="{{ asset('/pdfs/' . $book->ebook_url) }}" frameborder="0" width="100%" height="350px"></iframe>
                                                @endif
                                                <div class="invalid-feedback" id="valid-ebook_url">{{ $errors->first('ebook_url') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="book_cover_url">Book Cover URL <sup class="text-danger">max : 2MB</sup></label>
                                                <input type="file" class="form-control-file @error('book_cover_url') is-invalid @enderror" id="book_cover_url" name="book_cover_url">
                                                <img src="{{ asset('/img/books/' . $book->book_cover_url) }}" class="img-thumbnail">
                                                <div class="invalid-feedback" id="valid-book_cover_url">{{ $errors->first('book_cover_url') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="description">Description</label>
                                                <textarea class="form-control form-control-sm @error('description') is-invalid @enderror" name="description" id="description" placeholder="Input here..." rows="5">@error('description'){{ old('description') }}@else{{ $book->description }}@enderror</textarea>
                                                <div class="invalid-feedback" id="valid-description">{{ $errors->first('description') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="table_of_contents">Table of Contents</label>
                                                <textarea class="form-control form-control-sm @error('table_of_contents') is-invalid @enderror" name="table_of_contents" id="table_of_contents" placeholder="Input here..." rows="5">@error('table_of_contents'){{ old('table_of_contents') }}@else{{ $book->table_of_contents }}@enderror</textarea>
                                                <div class="invalid-feedback" id="valid-table_of_contents">{{ $errors->first('table_of_contents') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6" id="author-parent">
                                            <div class="form-group" id="author-select">
                                                <button class="btn btn-link btn-sm float-right" id="btn-add">
                                                    Add
                                                </button>
                                                <label for="author">Author</label>
                                                <select class="select2 form-control form-control-sm {{ count($errors->get('author_id.*')) > 0 ? 'is-invalid' : '' }}" name="author_id[]" id="author" multiple>
                                                    @foreach ($authors as $author)
                                                        <option value="{{ $author->id }}"
                                                            @if($errors->any() && old('author_id') !== NULL)
                                                                @for ($idx = 0; $idx < count(old('author_id')); $idx++)
                                                                    {{ old('author_id')[$idx] == $author->id ? 'selected' : '' }}
                                                                @endfor
                                                            @else
                                                                @for($idx = 0; $idx < $book->authors()->count(); $idx++)
                                                                    {{ $book->authors()->get()[$idx]->id == $author->id ? 'selected' : '' }}
                                                                @endfor
                                                            @endif
                                                            >{{ $author->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="valid-author">{{ count($errors->get('author_id.*')) > 0 ? 'Something error, please select again.' : '' }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="category">Category</label>
                                                <select class="select2 form-control form-control-sm @error('category_id') is-invalid @enderror" name="category_id" id="category">
                                                    <option value="" selected>-- UNCLASSIFIED --</option>
                                                        @foreach ($categories as $category)
                                                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id || $book->category_id == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                                        @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="valid-category">{{ $errors->first('category_id') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="publisher">Publisher</label>
                                                <select class="select2 form-control form-control-sm @error('publisher_id') is-invalid @enderror" name="publisher_id" id="publisher">
                                                    <option value="" selected>-- UNCLASSIFIED --</option>
                                                    @foreach ($publishers as $publisher)
                                                        <option value="{{ $publisher->id }}" {{ old('publisher_id') == $publisher->id || $book->publisher_id == $publisher->id ? 'selected' : '' }}>{{ $publisher->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="valid-publisher">{{ $errors->first('publisher_id') }}</div>
                                            </div>
                                        </div>
                                        <div class="col-md-6 col-sm-6">
                                            <div class="form-group">
                                                <label for="rack">Rack</label>
                                                <select class="select2 form-control form-control-sm @error('rack_id') is-invalid @enderror" name="rack_id" id="rack">
                                                    <option value="" selected>-- UNCLASSIFIED --</option>
                                                    @foreach ($racks as $rack)
                                                        <option value="{{ $rack->id }}" {{ old('rack_id') == $rack->id || $book->rack_id == $rack->id ? 'selected' : '' }}>{{ $rack->position }} - {{ $rack->category->name }}</option>
                                                    @endforeach
                                                </select>
                                                <div class="invalid-feedback" id="valid-rack">{{ $errors->first('rack_id') }}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Quantity</h4>
                                </div>
                                <div class="card-body">
                                    <div class="form-group">
                                        <label for="total_qty">Total Qty <sup class="text-danger">*</sup></label>
                                        <input type="number" class="form-control form-control-sm @error('total_qty') is-invalid @enderror" name="total_qty" id="total_qty" value="@error('total_qty'){{ old('total_qty') }}@else{{ $book->total_qty }}@enderror" placeholder="Input here...">
                                        <div class="invalid-feedback" id="valid-total_qty">{{ $errors->first('total_qty') }}</div>
                                    </div>
                                    <div class="form-group">
                                        <label for="qty_lost">Qty Lost</label>
                                        <input type="number" class="form-control form-control-sm @error('qty_lost') is-invalid @enderror" name="qty_lost" id="qty_lost" value="@error('qty_lost'){{ old('qty_lost') }}@else{{ $book->qty_lost }}@enderror" placeholder="Input here...">
                                        <div class="invalid-feedback" id="valid-qty_lost">{{ $errors->first('qty_lost') }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Other</h4>
                                </div>
                                <div class="card-body">
                                    <label>Active</label>
                                    <br>
                                    <label class="form-radio-label">
                                        <input class="form-radio-input" type="radio" name="disabled" id="yes" value="0" {{ old('disabled') == '0' || $book->disabled == '0' ? 'checked' : ''}}>
                                        <span class="form-radio-sign">Yes</span>
                                    </label>
                                    <label class="form-radio-label ml-4">
                                        <input class="form-radio-input" type="radio" name="disabled" id="no" value="1" {{ old('disabled') == '1' || $book->disabled == '1' ? 'checked' : ''}}>
                                        <span class="form-radio-sign">No</span>
                                    </label>
                                    <div class="text-danger" id="valid-disabled">{{ $errors->first('disabled') }}</div>
                                </div>
                            </div>
                            <a href="{{ route('admin.items.books.index') }}" class="btn btn-link float-left">
                                <i class="fas fa-arrow-left"></i>
                                Back
                            </a>
                            <button type="submit" class="btn btn-primary btn-round float-right" id="btn-submit">
                                <i class="fas fa-check"></i>
                                Update
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

            // Open Modal to Add new Author
            $('#btn-add').click(function(e) {
                e.preventDefault();
                $('#formModal').modal('show');
                $('.modal-title').html('Add Author');
                $('#author-form').trigger('reset');
                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                $('#author-form').find('.form-control').removeClass('is-invalid is-valid');
                $('#btn-save').val('save').removeAttr('disabled');
            });

            $('body').on('keyup', '#name, #email, #isbn, #title, #code, #description, #table_of_contents', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            // Store new Author
            $('#btn-save').click(function() {
                var id = $('#book_id').val();

                $('#btn-save').html('<i class="fas fa-cog fa-spin"></i> Saving...').attr("disabled", true);

                $.ajax({
                    type: "POST",
                    url: '{{ route('admin.authors.store') }}',
                    data: {
                        name: $('#name').val(),
                        email: $('#email').val(),
                    },
                    dataType: 'json',
                    success: function(data) {
                        swal({
                            title: "Good Job!",
                            text: "Author was successfully added \n (name : " + $('#name').val() + ")",
                            icon: "success",
                            timer: 3000
                        });

                        $('#author-parent').load('{{ route('admin.items.books.index') }}' + '/' + id + '/edit ' + '#author-select', function() {
                            // Open Modal to Add new Author
                            $('#btn-add').click(function(e) {
                                e.preventDefault();
                                $('#formModal').modal('show');
                                $('.modal-title').html('Add Author');
                                $('#author-form').trigger('reset');
                                $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                                $('#author-form').find('.form-control').removeClass('is-invalid is-valid');
                                $('#btn-save').val('save').removeAttr('disabled');
                            });

                            $('.select2').select2().trigger('load');

                            $('.select2').on('select2:selecting', function() {
                                $(this).removeClass('is-invalid');
                            });
                        });

                        $('#formModal').modal('hide');
                    },
                    error: function(data) {
                        try {
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

                            $('#btn-save').html('<i class="fas fa-check"></i> Save Changes');
                            $('#btn-save').removeAttr('disabled');
                        } catch {
                            swal({
                                title: "Hooray!",
                                text: "Something goes wrong",
                                icon: "error",
                                timer: 3000
                            });

                            $('#formModal').modal('hide');
                        }
                    }
                });
            });

            $('#isbn').mask('000 0 000 00000 0');

            $('body').on('keyup change', '#year, #pages, #edition, #total_qty, #qty_lost', function() {
                var test = $(this).val();
                if (test == '') {
                    $(this).removeClass('is-valid is-invalid');
                } else {
                    $(this).removeClass('is-invalid').addClass('is-valid');
                }
            });

            $('#yes, #no').click(function() {
                $('#valid-disabled').html('');
            });

            $('#available, #unavailable').click(function() {
                $('#valid-ebook_available').html('');
            });

            function filePreview(input) {
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
                filePreview(this);
                $('#valid-book_cover_url').html('');
            });

            function filePreview2(input) {
                if(input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function(e) {
                        $('#ebook_url + iframe').remove();
                        $('#ebook_url').after('<iframe src="' + e.target.result + '" frameborder="0" width="100%" height="350px"></iframe>');
                    };
                    reader.readAsDataURL(input.files[0]);
                };
            }

            $('#ebook_url').change(function() {
                filePreview2(this);
                $('#valid-ebook_url').html('');
            });

            $('form').submit(function() {
                $('#btn-submit').html('<i class="fas fa-cog fa-spin"></i> Updating...').attr("disabled", true);
            });

            $('body').on('keyup change', 'input[name=ebook_available]', function() {
                if($(this).val() == '1') {
                    $('#ebook-parent').removeAttr('style');
                } else {
                    $('#ebook-parent').css('display', 'none');
                }
            });
        })
    </script>
@endsection
