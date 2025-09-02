@extends('manager.layouts.master')

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
                            <th>#</th>
                            <th>Employee</th>
                            <th>Status</th>
                            <th>Check-In</th>
                            <th>Check-Out</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($attendances as $key => $user)
                            <tr>
                                <td>{{ $key + 1 }}</td>
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
