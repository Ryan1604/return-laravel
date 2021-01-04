@foreach($issue_items as $book)
    @if($book['return_date'])
        <div class="text-nowrap mt-2 mb-2">
            {{ date('d/m/Y', strtotime($book['return_date'])) }}
        </div>
        <br>
    @else
        <div class="text-nowrap mt-2 mb-2">
            -
        </div>
        <br>
    @endif
@endforeach
