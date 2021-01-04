@extends('public.layouts.master')

@section('title', 'Read E-Book')

@section('css')
    {{-- to load read PDF --}}
    <link rel="stylesheet" href="{{ asset('user/flip/css/dflip.css') }}" />
    <link rel="stylesheet" href="{{ asset('user/flip/css/themify-icons.css') }}" />
@endsection

@section('content')
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>

    <div class="container">
        <div class="logo text-center">
            <span>Read E-Book</span>
        </div>
    </div>

    <br><br>

    <div class="card-body">
        <div class="container">
            <div class="row" style="height:500px">
                <div class="col-md-12">
                    <div id="flipbookContainer" data-ebook="{{ asset('pdfs/' . $ebook->ebook_url) }}"></div>
                </div>
            </div>
            <br><br>
            <div class="row">
                <div class="col-md-12">
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
                    <a href="{{ route('public.ebooks') }}" class="btn btn-primary btn-rounded btn-sm">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>

    <br><br>
@endsection

@section('js')

    {{-- to execute PDF reader --}}
    <script src="{{ asset('user/flip/js/dflip.min.js') }}"></script>
    <script>
        var flipBook;

        $(document).ready(function () {

            var pdf = $("#flipbookContainer").data('ebook');

            var options = {
                hard: 'cover', webgl: true, duration: 800,
                height: "100%",
                //CONTROLS POSITION
                //default is "altPrev,pageNumber,altNext,outline,thumbnail,zoomIn,zoomOut,fullScreen,more"
                //this example remove outline and thumbnail; add download button in main bar
                moreControls: "download,startPage,endPage,sound",
                hideControls: "share,thumbnail,pageMode",
                pageMode : DFLIP.PAGE_MODE.DOUBLE,
                direction: DFLIP.DIRECTION.LTR,
                pageSize: DFLIP.PAGE_SIZE.AUTO,
                backgroundColor: "teal",
                ambientLightColor: "#fff",
            };

            flipBook = $("#flipbookContainer").flipBook(pdf, options);

            //NOTE:
            //Valid Control Names:
            //altPrev,pageNumber,altNext,outline,thumbnail,zoomIn,zoomOut,fullScreen,more
            //pageMode,startPage,endPage,download

            //We dont recommend putting pageNumber in moreControls, that doesn't make sense.
        });
    </script>

@endsection
