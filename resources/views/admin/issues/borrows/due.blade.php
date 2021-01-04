@foreach($issue_items as $book)
    <div class="text-nowrap mt-2 mb-2">
        {{ date('d/m/Y', strtotime($book['due_date'])) }}
    </div>
    <br>
@endforeach
