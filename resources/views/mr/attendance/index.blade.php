@extends('mr.layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row justify-content-center mt-4">

            <div class="col-md-6">
                <div class="card shadow-lg rounded-4 border-0">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">
                            <i class="bi bi-calendar-day me-2"></i> Today's Attendance
                        </h5>
                        <small>{{ \Carbon\Carbon::today()->format('d M, Y') }}</small>
                    </div>
                    <div class="card-body text-center">
                        <div class="mb-4">
                            <span class="avatar bg-info text-white rounded-circle shadow-sm mb-2" 
                                  style="width:60px;height:60px;display:inline-flex;align-items:center;justify-content:center;font-size:24px;">
                                <i class="bi bi-person-check"></i>
                            </span>
                            <h5 class="fw-bold mt-2">{{ auth()->user()->name }}</h5>
                        </div>

                        <div class="d-flex justify-content-around mb-4">
                            <div>
                                <div class="badge bg-success px-4 py-3 shadow-sm fs-6">
                                    <i class="bi bi-box-arrow-in-right me-1"></i>
                                    {{ $attendance && $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '-' }}
                                </div>
                                <div class="small text-muted mt-1">Check In</div>
                            </div>
                            <div>
                                <div class="badge bg-danger px-4 py-3 shadow-sm fs-6">
                                    <i class="bi bi-box-arrow-right me-1"></i>
                                    {{ $attendance && $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '-' }}
                                </div>
                                <div class="small text-muted mt-1">Check Out</div>
                            </div>
                        </div>

                        <div>
                            @if($attendance && $attendance->check_in && $attendance->check_out)
                                <span class="badge bg-success fs-6 px-4 py-2">Present</span>
                            @elseif($attendance && $attendance->check_in)
                                <span class="badge bg-warning text-dark fs-6 px-4 py-2">Half Day</span>
                            @else
                                <span class="badge bg-danger fs-6 px-4 py-2">Absent</span>
                            @endif
                        </div>

                        <div class="mt-4">
                            <form action="{{ route('mr.attendance.checkin') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-success shadow-sm me-2" 
                                        {{ $attendance && $attendance->check_in ? 'disabled' : '' }}>
                                    Check In
                                </button>
                            </form>
                            <form action="{{ route('mr.attendance.checkout') }}" method="POST" class="d-inline">
                                @csrf
                                <button class="btn btn-danger shadow-sm" 
                                        {{ $attendance && $attendance->check_out ? 'disabled' : '' }}>
                                    Check Out
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
