@extends('mr.layouts.master')

@section('content')
@php
    $present = $half = $absent = $leave = 0;
    foreach($days as $d) {
        if(($d['check_in'] ?? null) && ($d['check_out'] ?? null)) $present++;
        else if(($d['check_in'] ?? null) && !($d['check_out'] ?? null)) $half++;
        else $absent++;
        // (for "leave" logic, add here if you support it in data)
    }
@endphp
<style>
    .att-table {width: 100%;border-collapse: separate;border-spacing: 0;background: #fff;border-radius: 14px;box-shadow: 0 3px 20px rgba(66,112,250,0.06);overflow: hidden;}
    .att-table th, .att-table td {padding: 6px 7px;text-align: center;font-size: 1rem;vertical-align: middle;border: none;}
    .att-table th {background: #f4f8fd;color: #4270fa;letter-spacing: 0.06em;font-weight: 700;}
    .row-summary td {background: #f4f8fd;font-weight:700;font-size:1.09em;}
    .summ-badge {padding:4px 17px;font-size:1.05em;font-weight:700;border-radius:18px;letter-spacing:0.02em;}

    .summ-present {
        background: #e6fde9;
        color: #22ac45;
        border: 1px solid #a2f6b5;
        padding: 10px 27px;
        font-size: 13px;
    }
.row_disable_code .col-auto {
    padding: 17px 5px;
}

.row_disable_code {
    margin-left: 0px;
}
    .summ-half {
        background:#fffce4;
        color:#b88d01;
        border: 1px solid #fff6a3;
        padding: 10px 27px;
        font-size: 13px;
    }
.heading_attendance h2 {
    color: #000 !important;
    background: #ffff;
    border: 1px solid#ddd;
    padding: 10px;
    border-radius: 10px;
    font-size: 18px;
}
    .summ-absent {background:#fff1ec;color:#df493b;border: 1px solid #ffcab9;padding: 10px 27px;
        font-size: 13px;}
    .summ-leave {background:#ebf2fa;color:#3877b5;border: 1px solid #b3dafe;padding: 10px 27px;
        font-size: 13px;}
    .att-present {color:#22ac45; font-weight:700;}
    .att-half {color:#b88d01;font-weight:700;}
    .att-absent {color:#df493b;font-weight:700;}
    .att-leave {color:#3877b5;font-weight:700;}
    .fa-fw {width:1.28em;}
    @media (max-width:780px) {
        .att-table th, .att-table td{font-size:0.93em;}
    }
</style>
<div class="container">
    <div class="page-inner">
    <div class="mb-4 text-center heading_attendance">
        <h2 class="fw-bold text-primary">My Attendance ({{ now()->format('F Y') }})</h2>
    </div>
    <div class="row g-1 mb-3 align-items-center row_disable_code">
        <div class="col-auto">
            <span class="summ-badge summ-present"><i class="bi bi-check-circle-fill me-1"></i> Present: {{ $present }}</span>
        </div>
        <div class="col-auto">
            <span class="summ-badge summ-half"><i class="bi bi-hourglass-split me-1"></i> Half Day: {{ $half }}</span>
        </div>
        <div class="col-auto">
            <span class="summ-badge summ-absent"><i class="bi bi-x-circle-fill me-1"></i> Absent: {{ $absent }}</span>
        </div>
    </div>
    <div class="table-responsive">
        <table class="att-table">
    <thead>
        <tr>
            <th>Date</th>
            <th>Status</th>
            <th>Check In</th>
            <th>Check Out</th>
            <th>Total Time</th>
        </tr>
    </thead>
    <tbody>
        @forelse($days as $d)
            @php
                $checkIn = $d['check_in'] ? \Carbon\Carbon::parse($d['check_in']) : null;
                $checkOut = $d['check_out'] ? \Carbon\Carbon::parse($d['check_out']) : null;

                $totalTime = null;
                $status = 'absent';

                if ($checkIn && $checkOut) {
                    $diffInMinutes = $checkIn->diffInMinutes($checkOut);
                    $hours = floor($diffInMinutes / 60);
                    $minutes = $diffInMinutes % 60;
                    $totalTime = sprintf('%02dh %02dm', $hours, $minutes);

                    $status = $hours >= 8 ? 'present' : 'half-day';
                } elseif ($checkIn && !$checkOut) {
                    $status = 'half-day';
                }
            @endphp

            <tr>
                <td>{{ \Carbon\Carbon::parse($d['date'])->format('d M, D') }}</td>
                <td>
                    @if($status === 'present')
                        <span class="att-present"><i class="bi bi-check-circle-fill fa-fw me-1"></i> Present</span>
                    @elseif($status === 'half')
                        <span class="att-half"><i class="bi bi-hourglass-split fa-fw me-1"></i> Half Day</span>
                    @else
                        <span class="att-absent"><i class="bi bi-x-circle-fill fa-fw me-1"></i> Absent</span>
                    @endif
                </td>
                <td>{{ $checkIn ? $checkIn->format('h:i A') : '-' }}</td>
                <td>{{ $checkOut ? $checkOut->format('h:i A') : '-' }}</td>
                <td>{{ $totalTime ?? '-' }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" class="text-center text-muted">No data found.</td>
            </tr>
        @endforelse
    </tbody>
</table>

    </div>
</div>
</div>
@endsection
