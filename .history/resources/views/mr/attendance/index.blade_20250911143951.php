@extends('mr.layouts.master')

@section('content')
<style>

.attendence_indox .col {
    border: 1px solid #ddd;
    padding: 13px;
    border-radius: 10px;
    display: flex;
    align-items: center;
    gap: 15px;
}
.attendence_indox .badge {
    border-radius: 50px;
    border: none;
}
.attendence_indox {
    padding: 20px;
    gap: 16px;
}
.attendence_indox .col .small {
    text-transform: capitalize;
    font-size: 12px;
    font-weight: 700;
}
.attendence_indox .badge {
    margin: 0;
}

.attendance-hero {
    background: linear-gradient(92deg,#4270fa 0,#48c3fc 100%);
    border-radius: 14px 14px 0 0;
    min-height: 70px;
    padding: 10px 20px;
    color: #fff;
    display: flex;
    align-items: center;
    box-shadow: 0 6px 30px rgba(66,112,250,0.09);
    justify-content: space-between;
}

    .status-big {
        font-size: 1.4rem;
        padding: 8px 35px;
        border-radius: 22px;
        font-weight: 600;
        margin-bottom: 10px;
        letter-spacing: 0.025em;
        display:inline-block;
    }
    .stat-present { background: #e8fbe7; color: #25a04c; border: 1px solid #aae6b7; }
    .stat-half { background: #fff9e6; color: #d8b400; border: 1px solid #ffe6a6; }
    .stat-absent { background: #ffeeed; color: #e04a34; border: 1px solid #ffbeb1; }
    .card-shadow {
        box-shadow: 0 4px 24px rgba(44,62,80,0.09), 0 1.5px 5px rgba(44,62,80,.025);
        border-radius: 16px;
    }
    @media (max-width: 600px) {
        .attendance-hero {padding:18px 0;}
        .attendance-avatar {width:55px;height:55px;font-size:26px;}
        .status-big {font-size:1.1rem;padding:6px 17px;}
    }
</style>
<div class="container" style="max-width: 540px;">
    <div class="page-inner">
    <div class="row g-0 overflow-hidden card-shadow mb-4">
        <div class="attendance-hero">
            <!-- <div class="attendance-avatar">
                <i class="fa-solid fa-user"></i>
            </div> -->
            <div class=" fs-5 fw-bold">{{ auth()->user()->name }}</div>
            <div class="small opacity-75">{{ \Carbon\Carbon::today()->format('d F, Y') }}</div>
        </div>
        <div class="bg-white px-4 pb-4 pt-1 text-center">
            <div class="row justify-content-around mt-4 mb-3 attendence_indox">
                <div class="col">
                    <div class="badge bg-success px-4 py-2 fs-6">
                        <i class="bi bi-box-arrow-in-right me-1"></i>
                        {{ $attendance && $attendance->check_in ? \Carbon\Carbon::parse($attendance->check_in)->format('h:i A') : '-' }}
                    </div>
                    <div class="small text-muted mt-1">Check In</div>
                </div>
                <div class="col">
                    <div class="badge bg-danger px-4 py-2 fs-6">
                        <i class="bi bi-box-arrow-right me-1"></i>
                        {{ $attendance && $attendance->check_out ? \Carbon\Carbon::parse($attendance->check_out)->format('h:i A') : '-' }}
                    </div>
                    <div class="small text-muted mt-1">Check Out</div>
                </div>
            </div>

            <div class="my-3">
                @if($attendance)
                    @if($attendance->status === 'present')
                        <span class="status-big stat-present">
                            <i class="bi bi-patch-check-fill me-1"></i> Present
                        </span>
                    @elseif($attendance->status === 'half-day' || $attendance->status === 'half')
                        <span class="status-big stat-half">
                            <i class="bi bi-hourglass-split me-1"></i> Half Day
                        </span>
                    @elseif($attendance->status === 'absent')
                        <span class="status-big stat-absent">
                            <i class="bi bi-x-circle me-1"></i> Absent
                        </span>
                    @else
                        <span class="status-big stat-absent">
                            <i class="bi bi-question-circle me-1"></i> Unknown
                        </span>
                    @endif
                @else
                    <span class="status-big stat-absent">
                        <i class="bi bi-x-circle me-1"></i> Absent
                    </span>
                @endif
            </div>
            <div class="pt-4">
                <form action="{{ route('mr.attendance.checkin') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-success shadow-sm me-2"
                        {{ $attendance && $attendance->check_in ? 'disabled' : '' }}>
                        <i class="bi bi-check-lg me-1"></i> Check In
                    </button>
                </form>
                <form action="{{ route('mr.attendance.checkout') }}" method="POST" class="d-inline">
                    @csrf
                    <button class="btn btn-danger shadow-sm"
                        {{ $attendance && $attendance->check_out ? 'disabled' : '' }}>
                        <i class="bi bi-box-arrow-right me-1"></i> Check Out
                    </button>
                </form>
            </div>
            <div class="text-muted mt-2" style="font-size:12px">
                <i class="bi bi-info-circle me-1"></i>
                Please check-in at start of day, and check-out at end for full attendance.
            </div>
        </div>
    </div>
    </div>
</div>
@endsection
