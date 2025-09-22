@extends('mr.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <h2 class="mb-3">My Calendar</h2>
        <div id="calendar"></div>
    </div>
</div>
<!--Task Modal-->
<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title" id="taskModalLabel">Task Details</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Title:</strong> <span id="taskTitle"></span></p>
                <p><strong>Description:</strong> <span id="taskDescription"></span></p>
                <p><strong>Location:</strong> <span id="taskLocation"></span></p>
                <p><strong>Doctor:</strong> <span id="taskDoctor"></span></p>
                <p><strong>Pin Code:</strong> <span id="taskPin"></span></p>                                
                <p><strong>Start Date:</strong> <span id="taskStart"></span></p>
                <p><strong>End Date:</strong> <span id="taskEnd"></span></p>
                <p><strong>Status:</strong> <span id="taskStatus" class="badge"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<!--Event Modal-->
<div class="modal fade" id="eventModal" tabindex="-1" aria-labelledby="eventModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="eventModalLabel">Event Details</h5>
                <button type="button" class="btn-close btn-close-dark" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><strong>Name:</strong> <span id="eventName"></span></p>
                <p><strong>Start:</strong> <span id="eventStart"></span></p>
                <p><strong>End:</strong> <span id="eventEnd"></span></p>
                <p><strong>Location:</strong> <span id="eventLocation"></span></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>
<style>
    #calendar {
        background: #ffffff;
        border-radius: 12px;
        padding: 10px;
        box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.1);
    }
    .fc-toolbar-title { font-size: 20px; font-weight: 600; color: #2c3e50; }
    .fc-button {
        background: #3498db !important;
        border: none !important;
        border-radius: 6px !important;
        padding: 5px 12px !important;
        font-size: 14px !important;
        color: #fff !important;
        transition: 0.3s;
    }
    .fc-button:hover { background: #2980b9 !important; }
    .fc-daygrid-day-frame {
        min-height: 70px !important;
        padding: 4px !important;
        border-radius: 8px;
        transition: 0.3s;
    }
    .fc-daygrid-day-frame:hover {
        background: #f1f8ff;
        transform: scale(1.02);
        box-shadow: inset 0px 0px 8px rgba(0, 0, 0, 0.05);
    }
    .fc-daygrid-day-number { font-size: 13px; font-weight: 600; color: #34495e; }
    .fc-event { font-size: 12px; border-radius: 6px; padding: 2px 6px; font-weight: 500; border: none !important; }
</style>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    const taskModalEl = document.getElementById('taskModal');
    const taskModalInstance = new bootstrap.Modal(taskModalEl);
    const eventModalEl = document.getElementById('eventModal');
    const eventModalInstance = new bootstrap.Modal(eventModalEl);
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'timeGridWeek',
        contentHeight: 550,
        expandRows: true,
        eventSources: [
            {
                url: "{{ route('mr.calendar.tasks') }}",
                color: '#3788d8',
                textColor: '#fff'
            },
            {
                url: "{{ route('mr.calendar.events') }}",
                color: '#e67e22',
                textColor: '#fff'
            }
        ],
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,timeGridDay'
        },
        eventClick: function(info) {
            if(info.event.extendedProps.type === 'task') {
                document.getElementById('taskTitle').innerText = info.event.title;
                document.getElementById('taskDescription').innerText = info.event.extendedProps.description ?? "N/A";
                document.getElementById('taskLocation').innerText = info.event.extendedProps.location ?? "N/A";
                document.getElementById('taskDoctor').innerText = info.event.extendedProps.doctor ?? "N/A";
                document.getElementById('taskPin').innerText = info.event.extendedProps.pin ?? "N/A";
                document.getElementById('taskStart').innerText = info.event.start.toLocaleString();
                document.getElementById('taskEnd').innerText = info.event.end ? info.event.end.toLocaleString() : "N/A";
                document.getElementById('taskStatus').innerText = info.event.extendedProps.status ?? "N/A";
                let badge = document.getElementById('taskStatus');
                badge.className = "badge";
                if(info.event.extendedProps.status === 'pending'){
                    badge.classList.add("bg-warning");
                } else if(info.event.extendedProps.status === 'in_progress'){
                    badge.classList.add("bg-info");
                } else if(info.event.extendedProps.status === 'completed'){
                    badge.classList.add("bg-success");
                } else {
                    badge.classList.add("bg-secondary");
                }
                taskModalInstance.show();
            } else if(info.event.extendedProps.type === 'event') {
                document.getElementById('eventName').innerText = info.event.title;
                document.getElementById('eventStart').innerText = info.event.start.toLocaleString();
                document.getElementById('eventEnd').innerText = info.event.end ? info.event.end.toLocaleString() : "N/A";
                document.getElementById('eventLocation').innerText = info.event.extendedProps.location ?? "N/A";
                eventModalInstance.show();
            }
        }
    });
    calendar.render();
});
</script>
@endsection
