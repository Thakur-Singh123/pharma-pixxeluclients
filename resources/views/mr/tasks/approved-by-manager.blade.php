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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Approved Tasks Calendar</h4>
                    </div>
                    <div class="card-body">
                        <div id="calendar"></div>
                    </div>
                </div>

                <!-- Task Modal -->
                <div class="modal fade" id="taskModal" tabindex="-1" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Task Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <p><strong>Title:</strong> <span id="taskTitle"></span></p>
                                <p><strong>Description:</strong> <span id="taskDescription"></span></p>
                                <p><strong>Doctor Name:</strong> <span id="taskDoctor"></span></p>
                                <p><strong>Location:</strong> <span id="taskLocation"></span></p>
                                <p><strong>Area Pin Code:</strong> <span id="taskPinCode"></span></p>
                                <p><strong>Start Date:</strong> <span id="taskStart"></span></p>
                                <p><strong>End Date:</strong> <span id="taskEnd"></span></p>

                                <!-- Status Update Form -->
                                <form method="POST" action="{{ route('mr.task.update.status') }}">
                                    @csrf
                                    <input type="hidden" name="id" id="taskId">

                                    <div class="mb-2">
                                        <label>Status</label>
                                        <select name="status" id="taskStatus" class="form-select">
                                            <option value="pending">Pending</option>
                                            <option value="in_progress">In Progress</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>

                                    <div class="d-flex justify-content-between mt-3">
                                        <button type="submit" class="btn btn-success">Update</button>
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Modal -->

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
        events: @json($events),
        eventDisplay: 'block',

        eventDidMount: function(info) {
            info.el.style.cursor = 'pointer';
        },

       eventContent: function(arg) {
    let title = arg.event.title;
    let status = arg.event.extendedProps.status ?? 'pending';

    let statusColor = '';
    if(status.toLowerCase() === 'completed') statusColor = '#28a745';       
    else if(status.toLowerCase() === 'in_progress') statusColor = '#0d6efd'; 
    else if(status.toLowerCase() === 'pending') statusColor = '#dc3545';     

    let statusHtml = statusColor ? `
        <div style="
            position: absolute; 
            top: 2px; 
            right: 4px; 
            font-size: 0.7rem; 
            color: white; 
            background-color: ${statusColor}; 
            padding: 1px 4px; 
            border-radius: 4px;
        ">
            ${status.replace('_',' ')}
        </div>
    ` : '';

    let html = `
        <div style="position: relative; padding: 2px 4px;">
            <div style="font-weight: bold; font-size: 0.9rem;">${title}</div>
            ${statusHtml}
        </div>
    `;
    return { html: html };
},


        eventClick: function(info) {
            let task = info.event;

            document.getElementById('taskTitle').innerText = task.title;
            document.getElementById('taskDescription').innerText = task.extendedProps.description ?? 'N/A';
            document.getElementById('taskDoctor').innerText = task.extendedProps.doctor ?? 'N/A';
            document.getElementById('taskLocation').innerText = task.extendedProps.location ?? 'N/A';
            document.getElementById('taskPinCode').innerText = task.extendedProps.pin ?? 'N/A';

            document.getElementById('taskStatus').value = task.extendedProps.status ?? 'pending';
            document.getElementById('taskId').value = task.id;

            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            document.getElementById('taskStart').innerText = task.start ? new Date(task.start).toLocaleDateString('en-US', options) : 'N/A';
            document.getElementById('taskEnd').innerText = task.end ? new Date(task.end).toLocaleDateString('en-US', options) : 'N/A';

            var modal = new bootstrap.Modal(document.getElementById('taskModal'));
            modal.show();
        }
    });

    calendar.render();
});
</script>

@endsection
