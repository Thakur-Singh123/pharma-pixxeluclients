@extends('mr.layouts.master')
@section('content')
<style>
* {
    box-sizing: border-box;
}
body {
    margin: 0;
    background: #eef1f6;
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto;
}
.page-inner {
    min-height: calc(100vh - 120px);
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 60px 20px;
}
.container.month_att {
    max-width: 520px;
    width: 100%;
}
.card-shadow {
    background: #ffffff;
    border-radius: 22px;
    overflow: hidden;
    box-shadow:
    0 25px 60px rgba(15, 23, 42, 0.12),
    0 8px 18px rgba(15, 23, 42, 0.08);
}
.attendance-hero {
    background: linear-gradient(135deg, #0f172a, #020617);
    color: #ffffff;
    padding: 26px 28px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.attendance-hero .fs-5 {
    font-size: 18px;
    font-weight: 700;
}
.attendance-hero .small {
    font-size: 13px;
    opacity: 0.8;
}

.bg-white {
    padding: 34px 30px 30px;
    text-align: center;
}
.attendence_indox {
    display: flex;
    gap: 20px;
    justify-content: center;
    margin-bottom: 28px;
}
.attendence_indox .col {
    width: 100%;
    max-width: 210px;
    background: #f9fafb;
    border-radius: 18px;
    padding: 22px 16px;
    border: 1px solid #e5e7eb;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 10px;
}
.attendence_indox .badge {
    font-size: 16px;
    font-weight: 700;
    padding: 9px 22px;
    border-radius: 999px;
}
.attendence_indox .small {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}
.attendence_indox .col {
    text-align: center;              
    justify-content: center;
}
.attendence_indox .badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;                  
}
.free_del {
    text-align: center;
}
.free_del .btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;        
    text-align: center;
    gap: 6px;
}
.attendence_indox .col {
    max-width: 190px;       /
    padding: 16px 10px;     
    gap: 0px;
}
.attendence_indox .badge {
    font-size: 14px;
    padding: 6px 16px;
}
.attendence_indox .small {
    font-size: 12px;
    margin-top: 2px;
}
.free_del {
    display: flex;
    justify-content: center;
    gap: 18px;
    margin-bottom: 26px;
}
.free_del .btn {
    width: 150px;
    padding: 12px 24px;
    font-size: 15px;
    border-radius: 999px;
    font-weight: 700;
    border: none;
}
.free_del .btn-success {
    background: linear-gradient(135deg, #22c55e, #16a34a);
    color: #ffffff;
}
.free_del .btn-danger {
    background: linear-gradient(135deg, #fb7185, #ef4444);
    color: #ffffff;
}
.free_del .btn:disabled {
    opacity: 0.55;
}
.text-muted {
    background: #f1f5f9;
    padding: 16px 18px;
    border-radius: 16px;
    font-size: 14px;
    color: #475569;
    text-align: center;
}
.card-shadow,
.attendence_indox .col,
.free_del .btn {
    transition: none !important;
    transform: none !important;
}
@media (max-width: 576px) {
.page-inner {
    padding: 40px 16px;
}
.attendence_indox {
    flex-direction: column;
}
.attendence_indox .col {
    max-width: 100%;
}
.free_del {
    flex-direction: column;
}
.free_del .btn {
    width: 100%;
}
}
</style>
<div class="container  month_att" style="max-width: 540px;">
    <div class="page-inner">
        <div class="row g-0 overflow-hidden card-shadow mb-4">
            <div class="attendance-hero">
                <!--<div class="attendance-avatar">
                    <i class="fa-solid fa-user"></i>
                </div>-->
                <div class=" fs-5 fw-bold">{{ auth()->user()->name }}</div>
                <div class="attendance_single">
                    {{--@if($attendance)
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
                    @endif--}}
                </div>
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
                <div class="free_del">
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
                <div class="text-muted mt-4" style="font-size:14px">
                    <i class="bi bi-info-circle me-1"></i>
                    Please check-in at start of day, and check-out at end for full attendance.
                </div>
            </div>
        </div>
    </div>
</div>
@endsection