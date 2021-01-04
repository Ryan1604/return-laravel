@extends('public.layouts.master')

@section('title', 'History')

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ asset('frontend/css/dataTables.bootstrap4.min.css') }}">
@endsection

@section('content')
    {{-- CONTENT HISTORY --}}
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <br><br>
    <div class="page-content">
        <div class="container mt-4 mb-4 table-responsive">
            <table class="table table-hover table-condensed" id="history-table">
                <thead class="thead-dark">
                    <tr>
                        <th>Book</th>
                        <th>Borrow Date</th>
                        <th>Due Date</th>
                        <th>Return Date</th>
                        <th>Penalty</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    @if($issues->isNotEmpty())
                        @foreach($issues as $issue)
                            @foreach($issue->issueItems as $issueItem)
                                <tr>
                                    <td>
                                        <div class="row">
                                            <div class="col-lg-3 col-md-4 d-block">
                                                <img src="{{ asset('/img/books/' . $issueItem->book->book_cover_url) }}" class="img-thumbnail">
                                            </div>
                                            <div class="col-lg-9 col-md-8 col-sm-12 col-12">
                                                <h4 class="text-nowrap">{{ $issueItem->book->isbn }}</h4>
                                                <a href="{{ route('public.bookDetail', $issueItem->book->id) }}"><h2>{{ $issueItem->book->code ? $issueItem->book->code . ' - ' : '' }}{{ $issueItem->book->title }}</h2></a>
                                                @if($issueItem->book->rack)
                                                    <span class="btn btn-info btn-round">{{ $issueItem->book->rack->position }} - {{ $issueItem->book->rack->category->name }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td><h4>{{ date('d/m/Y', strtotime($issueItem->borrow_date)) }}</h4></td>
                                    <td><h4>{{ date('d/m/Y', strtotime($issueItem->due_date)) }}</h4></td>
                                    @if($issueItem->return_date)
                                        <td><h4>{{ date('d/m/Y', strtotime($issueItem->return_date)) }}</h4></td>
                                    @else
                                        <td></td>
                                    @endif
                                    <td>
                                        @php
                                            $dueDate = \Carbon\Carbon::parse($issueItem->due_date);
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

                                        @if($late > 0 && $issueItem->status == 'BORROW')
                                            <h4 class="text-danger">{{ $late }} days ({{ $pay }} R)</h4>
                                        @elseif($issueItem->status == 'RETURN')
                                            <h4 class="text-success">Paid</h4>
                                        @else
                                            <h4 class="text-secondary">No Penalty</h4>
                                        @endif
                                    </td>
                                    <td>
                                        <h3>
                                            @if($late > 0 && $issueItem->status == 'BORROW')
                                                <span class="badge badge-pill badge-warning">LATE</span>
                                            @else
                                                <span class="badge badge-pill @if($issueItem->status == 'BORROW' || $issueItem->status == 'RETURN')
                                                    badge-success
                                                @elseif($issueItem->status == 'LOST')
                                                    badge-danger
                                                @endif">{{ $issueItem->status }}
                                                </span>
                                            @endif
                                        </h3>
                                    </td>
                                </tr>
                            @endforeach
                        @endforeach
                    @else
                        <tr>
                            <td colspan="6" align="center">No History</td>
                        </tr>
                    @endif
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="5"><h2>TOTAL HISTORY</h2></td>
                        <td><h2>{{ $total ?? 0 }}</h2></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <br><br>
@endsection

@section('js')
    <script src="{{ asset('frontend/js/dataTables.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('#history-table').DataTable();
        });
    </script>
@endsection
