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
                                    <h4 class="card-title">Waiting For Approval Events</h4>
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
                                                                @php
                                                                    $createdBy = request('created_by');
                                                                @endphp

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
                                                                    <td>  
                                                                    {{ optional($event->mr)->name }} 
                                                                    </td>
                                                                    <td>{{ $event->location }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($event->start_datetime)->format('d M Y, h:i A') }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($event->end_datetime)->format('d M Y, h:i A') }}</td>
                                                                    <td>
                                                                        {!! $event->qr_code_path 
                                                                            ? '<img src="' . asset('public/qr_codes/' . $event->qr_code_path) . '" alt="qr code" width="100" height="100">' 
                                                                            : 'N/A' 
                                                                        !!}
                                                                    </td>
                                                                    <td>
                                                                        <span class="status-badge 
                                                                            {{ $event->status == 'pending' ? 'status-pending' : '' }}
                                                                            {{ $event->status == 'in_progress' ? 'status-progress' : '' }}
                                                                            {{ $event->status == 'completed' ? 'status-completed' : '' }}">
                                                                                {{ $event->status == 'in_progress' ? 'In Progress' : ucfirst($event->status) }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                    @if ($event->is_active == false)
                                                                        <form method="POST" action="{{ route('manager.approved.events', $event->id) }}">
                                                                            @csrf
                                                                            <button class="btn btn-success btn-sm">Approve</button>
                                                                        </form>
                                                                    @elseif($event->is_active == true)
                                                                        <form method="POST" action="{{ route('manager.rejected.events', $event->id) }}">
                                                                            @csrf
                                                                            <button class="btn btn-danger btn-sm">Reject</button>
                                                                        </form>    
                                                                    @endif
                                                                </td>
                                                                </tr>
                                                           @empty
                                                                <tr>
                                                                    <td colspan="10" class="text-center">No record found</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    {{ $events->links('pagination::bootstrap-5') }}
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
@endsection
