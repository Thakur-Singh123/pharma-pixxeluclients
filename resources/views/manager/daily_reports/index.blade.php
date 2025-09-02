@extends('manager.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    {{-- Success Message --}}
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h4 class="card-title">All Reports</h4>
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
                                                                    style="width: 242.688px;">S No.
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
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Total Visits
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Patients
                                                                    Referred
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Notes
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Status
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
                                                                    <td>{{ $report->report_date }}</td>
                                                                    <td>{{ $report->mr->name ?? 'N/A' }}</td>
                                                                    <td>{{ $report->total_visits }}</td>
                                                                    <td>{{ $report->patients_referred }}</td>
                                                                    <td>{{ $report->notes }}</td>
                                                                    <td>{{ $report->status }}</td>
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
