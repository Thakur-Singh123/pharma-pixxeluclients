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
                            <div class="card-header">
                                <h4 class="card-title">All Problems & Challenges</h4>
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
                                                                style="width: 366.578px;">Visit Area 
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Camp Type 
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Doctor Name 
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Start Date 
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">End Date 
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 187.688px;">Description
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 187.688px;">Mr Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Status
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 366.578px;">Approval
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
                                                        <!--Get problems-->
                                                        @forelse ($all_problems as $problem)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $problem->title }}</td>
                                                            <td>{{ $problem->visit_name }}</td>
                                                            <td>{{ $problem->camp_type }}</td>
                                                            <td>{{ $problem->doctor_name }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($problem->start_date)->format('d M, Y') }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($problem->end_date)->format('d M, Y') }}</td>
                                                            <td>{{ $problem->description }}</td>
                                                            <td>{{ $problem->mr_detail->name }}</td>
                                                            <td>
                                                                <span class="status-badge 
                                                                    {{ $problem->status == 'pending' ? 'status-pending' : '' }}
                                                                    {{ $problem->status == 'rejected' ? 'status-suspend' : '' }}
                                                                    {{ $problem->status == 'approved' ? 'status-approved' : '' }}">
                                                                    {{ ucfirst($problem->status) }}
                                                                </span>
                                                            </td>
                                                            <td style="display: flex; gap: 5px;">
                                                                @if ($problem->status == 'pending')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.problem.approve', $problem->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-success btn-sm">
                                                                            Approve
                                                                        </button>
                                                                    </form>
                                                                    <form method="POST"
                                                                        action="{{ route('manager.problem.reject', $problem->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-danger btn-sm">
                                                                            Reject
                                                                        </button>
                                                                    </form>
                                                                @elseif($problem->status == 'approved')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.problem.reject', $problem->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-danger btn-sm">
                                                                            Reject
                                                                        </button>
                                                                    </form>
                                                                @elseif($problem->status == 'rejected')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.problem.approve', $problem->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-success btn-sm">
                                                                            Approve
                                                                        </button>
                                                                    </form>
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action"> 
                                                                    <a href="{{ route('manager.problems.edit', $problem->id) }}" class="icon-button view-btn custom-tooltip" data-tooltip="View">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                                   <form action="{{ route('manager.problems.destroy', $problem->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center">No problems found.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $all_problems->links('pagination::bootstrap-5') }}
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