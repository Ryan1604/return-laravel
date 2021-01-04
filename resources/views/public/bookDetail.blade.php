@extends('public.layouts.master')

@section('title', 'Book Detail')

@section('content')
    <br> <br>
    <br> <br>
    <br> <br>
    <br> <br>
    <br> <br>
    <br> <br>
    <div class="card-body">
        <div class="container">
            <div class="row">
                <div class="col-md-3 col-3">
                    <img class="card-img-top" src="{{ asset('/img/books/' . $book->book_cover_url) }}" alt="Book Cover">
                </div>
                <div class="col-md-4 col-9">
                    <p class="text-muted">ISBN {{ $book->isbn }} {{ $book->code ? ' - ' . $book->code : '' }}</p>
                    <h1>{{ $book->title }}</h1>
                    <h3>{{ $book->year }}</h3>
                    <br>
                    <p class="card-text">{{ $book->edition ? 'Edition : ' . $book->edition : '' }}</p>
                    <p class="card-text">{{ $book->pages ? 'Pages : ' . $book->pages : '' }}</p>
                    <p class="card-text">Ebook Available : @if($book->ebook_available == '1') Available, <a target="_blank" class="btn-link" href="{{ route('public.ebookRead', $book->id) }}"><i class="fas fa-external-link-alt"></i> Read PDF</a> @else Unavailable @endif</p>
                    <p class="card-text">{{ $book->description ? 'Description : ' . $book->description : '' }}</p>
                    <p class="card-text">{{ $book->table_of_contents ? 'Table Of Contents : ' . $book->table_of_contents : '' }}</p>
                    <p class="card-text">Stocks in Library : {{ $book->total_qty - $book->qty_lost }}</p>
                    <p class="card-text">Borrowed : {{ $borrowed }}</p>
                    <p class="card-text">
                    @if($book->authors()->exists())
                        Author :
                        @foreach($book->authors()->get() as $idx => $author)
                            {{ ++$idx }}. {{ $author->name }},
                        @endforeach
                    @endif
                    </p>
                    <p class="card-text">Category : {{ $book->category->name ?? 'UNCLASSIFIED' }}</p>
                    @if($book->publisher()->exists())
                        <p class="card-text">Publisher : {{ $book->publisher->name }}</p>
                    @endif
                    @if($book->rack()->exists())
                        <p class="btn btn-info btn-round">Rack : {{ $book->rack->position }} - {{ $book->rack->category->name }}</p>
                    @else
                        <p class="btn btn-secondary btn-round">Rack : UNCLASSIFIED</p>
                    @endif
                    @if($book->total_qty - $book->qty_lost - $borrowed > 0)
                        <h1><span class="badge badge-pill badge-success">Available</span></h1>
                    @else
                        <h1><span class="badge badge-pill badge-danger">Not Available</span></h1>
                    @endif
                    <br>
                    <a href="{{ route('public.books') }}" class="btn btn-primary btn-rounded btn-sm mb-5">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
                <div class="offset-md-1 col-md-4">
                    <!-- E:mediumrectangle2 -->
                    <div class="box terpopuler">
                        <div class="box__header">
                            <h2 class="box__title">Related Books</h2>
                        </div>
                        <div class="row">
                            @if($relatedBooks->isNotEmpty())
                                @foreach($relatedBooks as $relatedBook)
                                    <div class="col-6 col-sm-4 col-md-12 col-lg-6 teacher">
                                        <div class="card">
                                            <div class="card_img">
                                                <div class="card_plus trans_200 text-center"><a href="{{ route('public.bookDetail', $relatedBook->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a></div>
                                                <img class="card-img-top trans_200" src="{{ asset('/img/books/' . $relatedBook->book_cover_url) }}" alt="Book Cover">
                                            </div>
                                            <div class="card-body text-center">
                                                <div class="card-title"><a href="{{ route('public.bookDetail', $relatedBook->id) }}">{{ $relatedBook->title }}</a></div>
                                                <div class="card-text">{{ $relatedBook->year }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    No Related Books Yet
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
