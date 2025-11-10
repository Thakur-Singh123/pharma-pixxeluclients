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
                                    <h4 class="card-title">Active users</h4>
                                    <form method="GET" action="#">
                                        <select name="user_type" class="form-control" onchange="handleFilterChange(this)">
                                            <option value="">All Active Users</option>
                                            <option value="MR" {{ request('user_type') == 'MR' ? 'selected' : '' }}>MR</option>
                                            <option value="vendor" {{ request('user_type') == 'vendor' ? 'selected' : '' }}>Vendor</option>
                                            <option value="purchase_manager" {{ request('user_type') == 'purchase_manager' ? 'selected' : '' }}>Purchase Manager</option>
                                            <option value="counsellor" {{ request('user_type') == 'counsellor' ? 'selected' : '' }}>Counsellor</option>
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
                                                                style="width: 156.312px;">User Type
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
                                                        <!--Get active users-->
                                                        @forelse ($active_users as $active)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $active->employee_code }}</td>
                                                            <td>{{ $active->name }}</td>
                                                            <td>{{ $active->email }}</td>
                                                            <td>{{ $active->phone }}</td>
                                                            <td>{{ $active->city }}</td>
                                                            <td>{{ $active->state }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($active->joining_date)->format('d M, Y') }}</td>
                                                            <td> 
                                                                {{  $active->user_type == 'purchase_manager' ? 'Purchase Manager' : 
                                                                ($active->user_type == 'counsellor' ? 'Counsellor' : 
                                                                ucfirst($active->user_type)) }}
                                                            </td>
                                                            <td>
                                                            <!--Check if attachment exists or not-->
                                                            @if ($active->file_attachement)
                                                                <a href="{{ asset('public/uploads/attachments/' . $active->file_attachement) }}"
                                                                    target="_blank">View
                                                                </a>
                                                            @else
                                                                -
                                                            @endif
                                                            </td>
                                                            <td>
                                                                <span class="status-badge 
                                                                    {{ $active->status == 'Pending' ? 'status-pending' : '' }} 
                                                                    {{ $active->status == 'Suspend' ? 'status-suspend' : '' }} 
                                                                    {{ $active->status == 'Active' ? 'status-active' : '' }} 
                                                                    {{ $active->status == 'Approved' ? 'status-approved' : '' }}">
                                                                    {{ ucfirst($active->status) }}
                                                                </span>
                                                            </td>
                                                            <td style="display: flex; gap: 5px;">
                                                                @if ($active->status == 'Pending')
                                                                    <form method="POST" action="{{ route('manager.user.approve', $active->id) }}">
                                                                        @csrf
                                                                        <button class="btn btn-success btn-sm">Approve</button>
                                                                    </form>
                                                                    <form method="POST" action="{{ route('manager.user.reject', $active->id) }}">
                                                                        @csrf
                                                                        <button class="btn btn-danger btn-sm">Suspend</button>
                                                                    </form>
                                                                @elseif($active->status == 'Suspend')
                                                                    <form method="POST" action="{{ route('manager.user.approve', $active->id) }}">
                                                                        @csrf
                                                                        <button class="btn btn-success btn-sm">Activate</button>
                                                                    </form>
                                                                @elseif($active->status == 'Active')
                                                                    <form method="POST" action="{{ route('manager.user.pending', $active->id) }}">
                                                                        @csrf
                                                                        <button class="btn btn-warning btn-sm">Pending</button>
                                                                    </form>
                                                                    <form method="POST" action="{{ route('manager.user.reject', $active->id) }}">
                                                                        @csrf
                                                                        <button class="btn btn-danger btn-sm">Suspend</button>
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
                                                {{ $active_users->links('pagination::bootstrap-5') }}
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
        window.location.href = "{{ route('manager.active.users') }}";
    } else {
        select.form.submit();
    }
}
</script>
@endsection