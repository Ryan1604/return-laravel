@foreach($issue_items as $book)
    @php
        $todayDate = \Carbon\Carbon::today();
        $late = \Carbon\Carbon::parse($book['due_date'])->diffInDays($todayDate->toDateString(), false);
    @endphp

    @if($late > 0)
        @if($book['status'] == 'BORROW')
            <div class="text-nowrap badge badge-pill badge-warning mt-1 mb-4">
                LATE
            </div>
            <br>
        @elseif($book['status'] == 'RETURN')
            <div class="text-nowrap badge badge-pill badge-success mt-1 mb-4">
                {{ $book['status'] }}
            </div>
            <br>
        @elseif($book['status'] == 'LOST')
            <div class="text-nowrap badge badge-pill badge-danger mt-1 mb-4">
                {{ $book['status'] }}
            </div>
            <br>
        @endif
    @else
        <div class="text-nowrap badge badge-pill
            @if($book['status'] == 'BORROW' || $book['status'] == 'RETURN')
                badge-success
            @elseif($book['status'] == 'LOST')
                badge-danger
            @endif
            mt-1 mb-4">{{ $book['status'] }}
        </div>
        <br>
    @endif
@endforeach
