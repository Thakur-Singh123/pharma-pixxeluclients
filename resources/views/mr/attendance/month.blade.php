@extends('mr.layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <h2 class="mb-4 fw-bold text-primary">Attendance for {{ now()->format('F Y') }}</h2>

        <div class="row row-cols-1 row-cols-md-3 g-3">
            @foreach($days as $day)
                <div class="col">
                    <div class="card shadow-sm border-0 rounded-4 h-100">
                        <div class="card-body text-center">
                            <h5 class="card-title mb-2">{{ \Carbon\Carbon::parse($day['date'])->format('d M, D') }}</h5>

                            <div class="mb-3">
                                <span class="badge
                                    @if($day['check_in'] && $day['check_out']) bg-success
                                    @elseif($day['check_in'] && !$day['check_out']) bg-warning text-dark
                                    @else bg-danger
                                    @endif
                                    px-3 py-2 fs-6">
                                    @if($day['check_in'] && $day['check_out'])
                                        <i class="bi bi-check-circle me-1"></i> Present
                                    @elseif($day['check_in'])
                                        <i class="bi bi-clock me-1"></i> Half Day
                                    @else
                                        <i class="bi bi-x-circle me-1"></i> Absent
                                    @endif
                                </span>
                            </div>

                            <div class="d-flex justify-content-between align-items-center mt-2 px-2">
                                <div>
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-box-arrow-in-right me-1"></i>Check In
                                    </small>
                                    <span class="fw-semibold text-success">
                                        {{ $day['check_in'] ? \Carbon\Carbon::parse($day['check_in'])->format('h:i A') : '-' }}
                                    </span>
                                </div>
                                <div>
                                    <small class="text-muted d-block mb-1">
                                        <i class="bi bi-box-arrow-right me-1"></i>Check Out
                                    </small>
                                    <span class="fw-semibold text-danger">
                                        {{ $day['check_out'] ? \Carbon\Carbon::parse($day['check_out'])->format('h:i A') : '-' }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
@endsection