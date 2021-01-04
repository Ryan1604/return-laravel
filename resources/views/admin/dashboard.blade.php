@extends('admin.layouts.master')
@section('title', 'Dashboard')

@section('content')
    <!-- Main Content -->
    <div class="main-content">
        <section class="section">
            <div class="section-header">
                <h1>Dashboard</h1>
            </div>
            <div class="row">
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-book"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Books</h4>
                            </div>
                            <div class="card-body">
                                {{ $counts['books'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="far fa-file-pdf"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>E-Books</h4>
                            </div>
                            <div class="card-body">
                                {{ $counts['ebooks'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Members</h4>
                            </div>
                            <div class="card-body">
                                {{ $counts['members'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-address-book"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Roles</h4>
                            </div>
                            <div class="card-body">
                                {{ $counts['roles'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-hashtag"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Categories</h4>
                            </div>
                            <div class="card-body">
                                {{ $counts['categories'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-secondary">
                            <i class="fas fa-marker"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Authors</h4>
                            </div>
                            <div class="card-body">
                                {{ $counts['authors'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-newspaper"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Publishers</h4>
                            </div>
                            <div class="card-body">
                                {{ $counts['publishers'] }}
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-3 col-md-4 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-columns"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Racks</h4>
                            </div>
                            <div class="card-body">
                                {{ $counts['racks'] }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-6 col-md-6 col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Today Issues</h4>
                        </div>
                        <div class="card-body">
                            <div class="summary">
                                <div class="summary-info">
                                    <h4>Issues</h4>
                                    @php
                                    $members = [];

                                    foreach ($issues as $issue) {
                                    array_push($members, $issue->issue->user_id);
                                    }

                                    $members = array_unique($members);
                                    @endphp
                                    <div class="text-muted">
                                        @if($issues->isNotEmpty())
                                        Issued {{ $issues->count() }} items on {{ count($members) }} members
                                        @else
                                        No issue yet for today
                                        @endif
                                    </div>
                                    <div class="d-block mt-2">
                                        <a href="{{ route('admin.issues.borrows.index') }}">View All</a>
                                    </div>
                                </div>
                                @if($issues->isNotEmpty())
                                <div class="summary-item">
                                    <h6>Issue List <span class="text-muted">({{ $issues->count() }}
                                            Items)</span></h6>
                                    <ul class="list-unstyled list-unstyled-border">
                                        @foreach($issues as $issue)
                                        <li class="media">
                                            <img class="mr-3 rounded" width="50"
                                                src="{{ asset('img/books/' . $issue->book->book_cover_url) }}"
                                                alt="product">
                                            <div class="media-body">
                                                <div class="media-right">{{ $issue->status }}</div>
                                                <div class="media-title">{{ $issue->book->title }}</div>
                                                <div class="text-muted text-small">by
                                                    {{ $issue->issue->user->name }}
                                                    <div class="bullet"></div>
                                                    {{ $issue->updated_at->diffForHumans() }}
                                                </div>
                                            </div>
                                        </li>
                                        @endforeach
                                    </ul>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>Overall Issues</h4>
                        </div>
                        <div class="card-body">
                            <canvas id="issue-chart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('js')
    <script src="{{ asset('backend/modules/chart.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            var ctx = document.getElementById("issue-chart").getContext('2d');
            var dynamicColors = function() {
                var r = Math.floor(Math.random() * 255);
                var g = Math.floor(Math.random() * 255);
                var b = Math.floor(Math.random() * 255);

                return "rgb(" + r + "," + g + "," + b + ")";
            };

            var config = {
                type: 'doughnut',
                data: {
                    datasets: [{
                        data: ["{{ $counts['borrowed'] }}", "{{ $counts['returned'] }}", "{{ $counts['lost'] }}"],
                        backgroundColor: [dynamicColors(), dynamicColors(), dynamicColors()]
                    }],
                    labels: ["Borrow", "Return", "Lost"],
                },
                options: {
                    responsive: true,
                    legend: {
                        position: 'bottom',
                    },
                }
            };

            new Chart(ctx, config);
        });
    </script>
@endsection

