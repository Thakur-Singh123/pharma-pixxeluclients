@extends('manager.layouts.master')
<style>
body, html {
    background: #f4f6fb;
    font-family: "Inter", system-ui, -apple-system, BlinkMacSystemFont, sans-serif;
}
.attendance-container {
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 10px 35px rgba(0,0,0,0.08);
    padding: 26px 26px 22px;
    margin-top: 30px;
}
.attendance-title {
    font-size: 1.7rem;
    font-weight: 700;
    color: #1f3b64;
    margin-bottom: 18px;
    position: relative;
}
.attendance-title::after {
    content: "";
    display: block;
    width: 90px;
    height: 3px;
    background: linear-gradient(90deg, #4270fa, #6b8cff);
    border-radius: 3px;
    margin-top: 6px;
}
.table-responsive {
    border-radius: 14px;
    overflow: auto;
}
.attendance-table {
    width: 100%;
    border-collapse: separate;
    border-spacing: 0;
    background: #fff;
    white-space: nowrap;
    box-shadow: 0 6px 20px rgba(0,0,0,0.06);
    border-radius: 14px;
}
.attendance-table thead th {
    background: linear-gradient(135deg, #1f2a44, #2f3e66);
    color: #ffffff;
    font-size: 12px;
    font-weight: 700;
    letter-spacing: 0.08em;
    padding: 11px 8px !important;
    border-right: 1px solid rgba(255,255,255,0.15);
}
.attendance-table thead th:first-child {
    border-top-left-radius: 14px;
}

.attendance-table thead th:last-child {
    border-top-right-radius: 14px;
}
.attendance-table td {
    border: 1px solid #eef1f6;
    padding: 9px 7px !important;
    font-size: 14px;
    color: #2b2b2b;
}
.attendance-table tbody tr {
    transition: background 0.18s ease-in-out;
}
.attendance-table tbody tr:hover {
    background: #f7faff;
}
td.employee {
    display: flex;
    align-items: center;
    gap: 12px;
    font-weight: 600;
    color: #243a5e;
    background: #f9fbff;
    min-width: 190px;
    padding-left: 12px !important;
}
td.employee img {
    width: 38px;
    height: 38px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid #dfe6f1;
    box-shadow: 0 2px 8px rgba(0,0,0,0.12);
}
.badge-att {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px;
    height: 30px;
    border-radius: 50%;
    font-weight: 700;
    font-size: 13px;
}
.att-present {
    background: #e7f8eb;
    color: #1ea84a;
    box-shadow: inset 0 0 0 1px #b7ebc6;
}
.att-half {
    background: #fff8dc;
    color: #b89400;
    box-shadow: inset 0 0 0 1px #ffe28a;
}

.att-leave {
    background: #eaf4ff;
    color: #2f6fd6;
    box-shadow: inset 0 0 0 1px #c5dcff;
}
.att-absent {
    background: #fff0ed;
    color: #e04a34;
    box-shadow: inset 0 0 0 1px #ffc1b6;
}
.tot-title {
    background: linear-gradient(135deg, #eef3fb, #f7f9fd) !important;
    color: #6c7f99 !important;
    font-size: 11px;
}
@media (max-width: 768px) {
    .attendance-container {
        padding: 14px 8px;
    }
    .attendance-title {
        font-size: 1.2rem;
    }
    .attendance-table th,
    .attendance-table td {
        font-size: 12px;
        padding: 5px 4px !important;
    }
    td.employee img {
        width: 26px;
        height: 26px;
    }
}
</style>
@section('content')
    <div class="container attendance-container">
        <div class="page-inner">
            <div class="attendance-title">Attendance Sheet ({{ \Carbon\Carbon::createFromDate($year, $month)->format('F Y') }})</div>
            <div class="table-responsive">
                <table class="attendance-table">
                    <thead>
                        <tr>
                            <th>Employee</th>
                            @for($d=1; $d<=$daysInMonth; $d++)
                                <th>{{ $d }}</th>
                            @endfor
                            <th class="tot-title">Full</th>
                            <th class="tot-title">Half</th>
                            <th class="tot-title">Leave</th>
                            <th class="tot-title">Absent</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendanceSummary as $item)
                        <tr>
                            <td class="employee">
                                <img src="{{ $item['employee']->image ? asset('uploads/users/' . $item['employee']->image) : 'https://i.pravatar.cc/32?user=' . $item['employee']->id }}" alt="{{ $item['employee']->name }}">
                                {{ $item['employee']->name }}
                            </td>
                            @for($d=1; $d<=$daysInMonth; $d++)
                                @php $status = $item['days'][$d]; @endphp
                                <td>
                                    @if($status == 'present')<span class="badge-att att-present">✔</span>
                                    @elseif($status == 'half')<span class="badge-att att-half">½</span>
                                    @elseif($status == 'leave')<span class="badge-att att-leave">L</span>
                                    @else <span class="badge-att att-absent">✖</span>
                                    @endif
                                </td>
                            @endfor
                            <td><span class="badge-att att-present">{{ $item['present'] }}</span></td>
                            <td><span class="badge-att att-half">{{ $item['half'] }}</span></td>
                            <td><span class="badge-att att-leave">{{ $item['leave'] }}</span></td>
                            <td><span class="badge-att att-absent">{{ $item['absent'] }}</span></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="{{ $daysInMonth+5 }}">No data found.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
