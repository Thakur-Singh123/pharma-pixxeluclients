@extends('manager.layouts.master')
<style>
body {
    background: #f4f7fb;
}
.attendance-container .card {
    border: 1px solid #e6ebf2;
    border-radius: 10px;
    box-shadow: 0 8px 24px rgba(0,0,0,0.06);
    overflow: hidden;
}
.attendance-container .card-header {
    background: linear-gradient(135deg, #2563eb, #3b82f6) !important;
    padding: 16px 22px;
    border-bottom: 1px solid rgba(255,255,255,0.25);
}
.attendance-container .card-header h4 {
    font-size: 18px;
    font-weight: 700;
    color: #ffffff;
    margin: 0;
    letter-spacing: 0.3px;
}
.attendance-container .card-header h4::after {
    content: "";
    display: block;
    width: 55px;
    height: 3px;
    margin-top: 6px;
    background: rgba(255,255,255,0.7);
    border-radius: 3px;
}
.attendance-container .card-header .badge {
    background: rgba(255,255,255,0.18) !important;
    color: #ffffff !important;
    font-size: 12px;
    font-weight: 600;
    padding: 6px 14px;
    border-radius: 999px;
}
.attendance-container table.table {
    width: 100%;
    border: 1px solid #e6ebf2 !important;
    border-radius: 8px;
    border-collapse: separate;
    border-spacing: 0;
    overflow: hidden;
    background: #ffffff;
}
.attendance-container .table-bordered > :not(caption) > * > * {
    border: none !important;
}
.attendance-container thead.table-light th {
    background: #ffffff !important;
    font-size: 12px;
    font-weight: 700;
    color: #111827;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    padding: 12px 10px;
    border-bottom: 1px solid #e6ebf2 !important;
}
.attendance-container thead th:not(:last-child) {
    border-right: 1px solid #e6ebf2 !important;
}
.attendance-container tbody tr {
    border-bottom: 1px solid #edf1f7;
}
.attendance-container tbody td {
    padding: 14px 12px;
    font-size: 14px;
    color: #1f2937;
    vertical-align: middle;
}
.attendance-container tbody td:not(:last-child) {
    border-right: 1px solid #f1f4f9;
}
.attendance-container tbody td:nth-child(2) {
    min-width: 260px;
}
.attendance-container tbody td img {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    border: 2px solid #e5e7eb;
}
.attendance-container .badge {
    font-size: 12px;
    font-weight: 600;
    padding: 5px 12px;
    border-radius: 999px;
}
.attendance-container .badge.bg-success {
    background: #22c55e !important;
    color: #ffffff !important;
}
.attendance-container .badge.bg-warning {
    background: #facc15 !important;
    color: #1f2937 !important;
}
.attendance-container .badge.bg-danger,
.attendance-container .badge.bg-secondary {
    background: #ef4444 !important;
    color: #ffffff !important;
}
@media (max-width: 768px) {
    .attendance-container .card-header h4 {
        font-size: 16px;
    }
    .attendance-container tbody td {
        font-size: 13px;
        padding: 10px 8px;
    }
    .attendance-container tbody td:nth-child(2) {
        min-width: 200px;
    }
    .attendance-container tbody td img {
        width: 32px;
        height: 32px;
    }
}
</style>
@section('content')
<div class="container attendance-container">
    <div class="page-inner">
        <div class="card shadow-lg rounded-3">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 class="mb-0">Daily Attendance - {{ \Carbon\Carbon::today()->format('d M, Y') }}</h4>
                <span class="badge bg-light text-dark">Total: {{ count($attendances) }}</span>
            </div>
            <div class="card-body">
                <table class="table table-bordered table-hover align-middle">
                    <thead class="table-light">
                        <tr>
                            <th>Sr No.</th>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}.</td>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="{{ $user['image'] ?: 'https://ui-avatars.com/api/?name=' . urlencode($user['name']) }}"
                                            alt="avatar" class="rounded-circle me-2" width="40" height="40">
                                        <div>
                                            <strong>{{ $user['name'] }}</strong><br>
                                            <small class="text-muted">{{ $user['email'] }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    @if(!empty($user['attendance']) && isset($user['attendance'][0]['status']))
                                        @php
                                            $status = strtolower($user['attendance'][0]['status']);
                                        @endphp
                                        @if($status === 'present')
                                            <span class="badge bg-success">Present</span>
                                        @elseif($status === 'half')
                                            <span class="badge bg-warning text-dark">Half Day</span>
                                        @else
                                            <span class="badge bg-danger">{{ ucfirst($status) }}</span>
                                        @endif
                                    @else
                                        <span class="badge bg-secondary">Absent</span>
                                    @endif
                                </td>
                                <td>
                                    {{ !empty($user['attendance']) && $user['attendance'][0]['check_in'] ? \Carbon\Carbon::parse($user['attendance'][0]['check_in'])->format('h:i A') : '-' }}
                                </td>
                                <td>
                                    {{ !empty($user['attendance']) && $user['attendance'][0]['check_out'] ? \Carbon\Carbon::parse($user['attendance'][0]['check_out'])->format('h:i A') : '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">No attendance records found for today.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
