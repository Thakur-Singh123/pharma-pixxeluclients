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
                                <h4 class="card-title">All Visits</h4>
                                <form method="GET" action="{{ url('manager/visit-filter') }}" class="p-3">
                                    <div class="filter-row">
                                        <input type="text" name="area_name" class="form-control" value="{{ request('area_name') }}" placeholder="Enter area name">
                                        <select name="status" id="status" class="form-cate-status-fliter">
                                            <option value="" disabled selected>Select Status</option>
                                            <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>All Status</option>
                                            <option value="Active" {{ request('status') == 'Active' ? 'selected' : '' }}>Active</option>
                                            <option value="Pending" {{ request('status') == 'Pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="Suspend" {{ request('status') == 'Suspend' ? 'selected' : '' }}>Suspend</option>
                                            <option value="Approved" {{ request('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        </select>
                                        <button type="submit" class="btn btn-success">
                                            <span style="margin-right: 0px; font-weight: bold;">|||</span> Filter
                                        </button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
                                    </div>
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
                                                                style="width: 242.688px;">Area Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Area Block
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 187.688px;">District
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 84.5px;">State</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Area Pin Code
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Visit Date
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Comments
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Mr Name
                                                            </th>
                                                            <!-- <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Visit Type
                                                            </th> -->
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Status
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $count = 1 @endphp
                                                        @forelse ($all_visits as $visit)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $visit->area_name }}</td>
                                                            <td>{{ $visit->area_block }}</td>
                                                            <td>{{ $visit->district }}</td>
                                                            <td>{{ $visit->state }}</td>
                                                            <td>{{ $visit->pin_code }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d M, Y') }}</td>
                                                            <td>{{ $visit->comments }}</td>
                                                            <td>{{ $visit->mr['name'] }}</td>
                                                            <!-- <td>
                                                                @if($visit->visit_type == 'other')
                                                                    Other Visit -
                                                                    ({{ $visit->other_visit ?? 'N/A' }})
                                                                @elseif($visit->visit_type == 'doctor')
                                                                    Doctor Visit - 
                                                                    ({{ $visit->doctor->doctor_name ?? 'N/A' }} -
                                                                    {{ $visit->doctor->specialist ?? 'N/A' }})
                                                                @elseif($visit->visit_type == 'religious_places')
                                                                    Religious Places -
                                                                    ({{ $visit->religious_place ?? 'N/A' }})
                                                                @endif
                                                            </td> -->
                                                            <td>
                                                                <span class="status-badge 
                                                                    {{ $visit->status == 'Pending' ? 'status-pending' : '' }}
                                                                    {{ $visit->status == 'Suspend' ? 'status-suspend' : '' }}
                                                                    {{ $visit->status == 'Active' ? 'status-active' : '' }}
                                                                    {{ $visit->status == 'Approved' ? 'status-approved' : '' }}">
                                                                    {{ ucfirst($visit->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    <a href="{{ route('manager.visits.edit', $visit->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('manager.visits.destroy', $visit->id) }}" method="POST" style="display:inline;">
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
                                                            <td colspan="8" class="text-center">No visits found.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $all_visits->links('pagination::bootstrap-5') }}
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