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
                                <h4 class="card-title">All Tour Plans</h4>
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
                                                                colspan="1" style="width: 366.578px;">Description
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 366.578px;">Location
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 366.578px;">Pin Code
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 366.578px;">Doctor Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">Start Date
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 156.312px;">End date
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 366.578px;">Assigned Mr Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 187.688px;">Status
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 366.578px;">Approval
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 187.688px;">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $count = 1 @endphp
                                                        <!--Get assigned tour plans-->
                                                        @forelse ($all_tour_plan as $tour)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $tour->title }}</td>
                                                            <td>{{ $tour->description }}</td>
                                                            <td>{{ $tour->location }}</td>
                                                            <td>{{ $tour->pin_code }}</td>
                                                            <td>{{ $tour->doctor['doctor_name'] ?? 'N/A'}}</td>
                                                            <td>{{ \Carbon\Carbon::parse($tour->start_date)->format('d M, Y') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($tour->end_date)->format('d M, Y') }}</td>
                                                            <td>{{ $tour->mr->name }}</td>
                                                            <td>
                                                                <span class="status-badge 
                                                                    {{ $tour->approval_status == 'Pending' ? 'status-pending' : '' }}
                                                                    {{ $tour->approval_status == 'Rejected' ? 'status-suspend' : '' }}
                                                                    {{ $tour->approval_status == 'Approved' ? 'status-approved' : '' }}">
                                                                    {{ ucfirst($tour->approval_status) }}
                                                                </span>
                                                            </td>
                                                            <td style="display: flex; gap: 5px;">
                                                                @if ($tour->approval_status == 'Pending')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.tour.approve', $tour->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-success btn-sm">Approve</button>
                                                                    </form>
                                                                    <form method="POST"
                                                                        action="{{ route('manager.tour.reject', $tour->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-danger btn-sm">Reject</button>
                                                                    </form>
                                                                @elseif($tour->approval_status == 'Approved')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.tour.reject', $tour->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-danger btn-sm">Reject</button>
                                                                    </form>
                                                                @elseif($tour->approval_status == 'Rejected')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.tour.approve', $tour->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-success btn-sm">Approve</button>
                                                                    </form>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    <a href="{{ url('manager/edit-tour-plan', $tour->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                    <!--<form action="{{ route('mr.tasks.destroy', $tour->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <a href="#" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete" onclick="event.preventDefault(); this.closest('form').submit();">
                                                                            <i class="fa fa-trash"></i>
                                                                        </a>
                                                                        </form>-->
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
                                                {{ $all_tour_plan->appends(request()->query())->links('pagination::bootstrap-5') }}     
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