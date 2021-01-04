@foreach($issues['issueItems'] as $book)
    @php
        $dueDate = \Carbon\Carbon::parse($book['due_date']);
        $late = (int) $dueDate->diffInDays(today()->toDateString(), false);
        $pay = 0;
        $minPenaltyIndex = [];
    @endphp

    @for($i = 0; $i < (int) count($penalty); $i++)
        @php
            $diff = (int) $dueDate->diffInDays($penalty[$i]->date, false);
        @endphp

        @if($diff <= 0)
            @php
                array_push($minPenaltyIndex, $i);
            @endphp
        @endif
    @endfor

    @for($i = (int) max($minPenaltyIndex); $i < (int) count($penalty); $i++)
        @if($i == (int) max($minPenaltyIndex))
            @isset($penalty[$i + 1])
                @php
                    $penaltyCheck = (int) $dueDate->diffInDays($penalty[$i + 1]->date, false);
                @endphp
            @endisset

            @if(isset($penaltyCheck) && $penaltyCheck < $late)
                @php
                    $pay += ($penaltyCheck * $penalty[$i]->price);
                @endphp
            @else
                @php
                    $pay += ($late * $penalty[$i]->price);
                @endphp
            @endif
        @endif

        @if($i > (int) max($minPenaltyIndex) && $i < (int) count($penalty))
            @php
                $nextPenalty = \Carbon\Carbon::parse($penalty[$i]->date);
                $todayDiff = (int) $nextPenalty->diffInDays(today()->toDateString(), false);
            @endphp

            @if(isset($todayDiff) && $todayDiff > 0)
                @if(isset($penalty[$i + 1]))
                    @php
                        $nextPenaltyCheck = (int) $nextPenalty->diffInDays($penalty[$i + 1]->date, false);
                    @endphp
                @endif

                @if(isset($nextPenaltyCheck) && $todayDiff > 0 && $nextPenaltyCheck < $todayDiff)
                    @php
                        $pay += ($nextPenaltyCheck * $penalty[$i]->price);
                    @endphp
                @else
                    @php
                        $pay += ($todayDiff * $penalty[$i]->price);
                    @endphp
                @endif
            @endif
        @endif
    @endfor

    @if($late > 0 && $book['status'] == 'BORROW')
        <div class="text-nowrap text-danger mt-2 mb-2">
            {{ $late }} days ({{ $pay }} R)
        </div>
        <br>
    @elseif($book['status'] == 'RETURN')
        <div class="text-nowrap text-success mt-2 mb-2">
            Paid
        </div>
        <br>
    @else
        <div class="text-nowrap text-dark mt-2 mb-2">
            No Penalty
        </div>
        <br>
    @endif
@endforeach
