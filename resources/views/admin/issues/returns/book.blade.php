@foreach($issue_items as $book)
    <button class="btn btn-link btn-block text-left" id="btn-book" value="{{ $book['book_id'] }}" data-book="{{ $book['book']['title'] }}">
        <i class="fa fa-book"></i>
        {{ $book['book']['title'] }}
    </button>
@endforeach
