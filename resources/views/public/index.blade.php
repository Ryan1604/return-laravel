@extends('public.layouts.master')

@section('title', 'Home')

@section('css')
    <style>
        body {
            font-family: "Open Sans", sans-serif;
        }
        h2 {
            color: #000;
            font-size: 26px;
            font-weight: 300;
            text-align: center;
            text-transform: uppercase;
            position: relative;
            margin: 30px 0 80px;
        }
        h2 b {
            color: #ffc000;
        }
        h2::after {
            content: "";
            width: 100px;
            position: absolute;
            margin: 0 auto;
            height: 4px;
            background: rgba(0, 0, 0, 0.2);
            left: 0;
            right: 0;
            bottom: -20px;
        }
        .carousel {
            margin: 50px auto;
            padding: 0 70px;
        }
        .carousel .carousel-item {
            min-height: 330px;
            text-align: center;
            overflow: hidden;
        }
        .carousel .carousel-item .img-box {
            height: 160px;
            width: 100%;
            position: relative;
        }
        .carousel .carousel-item img {
            max-width: 100%;
            max-height: 100%;
            display: inline-block;
            position: absolute;
            bottom: 0;
            margin: 0 auto;
            left: 0;
            right: 0;
        }
        .carousel .carousel-item h4 {
            font-size: 18px;
            margin: 10px 0;
        }
        .carousel .carousel-item .btn {
            color: #333;
            border-radius: 0;
            font-size: 11px;
            text-transform: uppercase;
            font-weight: bold;
            background: none;
            border: 1px solid #ccc;
            padding: 5px 10px;
            margin-top: 5px;
            line-height: 16px;
        }
        .carousel .carousel-item .btn:hover, .carousel .carousel-item .btn:focus {
            color: #fff;
            background: #000;
            border-color: #000;
            box-shadow: none;
        }
        .carousel .carousel-item .btn i {
            font-size: 14px;
            font-weight: bold;
            margin-left: 5px;
        }
        .carousel .thumb-wrapper {
            text-align: center;
        }
        .carousel .thumb-content {
            padding: 15px;
        }
        .carousel-control-prev, .carousel-control-next {
            height: 100px;
            width: 40px;
            background: none;
            margin: auto 0;
            background: rgba(0, 0, 0, 0.2);
        }
        .carousel-control-prev i, .carousel-control-next i {
            font-size: 30px;
            position: absolute;
            top: 50%;
            display: inline-block;
            margin: -16px 0 0 0;
            z-index: 5;
            left: 0;
            right: 0;
            color: rgba(0, 0, 0, 0.8);
            text-shadow: none;
            font-weight: bold;
        }
        .carousel-control-prev i {
            margin-left: -3px;
        }
        .carousel-control-next i {
            margin-right: -3px;
        }
        .carousel .item-price {
            font-size: 13px;
            padding: 2px 0;
        }
        .carousel .item-price strike {
            color: #999;
            margin-right: 5px;
        }
        .carousel .item-price span {
            color: #86bd57;
            font-size: 110%;
        }
        .carousel .carousel-indicators {
            bottom: -50px;
        }
        .carousel-indicators li, .carousel-indicators li.active {
            width: 10px;
            height: 10px;
            margin: 4px;
            border-radius: 50%;
            border-color: transparent;
            border: none;
        }
        .carousel-indicators li {
            background: rgba(0, 0, 0, 0.2);
        }
        .carousel-indicators li.active {
            background: rgba(0, 0, 0, 0.6);
        }
        .star-rating li {
            padding: 0;
        }
        .star-rating i {
            font-size: 14px;
            color: #ffc000;
        }
    </style>
@endsection

@section('slider')
    @include('public.layouts.slider1')
@endsection

