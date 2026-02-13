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
                                <h4 class="card-title">All Visit Plans</h4>
                                <form method="GET" action="{{ route('manager.visit-plans.index') }}" class="m-0 d-flex align-items-center" style="gap: 10px;">
                                    <input type="date"
                                        name="start_date"
                                        class="form-control"
                                        value="{{ request('start_date') }}"
                                        onchange="this.form.submit()"
                                    >
                                    <select name="status" class="form-control" onchange="if(this.value==''){ window.location='{{ route('manager.visit-plans.index') }}'; } else { this.form.submit(); }">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="">All Status</option>
                                        <option value="open" {{ request('open') == 'open' ? 'selected' : '' }}>
                                            Open</option>
                                        <option value="interested"
                                            {{ request('status') == 'interested' ? 'selected' : '' }}>
                                            Interested</option>
                                        <option value="assigned"
                                            {{ request('status') == 'assigned' ? 'selected' : '' }}>
                                            Assigned</option>
                                        <option value="completed"
                                            {{ request('completed') == 'assigned' ? 'selected' : '' }}>
                                            Completed</option>
                                    </select>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                <div id="basic-datatables_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <table id="basic-datatables" class="display table table-striped table-hover dataTable" role="grid" aria-describedby="basic-datatables_info">
                                                <thead>
                                                    <tr role="row">
                                                        <th>Sr No.</th>
                                                        <th>Title</th>
                                                        <th>Plan Type</th>
                                                        <th>Category</th>
                                                        <th>Description</th>
                                                        <th>Start Date</th>
                                                        <th>End Date</th>
                                                        <th>Location</th>
                                                        <th>Status</th>
                                                        <th>Comment</th>
                                                        <th>Add Comment</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $count = 1 @endphp
                                                    <!--Get visit plans-->
                                                    @forelse ($visit_plans as $visit_plan)
                                                    <tr role="row">
                                                        <td>{{ $count++ }}.</td>
                                                        <td>{{ $visit_plan->title }}</td>
                                                        <td>{{ $visit_plan->plan_type }}</td>
                                                        <td>{{ $visit_plan->visit_category }}</td>
                                                        <td>{{ $visit_plan->description }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($visit_plan->start_date)->format('d M, Y') }}</td>
                                                        <td>{{ \Carbon\Carbon::parse($visit_plan->end_date)->format('d M, Y') }}</td>
                                                        <td>{{ $visit_plan->location }}</td>
                                                        <td>
                                                            <form action="{{ route('manager.visit.update.status', $visit_plan->id) }}" method="POST" class="status-form">
                                                                @csrf
                                                                <select name="status" class="custom-status-dropdown" onchange="this.form.submit()">
                                                                    <option value="assigned" {{ $visit_plan->status == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                                                    <option value="interested" {{ $visit_plan->status == 'interested' ? 'selected' : '' }}>Interested</option>
                                                                    <option value="completed" {{ $visit_plan->status == 'completed' ? 'selected' : '' }}>Completed</option>
                                                                    <option value="open" {{ $visit_plan->status == 'open' ? 'selected' : '' }}>Open</option>
                                                                </select>
                                                            </form>
                                                        </td>
                                                        <td>
                                                            @forelse ($visit_plan->comments as $comment)
                                                            <span>{{ $comment->comment }}</span>
                                                            @empty
                                                                No comment
                                                            @endforelse
                                                        </td>
                                                        <td>
                                                            <button class="btn btn-success btn-sm" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#addDoctorModal" 
                                                                data-visit-id="{{ $visit_plan->id }}"
                                                                data-visit-title="{{ $visit_plan->title }}">
                                                                Add comment
                                                            </button>
                                                        </td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <a href="{{ url('manager/edit-visit-plan', $visit_plan->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <a href="{{ url('manager/delete-visit-plan', $visit_plan->id) }}" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="10" class="text-center">No record found</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                            {{ $visit_plans->appends(request()->query())->links('pagination::bootstrap-5') }}
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
<!--Single Modal at Bottom-->
<div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('manager.visit-plans.add-comment') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="visit_id" id="modalVisitId" value="">
                <div class="modal-header">
                    <h5 class="modal-title" id="addDoctorModalLabel">Add Comment for Visit <span id="visitTitleDisplay"></span></h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <label>Comment</label>
                            <textarea name="comment" id="comment" class="form-control" placeholder="Enter comment" required></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
       var addDoctorModal = document.getElementById('addDoctorModal');
        if (addDoctorModal) {
            addDoctorModal.addEventListener('show.bs.modal', function (event) {
                var button = event.relatedTarget;
                if (button) {
                    var visitId = button.getAttribute('data-visit-id');
                    var visitTitle = button.getAttribute('data-visit-title');
                    document.getElementById('modalVisitId').value = visitId || '';
                    document.getElementById('comment').value = '';
                    document.getElementById('visitTitleDisplay').textContent = visitTitle || '';
                }
            });
        }
    });
</script>
@endsection