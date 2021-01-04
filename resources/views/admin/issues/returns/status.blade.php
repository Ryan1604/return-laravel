@foreach($issue_items as $book)
    <div class="text-nowrap mt-1 mb-3">
        <span class="badge badge-pill badge-success">{{ $book['status'] }}</span>
    </div>
@endforeach
