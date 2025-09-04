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
                                    <h4 class="card-title">Interested Plans</h4>
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
                                                                    style="width: 242.688px;">Sr No.</th>
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
                                                                    colspan="1" style="width: 156.312px;">Status</th>
                                                               
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1 @endphp
                                                            <!--Get interests-->
                                                            @forelse ($interests as $interest)
                                                            @php $visit_plan = $interest->visitPlan; @endphp
                                                                <tr role="row">
                                                                    <td class="sorting_1">{{ $count++ }}</td>
                                                                    <td>{{ $visit_plan->title }}</td>
                                                                    <td>{{ $visit_plan->plan_type }}</td>
                                                                    <td>{{ $visit_plan->visit_category }}</td>
                                                                    <td>{{ $visit_plan->description }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($visit_plan->start_date)->format('d M, Y') }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($visit_plan->end_date)->format('d M, Y') }}</td>
                                                                    <td>{{ $visit_plan->location }}</td>
                                                                    <td style="color: {{ 
                                                                            $visit_plan->status == 'assigned' ? 'orange' : 
                                                                            ($visit_plan->status == 'interested' ? 'blue' : 
                                                                            ($visit_plan->status == 'completed' ? 'green' : 
                                                                            ($visit_plan->status == 'open' ? 'red' : 'black'))) 
                                                                        }}">
                                                                            {{ 
                                                                                ucfirst($visit_plan->status) 
                                                                            }}
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
                                                    {{ $interests->links('pagination::bootstrap-5') }}
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
