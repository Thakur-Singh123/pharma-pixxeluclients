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
                                    <h4 class="card-title">Interested MRS</h4>
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
                                                                    style="width: 242.688px;">S No.</th>
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">Title</th>
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">Plan Type</th>
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">Category</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Description
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Start Date</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">End Date</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Location</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 366.578px;">Interested MRS</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 156.312px;">Status</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 156.312px;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1 @endphp
                                                            @forelse ($intrested_mrs as $intrested_mr)
                                                                @php $visit_plan = $intrested_mr->visitPlan; @endphp
                                                                <tr role="row">
                                                                    <td class="sorting_1">{{ $count++ }}.</td>
                                                                    <td>{{ $visit_plan->title }}</td>
                                                                    <td>{{ $visit_plan->plan_type }}</td>
                                                                    <td>{{ $visit_plan->visit_category }}</td>
                                                                    <td>{{ $visit_plan->description }}</td>
                                                                    <td>{{ $visit_plan->start_date }}</td>
                                                                    <td>{{ $visit_plan->end_date }}</td>
                                                                    <td>{{ $visit_plan->location }}</td>
                                                                    <td>{{ $intrested_mr->mr->name }}</td>
                                                                    <td>{{ $visit_plan->status }}</td>
                                                                    <td style="display: flex; gap: 5px;">
                                                                        <form
                                                                            action="{{ route('manager.visit-plans.action', $intrested_mr->id) }}"
                                                                            method="POST" style="display:inline;">
                                                                            @csrf
                                                                            <input type="hidden" name="action"
                                                                                value="approve">
                                                                            <button type="submit"
                                                                                class="btn btn-success btn-sm"
                                                                               >
                                                                                Approve
                                                                            </button>
                                                                        </form>
                                                                        <form
                                                                            action="{{ route('manager.visit-plans.action', $intrested_mr->id) }}"
                                                                            method="POST" style="display:inline;">
                                                                            @csrf
                                                                            <input type="hidden" name="action"
                                                                                value="reject">
                                                                            <button type="submit"
                                                                                class="btn btn-danger btn-sm"
                                                                                >
                                                                                Reject
                                                                            </button>
                                                                        </form>
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
                                                    {{ $intrested_mrs->links('pagination::bootstrap-5') }}
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
