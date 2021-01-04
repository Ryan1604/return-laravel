@if(auth()->user()->role_id === 1)
    @foreach($issue_items as $book)
        <div class="form-button-action d-flex" style="margin-top: 2px;margin-bottom: 25px">
            <button type="button" data-toggle="tooltip" id="btn-renew" class="btn btn-icon btn-sm btn-warning mr-1" data-original-title="Renew" data-book_title="{{ $book['book']['title'] }}" data-book_id="{{ $book['book_id'] }}" data-issue_id="{{ $book['issue_id'] }}" data-penalty="{{ \Carbon\Carbon::parse($book['due_date'])->diffInDays(\Carbon\Carbon::now(), false) }}">
                <i class="fas fa-sync-alt"></i>
            </button>
            <button type="button" data-toggle="tooltip" id="btn-return" class="btn btn-icon btn-sm btn-info mr-1" data-original-title="Return" data-book_title="{{ $book['book']['title'] }}" data-book_id="{{ $book['book_id'] }}" data-issue_id="{{ $book['issue_id'] }}">
                <i class="fas fa-check-circle"></i>
            </button>
            <button type="button" data-toggle="tooltip" id="btn-lost" class="btn btn-icon btn-sm btn-danger" data-original-title="Lost Book" data-book_title="{{ $book['book']['title'] }}" data-book_id="{{ $book['book_id'] }}" data-issue_id="{{ $book['issue_id'] }}">
                <i class="far fa-times-circle"></i>
            </button>
        </div>
    @endforeach
@endif
