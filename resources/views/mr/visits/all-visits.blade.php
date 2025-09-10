@extends('mr.layouts.master')
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
                                <h4 class="card-title">All Visits</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="basic-datatables_wrapper"
                                        class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="basic-datatables"
                                                class="display table table-striped table-hover dataTable"
                                                role="grid" aria-describedby="basic-datatables_info">
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
                                                            style="width: 184.234px;">Area Code
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="basic-datatables" rowspan="1"
                                                            colspan="1"
                                                            style="width: 184.234px;">Doctor Name
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="basic-datatables" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Salary: activate to sort column ascending"
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
                                                    <!--Get visits-->
                                                    @forelse ($all_visits as $visit)
                                                    <tr role="row">
                                                        <td class="sorting_1">{{ $count++ }}.</td>
                                                        <td>{{ $visit->area_name }}</td>
                                                        <td>{{ $visit->area_block }}</td>
                                                        <td>{{ $visit->district }}</td>
                                                        <td>{{ $visit->state }}</td>
                                                        <td>{{ $visit->area_code }}</td>
                                                        <td>
                                                            {{ $visit->doctor->doctor_name ?? 'N/A' }}
                                                            ({{ $visit->doctor->specialist ?? 'N/A' }})
                                                        </td>
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
                                                            <a href="{{ url('mr/visits/edit', $visit->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a>
                                                            <a href="{{ url('mr/delete-visit', $visit->id) }}" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a>
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