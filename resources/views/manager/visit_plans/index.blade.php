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
                                    <h4 class="card-title">All MRs</h4>
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
                                                                <th>S No.</th>
                                                                <th>Title</th>
                                                                <th>Plan Type</th>
                                                                <th>Category</th>
                                                                <th>Description</th>
                                                                <th>Start Date</th>
                                                                <th>End Date</th>
                                                                <th>Location</th>
                                                                <th>Status</th>
                                                                <th>Add Comment</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1 @endphp
                                                            @forelse ($visit_plans as $visit_plan)
                                                                <tr role="row">
                                                                    <td>{{ $count++ }}</td>
                                                                    <td>{{ $visit_plan->title }}</td>
                                                                    <td>{{ $visit_plan->plan_type }}</td>
                                                                    <td>{{ $visit_plan->visit_category }}</td>
                                                                    <td>{{ $visit_plan->description }}</td>
                                                                    <td>{{ $visit_plan->start_date }}</td>
                                                                    <td>{{ $visit_plan->end_date }}</td>
                                                                    <td>{{ $visit_plan->location }}</td>
                                                                    <td>{{ $visit_plan->status }}</td>
                                                                    <td>
                                                                        <button class="btn btn-success btn-sm" 
                                                                                data-bs-toggle="modal" 
                                                                                data-bs-target="#addDoctorModal" 
                                                                                data-visit-id="{{ $visit_plan->id }}"
                                                                                data-visit-title="{{ $visit_plan->title }}">
                                                                            Add comment
                                                                        </button>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="10" class="text-center">No record found</td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    {{ $visit_plans->links('pagination::bootstrap-5') }}
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

    <!-- Single Modal at Bottom -->
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
                                <textarea name="comment" id="comment" class="form-control" required></textarea>
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
