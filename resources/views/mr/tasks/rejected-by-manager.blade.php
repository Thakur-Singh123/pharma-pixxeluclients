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
                @if (session('error'))
                <div class="alert alert-danger">
                    {{ session('error') }}
                </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Tasks Calendar Rejected by Manager
                            <form action="{{ route('mr.tasks.sendMonthly') }}" method="POST">
                                @csrf
                                <button type="submit" class="send-approval-btn float-end">
                                    Send to Manager Approval
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <!--Calendar-->
                        <div id="calendar"></div>
                        <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form id="taskForm" method="POST" action="">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="_method" id="formMethod" value="PUT">
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
                                                    <input type="text" class="form-control" id="title" name="title" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Description</label>
                                                    <textarea class="form-control" id="description" name="description" required></textarea>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Assign Doctor</label>
                                                    <select name="doctor_id" id="doctor_id" class="form-control">
                                                        <option value="" disabled>Select</option>
                                                        @foreach($all_doctors as $doctor)
                                                            <option value="{{ $doctor->id }}">
                                                                {{ $doctor->doctor_name }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="pin_code">Area Pin Code</label>
                                                    <input type="number" class="form-control" id="pin_code" name="pin_code" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="start_date">Start Date</label>
                                                    <input type="date" class="form-control" id="start_date" name="start_date" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="end_date">End Date</label>
                                                    <input type="date" class="form-control" id="end_date" name="end_date" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="location">Location</label>
                                                    <input type="text" class="form-control" id="location" name="location" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Status</label>
                                                    <select name="status" id="status" class="form-control">
                                                        <option value="pending">Pending</option>
                                                        <option value="in_progress">In Progress</option>
                                                        <option value="completed">Completed</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" id="saveTaskBtn" class="btn btn-success">Update</button>
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                        <!--End Task-->
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');
    var today = new Date();
    var nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, 1);
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: nextMonth,
        selectable: false,
        events: @json($events),
        dayCellDidMount: function(info) {
            var todayZero = new Date();
            todayZero.setHours(0,0,0,0);
            var cellDate = new Date(info.date);
            //Disable past dates
            if (cellDate < todayZero) {
                info.el.style.backgroundColor = "#f0f0f0";
                info.el.style.color = "#999";
            }
            info.el.style.cursor = "pointer";
        },
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

//Edit Task Modal
function openEditTaskModal(task) {
    document.getElementById('task_id').value = task.id;
    document.getElementById('title').value = task.title;
    document.getElementById('description').value = task.description;
    document.getElementById('start_date').value = task.start_date;
    document.getElementById('end_date').value = task.end_date;
    document.getElementById('location').value = task.location;
    document.getElementById('status').value = task.status;
    document.getElementById('doctor_id').value = task.doctor_id;
    document.getElementById('pin_code').value = task.pin_code;
    let updateUrl = "{{ route('mr.tasks.update', ':id') }}".replace(':id', task.id);
    document.getElementById('taskForm').action = updateUrl;
    document.getElementById('formMethod').value = 'PUT';
    document.getElementById('taskModalTitle').innerText = "Update Task";
    document.getElementById('saveTaskBtn').innerText = "Update";
    setDateValidation(task.start_date, true);
    var myModal = new bootstrap.Modal(document.getElementById('taskModal'));
    myModal.show();
}
//Date Validation
function setDateValidation(existingStart = '', isEdit = false) {
    var startInput = document.getElementById('start_date');
    var endInput = document.getElementById('end_date');
    var today = new Date().toISOString().split('T')[0];
    startInput.setAttribute('min', isEdit ? existingStart : today);
    endInput.setAttribute('min', startInput.value);

    startInput.onchange = function() {
        endInput.setAttribute('min', this.value);
    };

    var form = document.getElementById('taskForm');
    form.onsubmit = function(e) {
        if (endInput.value < startInput.value) {
            e.preventDefault();
            alert('Error: End Date cannot be earlier than Start Date!');
        }
    };
}
</script>
@endsection
