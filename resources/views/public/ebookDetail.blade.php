@extends('public.layouts.master')

@section('title', 'E-Book Detail')

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
                    <img class="card-img-top" src="{{ asset('/img/ebooks/' . $ebook->book_cover_url) }}" alt="E-Book Thumbnail">
                    <br><br>
                    <div class="d-flex justify-content-center">
                        <div class="text-center trans_200">
                            <a href="{{ route('public.ebookRead', $ebook->id) }}" class="btn btn-warning" target="_blank"><i class="fa fa-external-link-alt"></i> Read E-Book</a>
                        </div>
                    </div>

                </div>
                <div class="col-md-4 col-9">
                    <p class="text-muted">ISBN {{ $ebook->isbn }} {{ $ebook->code ? ' - ' . $ebook->code : '' }}</p>
                    <h1>{{ $ebook->title }}</h1>
                    <h3>{{ $ebook->year }}</h3>
                    <br>
                    <p class="card-text">{{ $ebook->edition ? 'Edition : ' . $ebook->edition : '' }}</p>
                    <p class="card-text">{{ $ebook->pages ? 'Pages : ' . $ebook->pages : '' }}</p>
                    <p class="card-text">{{ $ebook->description ? 'Description : ' . $ebook->description : '' }}</p>
                    <p class="card-text">{{ $ebook->table_of_contents ? 'Table Of Contents : ' . $ebook->table_of_contents : '' }}</p>
                    <p class="card-text">
                    @if($ebook->authors()->exists())
                        Author :
                        @foreach($ebook->authors()->get() as $idx => $author)
                            {{ ++$idx }}. {{ $author->name }},
                        @endforeach
                    @endif
                    </p>
                    <p class="card-text">Category : {{ $ebook->category->name ?? 'UNCLASSIFIED' }}</p>
                    @if($ebook->publisher()->exists())
                        <p class="card-text">Publisher : {{ $ebook->publisher->name }}</p>
                    @endif
                    <a href="{{ route('public.ebooks') }}" class="btn btn-primary btn-rounded btn-sm mb-5">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
                <div class="offset-md-1 col-md-4">
                    <!-- E:mediumrectangle2 -->
                    <div class="box terpopuler">
                        <div class="box__header">
                            <h2 class="box__title">Related E-Books</h2>
                        </div>
                        <div class="row">
                            @if($relatedEbooks->isNotEmpty())
                                @foreach($relatedEbooks as $relatedEbook)
                                    <div class="col-6 col-sm-4 col-md-12 col-lg-6 teacher">
                                        <div class="card">
                                            <div class="card_img">
                                                <div class="card_plus trans_200 text-center"><a href="{{ route('public.ebookDetail', $relatedEbook->id) }}"><i class="fa fa-eye" aria-hidden="true"></i></a></div>
                                                <img class="card-img-top trans_200" src="{{ asset('/img/ebooks/' . $relatedEbook->book_cover_url) }}" alt="Book Cover">
                                            </div>
                                            <div class="card-body text-center">
                                                <div class="card-title"><a href="{{ route('public.ebookDetail', $relatedEbook->id) }}">{{ $relatedEbook->title }}</a></div>
                                                <div class="card-text">{{ $relatedEbook->year }}</div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            @else
                                <div class="col-12">
                                    No Related E-Books Yet
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <br><br>
@endsection