@section('content')
    @if(session('status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <strong>Success!</strong> {{ session('status') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <strong>Error!</strong> {{ session('error') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(auth()->check() && auth()->user()->password_changed_at === null)
    <div class="alert alert-warning alert-dismissible fade show" role="alert">
        <strong>Warning!</strong> For first time login, change your password now! Click
        <a href="{{ route('public.changepassword') }}">
            here
        </a> to continue
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    @if(session('info'))
    <div class="alert alert-info alert-dismissible fade show" role="alert">
        <strong>Info!</strong> {{ session('info') }}
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    @endif

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Popular <b>Books</b></h2>
                @if($popularBooks->isNotEmpty())
                    <div id="myCarousel" class="carousel slide" data-ride="carousel" data-interval="0">

                        <!-- Carousel indicators -->
                        <ol class="carousel-indicators">
                            @foreach($popularBooks as $popularBook)
                                @if($loop->index == 0 || $loop->index % 4 == 0)
                                    <li data-target="#myCarousel" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                                @endif
                            @endforeach
                        </ol>

                        <!-- Wrapper for carousel items -->
                        <div class="carousel-inner">
                            @foreach($popularBooks as $popularBook)
                                @if($loop->index == 0 || $loop->index % 4 == 0)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <div class="row">
                                @endif
                                    <div class="col">
                                        <div class="thumb-wrapper">
                                            <div class="img-box">
                                                <img src="{{ asset('/img/books/' . $popularBook->book_cover_url) }}" class="img-fluid" alt="">
                                            </div>
                                            <div class="thumb-content">
                                                <h4>{{ $popularBook->title }}</h4>
                                                <p class="item-price">{{ $popularBook->category_id ? $popularBook->category->name : '' }}</p>

                                                <a href="{{ route('public.bookDetail', $popularBook->id) }}" class="btn btn-primary">View Book</a>
                                            </div>
                                        </div>
                                    </div>
                                @if(($loop->index > 0 && $loop->index % 4 == 3) || $loop->last)
                                </div>
                                </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Carousel controls -->
                        <a class="carousel-control-prev" href="#myCarousel" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                @else
                    <h3 class="text-center">No Book</h3>
                @endif
            </div>
        </div>
    </div>

    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2>Latest <b>Books</b></h2>
                @if($latestBooks->isNotEmpty())
                    <div id="myCarousel2" class="carousel slide" data-ride="carousel" data-interval="0">

                        <!-- Carousel indicators -->
                        <ol class="carousel-indicators">
                            @foreach($latestBooks as $latestBook)
                                @if($loop->index == 0 || $loop->index % 4 == 0)
                                    <li data-target="#myCarousel2" data-slide-to="{{ $loop->index }}" class="{{ $loop->first ? 'active' : '' }}"></li>
                                @endif
                            @endforeach
                        </ol>

                        <!-- Wrapper for carousel items -->
                        <div class="carousel-inner">
                            @foreach($latestBooks as $latestBook)
                                @if($loop->index == 0 || $loop->index % 4 == 0)
                                <div class="carousel-item {{ $loop->first ? 'active' : '' }}">
                                <div class="row">
                                @endif
                                    <div class="col">
                                        <div class="thumb-wrapper">
                                            <div class="img-box">
                                                <img src="{{ asset('/img/books/' . $latestBook->book_cover_url) }}" class="img-fluid" alt="">
                                            </div>
                                            <div class="thumb-content">
                                                <h4>{{ $latestBook->title }}</h4>
                                                <p class="item-price">{{ $latestBook->category_id ? $latestBook->category->name : '' }}</p>

                                                <a href="{{ route('public.bookDetail', $latestBook->id) }}" class="btn btn-primary">View Book</a>
                                            </div>
                                        </div>
                                    </div>
                                @if(($loop->index > 0 && $loop->index % 4 == 3) || $loop->last)
                                </div>
                                </div>
                                @endif
                            @endforeach
                        </div>

                        <!-- Carousel controls -->
                        <a class="carousel-control-prev" href="#myCarousel2" data-slide="prev">
                            <i class="fa fa-angle-left"></i>
                        </a>
                        <a class="carousel-control-next" href="#myCarousel2" data-slide="next">
                            <i class="fa fa-angle-right"></i>
                        </a>
                    </div>
                    <br>
                @else
                    <h3 class="text-center">No Book</h3>
                    <br>
                @endif
            </div>
        </div>
    </div>
@endsection
