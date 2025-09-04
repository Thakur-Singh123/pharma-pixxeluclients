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
                                    <h4 class="card-title">All Tasks</h4>
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
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Start Date
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">End date
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" style="width: 187.688px;">Status
                                                                </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1 @endphp
                                                            <!--Get tasks-->
                                                            @forelse ($all_tasks as $task)
                                                                <tr role="row">
                                                                    <td class="sorting_1">{{ $count++ }}.</td>
                                                                    <td>{{ $task->title }}</td>
                                                                    <td>{{ $task->description }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($task->start_date)->format('d M, Y') }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($task->end_date)->format('d M, Y') }}</td>
                                                                    <td style="color: {{ 
                                                                        $task->status == 'pending' ? 'orange' : 
                                                                        ($task->status == 'in_progress' ? 'blue' : 
                                                                        ($task->status == 'completed' ? 'green' : 'black')) 
                                                                    }}">
                                                                        {{ 
                                                                            $task->status == 'in_progress' ? 'In Progress' : ucfirst($task->status) 
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
                                                    {{ $all_tasks->links('pagination::bootstrap-5') }}
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
