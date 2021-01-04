@foreach($issue_items as $book)
    <div class="text-nowrap mt-2 mb-4">
        {{ date('d/m/Y', strtotime($book['borrow_date'])) }}
    </div>
@endforeach
