@extends('manager.layouts.master')
<style>
    body, html {
        background: #f6f7fb;
    }
    .attendance-container {
        background: #fff;
        border-radius: 14px;
        box-shadow: 0 4px 24px rgba(0,0,0,0.09), 0 1.5px 5px rgba(44,62,80,.025);
        padding: 30px 22px 24px 22px;
        margin-top: 36px;
    }
    .attendance-title {
        font-size: 2rem;
        font-weight: bold;
        color: #15447c;
        margin-bottom: 4px;
    }
    .attendance-subtitle {
        color: #888;
        margin-bottom: 28px;
        font-size: 1.08rem;
    }
    .attendance-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        background: #fff;
        border-radius: 12px;
        overflow: auto;
        margin-bottom: 0;
        box-shadow: 0 3px 6px rgba(0,0,0,0.06);
        white-space: nowrap;
    }
    .attendance-table th, .attendance-table td {
        border: none;
        padding: 8px 7px !important;
        text-align: center;
        font-size: 1rem;
    }
    .attendance-table th {
        background: #f2f7fa;
        color: #446081;
        font-weight: 700;
        letter-spacing: 0.08em;
        border-bottom: 2px solid #dee8f3;
    }
    .attendance-table tbody tr {
        transition: background 0.2s;
    }
    .attendance-table tbody tr:hover {
        background: #f7faff;
    }
    td.employee {
        text-align: left;
        display: flex;
        align-items: center;
        gap: 11px;
        font-weight: 500;
        color: #233753;
        background: #fafdff;
        border-radius: 13px;
        margin-left: 6px;
        min-width: 180px;
    }
    td.employee img {
        width: 38px;
        height: 38px;
        border-radius: 50%;
        object-fit: cover;
        border: 2px solid #e0e5ec;
        box-shadow: 0 1px 7px rgba(21,68,124,0.11);
    }
    .badge-att {
        display: inline-block;
        min-width: 25px;
        border-radius: 8px;
        font-weight: 700;
        font-size: 1.05em;
        padding: 2px 4px;
        line-height: 1.1;
    }
    .att-present {
        background: #e4fbe6;
        color: #0db81f;
        border-radius: 50%;
        height: 30px;
        line-height: 26px;
        font-size: 13px;
        min-width: 30px;
    }
    .att-half {
        background: #fffdea;
        color: #bca200;
        border-radius: 50%;
        height: 30px;
        line-height: 26px;
        font-size: 13px;
        min-width: 30px;
    }
    .att-leave {
        background: #e6f3fc;
        color: #2471c1;
        border-radius: 50%;
        height: 30px;
        line-height: 26px;
        font-size: 13px;
        min-width: 30px;
    }


    .att-absent {
    background: #fff2ee;
    color: #e04a34;
    border-radius: 50%;
    height: 30px;
    line-height: 26px;
    font-size: 13px;
    min-width: 30px;
}

    .tot-title {background: #f2f7fa; color: #7e8fa9; font-size: 0.85em;}
    @media (max-width: 700px) {
        .attendance-container {padding: 12px 4px 6px 4px;}
        .attendance-table th, .attendance-table td {font-size: 12px;padding: 3px 2px !important;}
        .attendance-title {font-size: 1.1rem;}
        td.employee img {width: 24px; height:24px;}
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
