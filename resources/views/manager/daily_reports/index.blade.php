@extends('manager.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    {{--Success Message--}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">All Reports</h4>
                                    <div class="d-flex" style="gap: 20px;">
                                        <form method="GET" action="{{ route('manager.daily-reports.index') }}" class="m-0" id="filter-form">
                                            <select name="filter_by" onchange="this.form.submit()"
                                                class="form-control">
                                                <option value="">Select</option>
                                                <option value="today" {{ request('filter_by') == 'today' ? 'selected' : '' }}>
                                                    Today</option>
                                                <option value="week" {{ request('filter_by') == 'week' ? 'selected' : '' }}>
                                                    This Week</option>
                                                <option value="month" {{ request('filter_by') == 'month' ? 'selected' : '' }}>
                                                    This Month</option>
                                                <option value="year" {{ request('filter_by') == 'year' ? 'selected' : '' }}>
                                                    This Year</option>
                                                <option value="all" {{ request('filter_by') == 'all' ? 'selected' : '' }}>
                                                    All</option>
                                            </select>
                                        </form>
                                        <a href="{{ route('manager.reports.export.daily', ['filter_by' => request('filter_by') ?? 'today']) }}" class="btn btn-primary">Export Report</a>
                                    </div>
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
                                                                    style="width: 242.688px;">Report Date
                                                                </th>
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">MR Name
                                                                </th>
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">Status
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Approval
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Action
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1 @endphp
                                                            @forelse ($reports as $report)
                                                                <tr role="row">
                                                                    <td class="sorting_1">{{ $count++ }}.</td>
                                                                    <td>{{ \Carbon\Carbon::parse($report->report_date)->format('d M, Y') }}</td>
                                                                    <td>{{ $report->mr->name ?? 'N/A' }}</td>
                                                                    <td>
                                                                        <span class="status-badge 
                                                                            {{ $report->status == 'pending' ? 'status-pending' : '' }}
                                                                            {{ $report->status == 'rejected' ? 'status-suspend' : '' }}
                                                                            {{ $report->status == 'approved' ? 'status-approved' : '' }}">
                                                                            {{ ucfirst($report->status) }}
                                                                        </span>
                                                                    </td>
                                                                    <td>
                                                                        @if ($report->status == 'pending')
                                                                            <form
                                                                                action="{{ route('manager.reports.review', $report->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button name="action" value="approve"
                                                                                    class="btn btn-success btn-sm">Approve</button>
                                                                                <button name="action" value="reject"
                                                                                    class="btn btn-danger btn-sm">Reject</button>
                                                                            </form>
                                                                        @elseif ($report->status == 'approved')
                                                                            <form
                                                                                action="{{ route('manager.reports.review', $report->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button name="action" value="reject"
                                                                                    class="btn btn-danger btn-sm">Reject</button>
                                                                            </form>
                                                                        @elseif ($report->status == 'rejected')
                                                                            <form
                                                                                action="{{ route('manager.reports.review', $report->id) }}"
                                                                                method="POST">
                                                                                @csrf
                                                                                <button name="action" value="approve"
                                                                                    class="btn btn-success btn-sm">Approve</button>
                                                                            </form>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-button-action">
                                                                            <a href="{{ route('manager.reports.edit.daily', $report->id) }}"
                                                                                class="icon-button edit-btn custom-tooltip"
                                                                                data-tooltip="Edit">
                                                                                <i class="fa fa-edit"></i>
                                                                            </a>
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
                                                    {{ $reports->links('pagination::bootstrap-5') }}
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
