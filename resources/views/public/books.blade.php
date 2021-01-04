@extends('public.layouts.master')

@section('title', 'Books')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/select2.min.css') }}">
@endsection

@section('slider')
    @include('public.layouts.slider3')
@endsection

@section('content')
    <br> <br>

    <div class="logo_container">
        <h1>Category</h1>
        <select class="select2" name="category_id" id="category">
            <option value="" selected>-- Show All Categories --</option>
            @foreach ($categories as $category)
                <option value="{{ $category->id }}">{{ $category->name }}</option>
            @endforeach
        </select>
    </div>

    <!-- Teachers -->
    <div class="teachers page_section">
        <div class="container" id="book-list">
            @section('data')
                <div class="row">
                    @if($books->isNotEmpty())
                        @foreach($books as $book)
                            <!-- Teacher -->
                            <div class="col-lg-3 col-md-4 col-sm-6 col-6 teacher">
                                <div class="card">
                                    <div class="card_img">
                                        <div class="card_plus trans_200 text-center"><a href="{{ route('public.bookDetail', $book->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a></div>
                                        <img class="card-img-top trans_200" src="{{ asset('/img/books/' . $book->book_cover_url) }}" alt="Book Cover">
                                    </div>
                                    <div class="card-body text-center">
                                        <div class="card-title"><a href="{{ route('public.bookDetail', $book->id) }}">{{ $book->title }}</a></div>
                                        <div class="card-text">{{ $book->category->name ?? 'UNCLASSIFIED' }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 teacher">
                            No Result
                        </div>
                    @endif
                </div>
            @show
        </div>
    </div>
@endsection

@section('js')
    <script src="{{ asset('frontend/js/select2.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                width: '100%'
            });

            $('body').on('change keyup', '#category', function() {
                $.ajax({
                    type : 'GET',
                    url : '{{ route('public.books') }}',
                    data : {
                        category : $(this).val(),
                    },
                    dataType : 'json',
                    success : function(data) {
                        $('#book-list').html(data);
                    },
                    error : function(data) {
                        alert('Unable to handle request' + data);
                    }
                });
            });
        })
    </script>
@endsection
