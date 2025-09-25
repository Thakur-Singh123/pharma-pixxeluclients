@extends('mr.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">
                            Tasks Calendar
                            <form action="{{ route('mr.tasks.sendMonthly') }}" method="POST">
                                @csrf
                                <button type="submit" class="send-approval-btn float-end">
                                    Send to Manager Approval
                                </button>
                            </form>
                        </div>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                        <!--Task Modal-->
                        <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog modal-lg">
                                <form id="taskForm" method="POST" action="">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="_method" id="formMethod" value="POST">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="taskModalTitle">Add Task</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                        </div>
                                        <div class="modal-body">
                                            <input type="hidden" id="task_id" name="task_id">
                                            <div class="row">
                                                <div class="col-md-6 mb-3">
                                                    <label>Title</label>
                                                    <input type="text" class="form-control" id="title" name="title" placeholder="Enter task title" required>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Description</label>
                                                    <textarea class="form-control" id="description" name="description" placeholder="Enter description" required></textarea>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label>Assign Doctor</label>
                                                    <select name="doctor_id" id="doctor_id" class="form-control">
                                                        <option value="" disabled selected>Select</option>
                                                        @foreach($all_doctors as $doctor)
                                                            <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6 mb-3">
                                                    <label for="pin_code">Area Pin Code</label>
                                                    <input type="number" class="form-control" id="pin_code" name="pin_code" placeholder="Enter area pin code" required>
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
                                                    <input type="text" class="form-control" id="location" name="location" placeholder="Enter location" required>
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
                                            <button type="submit" id="saveTaskBtn" class="btn btn-success">Save</button>
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
    today.setHours(0,0,0,0);
    var nextMonthStart = new Date(today.getFullYear(), today.getMonth() + 1, 1);
    nextMonthStart.setHours(0,0,0,0);
    var nextMonthEnd = new Date(today.getFullYear(), today.getMonth() + 2, 0); 
    nextMonthEnd.setHours(23,59,59,999); 

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: nextMonthStart,
        selectable: true,
        events: @json($events),

        dayCellDidMount: function(info) {
            var cellDate = new Date(info.date);
            cellDate.setHours(12,0,0,0);
            if (cellDate < nextMonthStart || cellDate > nextMonthEnd) {
                info.el.style.backgroundColor = "#f0f0f0";
                info.el.style.color = "#999";
                info.el.style.pointerEvents = "none";
            } else {
                info.el.style.cursor = "pointer";
            }
        },

        dateClick: function(info) {
            var clickedDate = new Date(info.dateStr);
            clickedDate.setHours(12,0,0,0);
            if (clickedDate < nextMonthStart || clickedDate > nextMonthEnd) return;

            openAddTaskModal();
            document.getElementById('start_date').value = info.dateStr;
            document.getElementById('end_date').value = info.dateStr;
            var myModal = new bootstrap.Modal(document.getElementById('taskModal'));
            myModal.show();
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

            var startDate = new Date(task.start_date);
            startDate.setHours(12,0,0,0);

            if (startDate < nextMonthStart || startDate > nextMonthEnd) return;

            openEditTaskModal(task);
        }
    });

    calendar.render();

    function openAddTaskModal() {
        var form = document.getElementById('taskForm');
        form.reset();
        document.getElementById('task_id').value = '';
        document.getElementById('formMethod').value = 'POST';
        form.action = "{{ route('mr.tasks.store') }}";
        document.getElementById('taskModalTitle').innerText = "Add Task";
        document.getElementById('saveTaskBtn').innerText = "Save";
        setDateValidation();
    }

    function openEditTaskModal(task) {
        var form = document.getElementById('taskForm');
        document.getElementById('task_id').value = task.id;
        document.getElementById('title').value = task.title;
        document.getElementById('description').value = task.description;
        document.getElementById('start_date').value = task.start_date;
        document.getElementById('end_date').value = task.end_date;
        document.getElementById('location').value = task.location;
        document.getElementById('status').value = task.status;
        document.getElementById('doctor_id').value = task.doctor_id;
        document.getElementById('pin_code').value = task.pin_code;
        document.getElementById('taskModalTitle').innerText = "Update Task";
        document.getElementById('saveTaskBtn').innerText = "Update";
        form.action = "{{ route('mr.tasks.update', ':id') }}".replace(':id', task.id);
        document.getElementById('formMethod').value = 'PUT';
        setDateValidation(task.start_date, true);
        var myModal = new bootstrap.Modal(document.getElementById('taskModal'));
        myModal.show();
    }

    function setDateValidation(existingStart = '', isEdit = false) {
        var startInput = document.getElementById('start_date');
        var endInput = document.getElementById('end_date');

        var nextMonthStartStr = nextMonthStart.toISOString().split('T')[0];
        var nextMonthEndStr = nextMonthEnd.toISOString().split('T')[0];

        startInput.setAttribute('min', nextMonthStartStr);
        startInput.setAttribute('max', nextMonthEndStr);
        endInput.setAttribute('min', startInput.value);
        endInput.setAttribute('max', nextMonthEndStr);

        startInput.addEventListener('change', function() {
            endInput.setAttribute('min', this.value);
        });

        var form = document.getElementById('taskForm');
        form.addEventListener('submit', function(e) {
            if (endInput.value && startInput.value && endInput.value < startInput.value) {
                e.preventDefault();
                alert('Error: End Date cannot be earlier than Start Date!');
            }
        });
    }
});
</script>
@endsection
