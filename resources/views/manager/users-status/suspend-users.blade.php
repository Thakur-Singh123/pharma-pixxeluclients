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
                                                        <!--Get users-->
                                                        @forelse ($all_users as $user)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $user->employee_code }}</td>
                                                            <td>{{ $user->name }}</td>
                                                            <td>{{ $user->email }}</td>
                                                            <td>{{ $user->phone }}</td>
                                                            <td>{{ $user->city }}</td>
                                                            <td>{{ $user->state }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($user->joining_date)->format('d M, Y') }}</td>
                                                            <td>
                                                                <span class="status-badge 
                                                                    {{ $user->status == 'Pending' ? 'status-pending' : '' }} 
                                                                    {{ $user->status == 'Suspend' ? 'status-suspend' : '' }} 
                                                                    {{ $user->status == 'Active' ? 'status-active' : '' }} 
                                                                    {{ $user->status == 'Approved' ? 'status-approved' : '' }}">
                                                                    {{ ucfirst($user->status) }}
                                                                </span>
                                                            </td>
                                                            <td style="display: flex; gap: 5px;">
                                                                @if ($user->status == 'Pending')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.user.approve', $user->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-success btn-sm">Approve</button>
                                                                    </form>
                                                                    <form method="POST"
                                                                        action="{{ route('manager.user.reject', $user->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-danger btn-sm">Suspend</button>
                                                                    </form>
                                                                @elseif($user->status == 'Approved')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.user.reject', $user->id) }}">
                                                                        @csrf
                                                                        <button
                                                                            class="btn btn-danger btn-sm">Suspend</button>
                                                                    </form>
                                                                @elseif($user->status == 'Suspend')
                                                                    <form method="POST"
                                                                        action="{{ route('manager.user.approve', $user->id) }}">
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