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
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif

                <!--Approve/Reject button-->
                <div class="card">
                    <div class="card-flexd">
                        <div class="d-flex justify-content-end mb-3">
                            <h4 class="tasks-heading">Calander For Approval</h4>
                            <!--Approved all-->
                            <form action="{{ route('manager.tasks.approveAll') }}" method="POST" class="me-2">
                                @csrf
                                <button type="submit" class="btn btn-success-appoval">
                                    Approve All
                                </button>
                            </form>
                            <!--Rejected all-->
                            <form action="{{ route('manager.tasks.rejectAll') }}" method="POST">
                                @csrf
                                <button type="submit" class="btn btn-danger">
                                    Reject All
                                </button>
                            </form>
                        </div>

                        <div class="card-body">
                            <div id="calendar"></div>

                            <!-- Task Modal (Only Edit for Manager) -->
                          <!-- Task Modal (Manager can Edit All Fields) -->
<div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <form id="taskForm" method="POST" action="">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="taskModalTitle">Update Task</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="task_id" name="task_id">

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label>Title</label>
                            <input type="text" class="form-control" id="title" name="title">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>Description</label>
                            <textarea class="form-control" id="description" name="description"></textarea>
                        </div>

                        <!-- Doctor -->
                        <div class="col-md-6 mb-3">
                            <label>Assign Doctor</label>
                            <select name="doctor_id" id="doctor_id" class="form-control">
                                <option value="" disabled>Select</option>
                                @foreach($doctors as $doctor)
                                    <option value="{{ $doctor->id }}">
                                        {{ $doctor->doctor_name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Area Pin Code</label>
                            <input type="text" class="form-control" id="pin_code" name="pin_code">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Start Date</label>
                            <input type="date" class="form-control" id="start_date" name="start_date">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label>End Date</label>
                            <input type="date" class="form-control" id="end_date" name="end_date">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Location</label>
                            <input type="text" class="form-control" id="location" name="location">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label>Status</label>
                            <select name="status" id="status" class="form-control">
                                <option value="pending">Pending</option>
                                <option value="in_progress">In Progress</option>
                                <option value="completed">Completed</option>
                                <option value="approved">Approved</option>
                                <option value="rejected">Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-success">Update</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        events: @json($events),

        eventClick: function(info) {
            var event = info.event;

            var task = {
                id: event.id,
                title: event.title || '',
                description: event.extendedProps.description || '',
                start_date: event.startStr,
                end_date: event.endStr || event.startStr,
                location: event.extendedProps.location || '',
                status: event.extendedProps.status || 'pending',
                doctor_id: event.extendedProps.doctor_id || '',
                pin_code: event.extendedProps.pin_code || ''
            };

            openEditTaskModal(task);
        }
    });

    calendar.render();
});

// Format date to YYYY-MM-DD
function formatDate(dateString) {
    if (!dateString) return '';
    let date = new Date(dateString);
    let year = date.getFullYear();
    let month = ('0' + (date.getMonth() + 1)).slice(-2);
    let day = ('0' + date.getDate()).slice(-2);
    return `${year}-${month}-${day}`;
}

// Open Edit Task Modal
function openEditTaskModal(task) {
    document.getElementById('task_id').value = task.id;
    document.getElementById('title').value = task.title;
    document.getElementById('description').value = task.description;
    document.getElementById('start_date').value = formatDate(task.start_date);
    document.getElementById('end_date').value = formatDate(task.end_date);
    document.getElementById('location').value = task.location;
    document.getElementById('status').value = task.status;
    document.getElementById('doctor_id').value = task.doctor_id;
    document.getElementById('pin_code').value = task.pin_code;

    let updateUrl = "{{ route('manager.tasks.update', ':id') }}";
    updateUrl = updateUrl.replace(':id', task.id);
    document.getElementById('taskForm').action = updateUrl;

    var myModal = new bootstrap.Modal(document.getElementById('taskModal'));
    myModal.show();
}
</script>
@endsection
