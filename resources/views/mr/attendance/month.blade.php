@extends('mr.layouts.master')
@section('content')
<style>
body {
    background: #f5f7fb;
}
.page-inner {
    background: #ffffff;
    border-radius: 16px;
    padding: 22px;
    box-shadow: 0 8px 30px rgba(0, 0, 0, 0.05);
}
.heading_attendance h2,
.heading_attendance h2:hover,
.heading_attendance h2:active,
.heading_attendance h2:focus {
    background: linear-gradient(135deg, #4270fa, #6b8cff);
    color: #fff !important;
    padding: 14px 20px;
    border-radius: 12px;
    font-size: 20px;
    font-weight: 700;
    box-shadow: 0 6px 15px rgba(66, 112, 250, 0.3);
}
.heading_attendance h2 {
    pointer-events: none;
}
.summ-badge {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    border-radius: 30px;
    font-weight: 700;
    padding: 10px 22px;
    font-size: 13px;
    white-space: nowrap;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
}
.summ-present {
    background: #e6fde9;
    color: #22ac45;
    border: 1px solid #a2f6b5;
}
.summ-half {
    background: #fffce4;
    color: #b88d01;
    border: 1px solid #fff2a3;
}
.summ-absent {
    background: #fff1ec;
    color: #df493b;
    border: 1px solid #ffc6b9;
}
.summ-leave {
    background: #ebf2fa;
    color: #3877b5;
    border: 1px solid #b3dafe;
}
.att-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #ffffff;
    border-radius: 14px;
    overflow: hidden;
    box-shadow: 0 6px 25px rgba(0, 0, 0, 0.06);
}
.att-table thead th {
    background: linear-gradient(135deg, #1f2a44, #2e3c63);
    color: #ffffff;
    padding: 12px 8px;
    font-size: 13px;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    border: none;
}
.att-table tbody td {
    padding: 10px 6px;
    font-size: 14px;
    color: #333;
    border-bottom: 1px solid #f0f0f0;
}
.att-table tbody tr:hover {
    background: #f8faff;
    transition: 0.2s ease-in-out;
}
.att-present {
    color: #22ac45;
    font-weight: 700;
}
.att-half {
    color: #b88d01;
    font-weight: 700;
}
.att-absent {
    color: #df493b;
    font-weight: 700;
}
.att-leave {
    color: #3877b5;
    font-weight: 700;
}
.fa-fw {
    width: 1.3em;
}
@media (max-width: 768px) {
    .summ-badge {
        padding: 8px 14px;
        font-size: 12px;
    }
    .att-table thead th,
    .att-table tbody td {
        font-size: 12px;
    }
}
</style>
    <div class="container">
        <div class="page-inner">
            <div class="mb-4 text-center heading_attendance">
                <h2 class="fw-bold text-primary">My Attendance ({{ now()->format('F Y') }})</h2>
            </div>
            <div class="row g-1 mb-3 align-items-center row_disable_code">
                @php
                    $present = 0;
                    $half = 0;
                    $short = 0;
                    $absent = 0;
                    $leave = 0;

                    foreach ($days as $d) {
                        // Leave check FIRST
                        if ($d['status'] === 'leave') {
                            $leave++;
                            continue; 
                        }

                        $checkIn = $d['check_in'] ? \Carbon\Carbon::parse($d['check_in']) : null;
                        $checkOut = $d['check_out'] ? \Carbon\Carbon::parse($d['check_out']) : null;

                        // No check-in or check-out → Absent
                        if (!$checkIn || !$checkOut) {
                            $absent++;
                            continue;
                        }

                        // Calculate hours
                        $diffInMinutes = $checkIn->diffInMinutes($checkOut);
                        $hours = floor($diffInMinutes / 60);

                        // Status logic
                        if ($hours >= 10) {
                            $present++;
                        } elseif ($hours >= 5) {
                            $half++;
                        } elseif ($hours >= 2) {
                            $short++;
                        } else {
                            $absent++;
                        }
                    }
                @endphp
                <div class="col-auto">
                    <span class="summ-badge summ-present"><i class="bi bi-check-circle-fill me-1"></i> Present:
                        {{ $present }}
                    </span>
                </div>
                <div class="col-auto">
                    <span class="summ-badge summ-half"><i class="bi bi-hourglass-split me-1"></i> Half Day:
                        {{ $half }}
                    </span>
                </div>
                <div class="col-auto">
                    <span class="summ-badge summ-half"><i class="bi bi-hourglass-split me-1"></i> Short Leave:
                        {{ $short }}
                    </span>
                </div>
                <div class="col-auto">
                    <span class="summ-badge summ-half"><i class="bi bi-hourglass-split me-1"></i>Leave:
                        {{ $leave }}
                    </span>
                </div>
                <div class="col-auto">
                    <span class="summ-badge summ-absent"><i class="bi bi-x-circle-fill me-1"></i> Absent:
                        {{ $absent }}
                    </span>
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
                                } elseif ($checkIn && !$checkOut) {
                                     $totalTime = 0;
                                }
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($d['date'])->format('d M, D') }}</td>
                                <td>
                                    @if ($d['status'] === 'present')
                                        <span class="att-present"><i class="bi bi-check-circle-fill fa-fw me-1"></i>
                                            Present
                                        </span>
                                    @elseif($d['status']  === 'half')
                                        <span class="att-half"><i class="bi bi-hourglass-split fa-fw me-1"></i> Half
                                            Day
                                        </span>
                                    @elseif($d['status']  === 'short_leave')
                                        <span class="att-half"><i class="bi bi-hourglass-split fa-fw me-1"></i> Short
                                            Leave
                                        </span>
                                    @elseif($d['status']  === 'leave')
                                        <span class="att-half"><i class="bi bi-hourglass-split fa-fw me-1"></i>
                                            Leave
                                        </span>
                                    @else
                                        <span class="att-absent"><i class="bi bi-x-circle-fill fa-fw me-1"></i>
                                            Absent
                                        </span>
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
