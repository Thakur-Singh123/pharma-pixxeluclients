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
                                <h4 class="card-title">Suspend users</h4>
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
                                                                style="width: 242.688px;">Employee Code
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 366.578px;">Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 187.688px;">Email
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 84.5px;">Phone</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 184.234px;">City
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">State
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Joining Date
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Attachment
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
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $count = 1 @endphp
                                                        <!--Get suspend users-->
                                                        @forelse ($all_users as $suspend)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $suspend->employee_code }}</td>
                                                            <td>{{ $suspend->name }}</td>
                                                            <td>{{ $suspend->email }}</td>
                                                            <td>{{ $suspend->phone }}</td>
                                                            <td>{{ $suspend->city }}</td>
                                                            <td>{{ $suspend->state }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($suspend->joining_date)->format('d M, Y') }}</td>
                                                            <td>
                                                                <!--Check if attachment exists or not-->
                                                                @if ($suspend->file_attachement)
                                                                    <a href="{{ asset('public/uploads/attachments/' . $suspend->file_attachement) }}"
                                                                        target="_blank">View
                                                                    </a>
                                                                @else
                                                                    -
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <span class="status-badge 
                                                                    {{ $suspend->status == 'Pending' ? 'status-pending' : '' }} 
                                                                    {{ $suspend->status == 'Suspend' ? 'status-suspend' : '' }} 
                                                                    {{ $suspend->status == 'Active' ? 'status-active' : '' }} 
                                                                    {{ $suspend->status == 'Approved' ? 'status-approved' : '' }}">
                                                                    {{ ucfirst($suspend->status) }}
                                                                </span>
                                                            </td>
                                                            <td style="display: flex; gap: 5px;">
                                                                @if ($suspend->status == 'Pending')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.user.approve', $suspend->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-success btn-sm">Approve</button>
                                                                    </form>
                                                                    <form method="POST"
                                                                        action="{{ route('manager.user.reject', $suspend->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-danger btn-sm">Suspend</button>
                                                                    </form>
                                                                @elseif($suspend->status == 'Approved')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.user.reject', $suspend->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-danger btn-sm">Suspend</button>
                                                                    </form>
                                                                @elseif($suspend->status == 'Suspend')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.user.approve', $suspend->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-success btn-sm">Approve</button>
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
                                                {{ $all_users->links('pagination::bootstrap-5') }}
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