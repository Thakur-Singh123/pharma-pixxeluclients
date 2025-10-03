@extends('manager.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <div class="row">
                    <div class="col-md-12">
                        <div class="card">
                            <div class="card-header d-flex justify-content-between align-items-center">
                                <h4 class="card-title">All Events</h4>
                                <form method="GET" action="{{ route('manager.events.index') }}">
                                <select name="created_by" class="form-control" onchange="handleFilterChange(this)">
                                    <option value="">📋 All Events</option>
                                    <option value="manager" {{ request('created_by') == 'manager' ? 'selected' : '' }}>👤 Created by Me (Manager)</option>
                                    <option value="mr" {{ request('created_by') == 'mr' ? 'selected' : '' }}>🧑‍💼 Created by MR</option>
                                </select>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="basic-datatables_wrapper"
                                        class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="basic-datatables" class="display table table-striped table-hover dataTable" role="grid" aria-describedby="basic-datatables_info">
                                                    <thead>
                                                        <tr role="row">
                                                            <th class="sorting_asc" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" aria-sort="ascending"
                                                                style="width: 242.688px;">Sr No.
                                                            </th>
                                                            <th class="sorting_asc" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" aria-sort="ascending"
                                                                style="width: 242.688px;">Title
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Description
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Doctor Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1" colspan="1"
                                                                style="width: 366.578px;">
                                                                Assigned To 
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">Location
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">Area Pin Code
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">Start Date & Time
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">End Date & Time
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">Created By
                                                            </th>
                                                                <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">QR Code</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">Status
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $count = 1 @endphp
                                                        <!--Get events-->
                                                        @forelse ($events as $event)
                                                            <tr role="row">
                                                                <td class="sorting_1">{{ $count++ }}.</td>
                                                                <td>{{ $event->title }}</td>
                                                                <td>{{ $event->description }}</td>
                                                                <td>{{ $event->doctor_detail->doctor_name ?? 'N/A' }}</td>
                                                                <td>
                                                                {{ optional($event->mr)->name }} 
                                                                </td>
                                                                <td>{{ $event->location }}</td>
                                                                <td>{{ $event->pin_code }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($event->start_datetime)->format('d M Y, h:i A') }}</td>
                                                                <td>{{ \Carbon\Carbon::parse($event->end_datetime)->format('d M Y, h:i A') }}</td>
                                                                <td>{{ $event->created_by }}</td>
                                                                <td>
                                                                    {!! $event->qr_code_path 
                                                                        ? '<img src="' . asset('public/qr_codes/' . $event->qr_code_path) . '" alt="qr code" width="100" height="100">' 
                                                                        : 'N/A' 
                                                                    !!}
                                                                </td>
                                                                <td>
                                                                    <form action="{{ route('manager.event.update.status', $event->id) }}" method="POST" class="status-form">
                                                                        @csrf
                                                                        <select name="status" class="custom-status-dropdown" onchange="this.form.submit()">
                                                                            <option value="pending" {{ $event->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                            <option value="in_progress" {{ $event->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                                            <option value="completed" {{ $event->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                                        </select>
                                                                    </form>
                                                                </td>
                                                                <td>
                                                                    <div class="form-button-action">
                                                                        <a href="{{ route('manager.events.edit', $event->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                        <form action="{{ route('manager.events.destroy', $event->id) }}" method="POST" style="display:inline;">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <a href="#" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete" onclick="event.preventDefault(); this.closest('form').submit();">
                                                                                <i class="fa fa-trash"></i>
                                                                            </a>
                                                                        </form>
                                                                    </div>
                                                                </td>
                                                            </tr>
                                                        @empty
                                                            <tr>
                                                                <td colspan="10" class="text-center">No record found</td>
                                                            </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $events->appends(request()->query())->links('pagination::bootstrap-5') }}    
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
function handleFilterChange(select) {
    if (select.value === "") {
        window.location.href = "{{ route('manager.events.index') }}";
    } else {
        select.form.submit();
    }
}
</script>
@endsection
