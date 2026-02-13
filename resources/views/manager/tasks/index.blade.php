@extends('manager.layouts.master')
@section('content')
    @php
        $createdBy = request('created_by');
    @endphp
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
                                    <h4 class="card-title">All Tasks</h4>
                                    <form method="GET" action="{{ route('manager.tasks.index') }}" class="m-0 d-flex align-items-center" style="gap: 10px;">
                                        <select name="created_by" class="form-control" onchange="if(this.value==''){ window.location='{{ route('manager.tasks.index') }}'; } else { this.form.submit(); }">
                                            <option value="" disabled selected>Select Tasks</option>    
                                            <option value="">All Tasks</option>
                                            <option value="manager"
                                                {{ request('created_by') == 'manager' ? 'selected' : '' }}>Created by Me
                                                (manager)</option>
                                            <option value="mr" {{ request('created_by') == 'mr' ? 'selected' : '' }}>
                                                Created by MR</option>
                                        </select>
                                        <input type="date"
                                            name="start_date"
                                            class="form-control"
                                            value="{{ request('start_date') }}"
                                            onchange="this.form.submit()"
                                        >
                                    </form>
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
                                                                    colspan="1" style="width: 366.578px;">
                                                                   Assigned To 
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 156.312px;">Start Date
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 156.312px;">End date
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 156.312px;">Created By
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 156.312px;">Status
                                                                </th>
                                                                <!-- <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 156.312px;">Status
                                                                </th> -->
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 156.312px;">Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1 @endphp
                                                            <!--Get tasks-->
                                                            @forelse ($tasks as $task)
                                                                <tr role="row">
                                                                    <td class="sorting_1">{{ $count++ }}.</td>
                                                                    <td>{{ $task->title }}</td>
                                                                    <td>{{ $task->description }}</td>
                                                                    <td>{{ $task->location }}</td>
                                                                    <td>{{ $task->pin_code }}</td>
                                                                    <td>
                                                                        {{ $task->doctor->doctor_name ?? 'N/A' }}
                                                                            @if(isset($task->doctor->specialist))
                                                                                ({{ $task->doctor->specialist }})
                                                                            @endif
                                                                    </td>
                                                                    <td> 
                                                                        {{ optional($task->mr)->name ?? 'N/A'}}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($task->start_date)->format('d M, Y') }}
                                                                    </td>
                                                                    <td>{{ \Carbon\Carbon::parse($task->end_date)->format('d M, Y') }}
                                                                    </td>
                                                                    <td>{{ $task->created_by }}</td>
                                                                    <td>
                                                                        <form action="{{ route('manager.tasks.update.status', $task->id) }}" method="POST" class="status-form">
                                                                            @csrf
                                                                            <select name="status" class="custom-status-dropdown" onchange="this.form.submit()">
                                                                                <option value="pending" {{ $task->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                                                                <option value="in_progress" {{ $task->status == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                                                                <option value="completed" {{ $task->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                                            </select>
                                                                        </form>
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-button-action">
                                                                            @if($task->is_active != '1')
                                                                                <a href="{{ route('manager.tasks.edit', $task->id) }}"
                                                                                    class="icon-button edit-btn custom-tooltip"
                                                                                    data-tooltip="Edit">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </a>
                                                                            @else
                                                                                <a href="{{ route('manager.tasks.edit', $task->id) }}" class="icon-button view-btn custom-tooltip" data-tooltip="View">
                                                                                    <i class="fa fa-eye"></i>
                                                                                </a>
                                                                            @endif
                                                                            <form
                                                                                action="{{ route('manager.tasks.destroy', $task->id) }}"
                                                                                method="POST" style="display:inline;">
                                                                                @csrf
                                                                                @method('DELETE')
                                                                                <a href="#"
                                                                                    class="icon-button delete-btn custom-tooltip"
                                                                                    data-tooltip="Delete"
                                                                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </a>
                                                                            </form>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="10" class="text-center">No record found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    {{ $tasks->appends(request()->query())->links('pagination::bootstrap-5') }}
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
        window.location.href = "{{ route('manager.tasks.index') }}";
    } else {
        select.form.submit();
    }
}
</script>
@endsection
