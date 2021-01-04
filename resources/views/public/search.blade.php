@extends('public.layouts.master')

@section('title', 'Search Result')

@section('content')
    <br> <br>
    <br> <br>
    <br> <br>
    <br> <br>
    <br> <br>
    <br> <br>

    <div class="container">
        <div class="logo text-center">
            <span>Search Result</span>
        </div>
    </div>

    <!-- Teachers -->
    <div class="teachers page_section">
        <div class="container">
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
                                    <div class="card-text">{{ $book->category_id ? $book->category_name : '' }}</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="col-12 teacher">
                        No Result, <a href="{{ url('/') }}">Go Back</a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection
