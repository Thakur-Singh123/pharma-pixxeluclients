@extends('manager.layouts.master')
@section('content')
<style>
    /* =========================
   PAGE + CARD
========================= */
.page-inner {
    background: #f5f6fa;
}

.calendar-card {
    background: #ffffff;
    border-radius: 10px;
    padding: 16px;
    box-shadow: 0 4px 14px rgba(0,0,0,0.06);
}

/* =========================
   TITLE (same as MR)
========================= */
.calendar-title {
    margin: 0 0 14px 0;
    padding-bottom: 10px;
    color: #2a2f5b;
    font-size: 18px;
    font-weight: 600;
    line-height: 1.6;
    border-bottom: 1px solid #e5e7eb;
}

/* =========================
   FULLCALENDAR HEADER
========================= */
.fc-header-toolbar {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 14px;
}

.fc-toolbar-title {
    font-size: 18px;
    font-weight: 600;
    color: #111827;
}

/* =========================
   BUTTONS (Month + Arrows)
========================= */
.fc-button {
    background: #6c757d !important;
    color: #ffffff !important;
    border: none !important;
    border-radius: 6px !important;
    padding: 6px 12px !important;
    font-size: 13px !important;
    font-weight: 500;
}

.fc-button:hover {
    background: #5a6268 !important;
}

.fc-button:focus,
.fc-button:active {
    box-shadow: none !important;
    outline: none !important;
}

.fc-toolbar-chunk {
    display: flex;
    gap: 6px;
}

/* =========================
   DAY HEADER
========================= */
.fc-col-header-cell-cushion {
    font-size: 13px;
    font-weight: 600;
    color: #2563eb;
}

/* =========================
   DAY CELLS
========================= */
.fc-daygrid-day-frame {
    min-height: 80px;
    padding: 6px;
    border-radius: 6px;
}

.fc-daygrid-day-number {
    font-size: 13px;
    font-weight: 600;
    color: #374151;
}

/* =========================
   EVENTS
========================= */
.fc-event {
    font-size: 12px;
    font-weight: 500;
    border-radius: 6px;
    padding: 3px 6px;
    border: none !important;
}

/* =========================
   REMOVE RIGHT SCROLLER
========================= */
.fc-scroller,
.fc-scroller-liquid-absolute {
    overflow: visible !important;
}

.fc-daygrid-body {
    overflow: visible !important;
}

#calendar {
    overflow: hidden !important;
}

/* =========================
   MODALS (same as MR)
========================= */
.modal-content {
    border-radius: 12px;
    border: none;
}

.modal-header {
    background: #0d6efd;
    color: #ffffff;
    border-radius: 12px 12px 0 0;
}

.modal-title {
    font-size: 16px;
    font-weight: 600;
}

.modal-body p {
    font-size: 14px;
    margin-bottom: 8px;
    color: #374151;
}

.modal-body strong {
    color: #111827;
}

.badge {
    padding: 4px 10px;
    font-size: 12px;
    border-radius: 12px;
    text-transform: capitalize;
}
</style>
<div class="container">
    <div class="page-inner">
        <div class="calendar-card">
            <h4 class="calendar-title">My Calendar</h4>
            <div id="calendar"></div>
        </div>
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
                <p id="taskAssignedContainer"></p>
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

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        const taskModalEl = document.getElementById('taskModal');
        const taskModalInstance = new bootstrap.Modal(taskModalEl);
        const eventModalEl = document.getElementById('eventModal');
        const eventModalInstance = new bootstrap.Modal(eventModalEl);
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',     
            eventDisplay: 'block',             
            height: 'auto',                     
        fixedWeekCount: false,    
            initialDate: new Date(new Date().setMonth(new Date().getMonth() + 1)),
            contentHeight: 550,
            expandRows: true,
            eventSources: [
                {
                    url: "{{ route('manager.calendar.tasks') }}", 
                    color: '#3788d8',
                    textColor: '#fff'
                },
                {
                    url: "{{ route('manager.calendar.events') }}", 
                    color: '#e67e22',
                    textColor: '#fff'
                }
            ],
            headerToolbar: {
                left: 'title',
                center: '',
                right: 'dayGridMonth prev,next'
            },
            buttonText: {
                dayGridMonth: 'Month'
            },

            eventClick: function(info) { 
                if(info.event.extendedProps.type === 'task' || info.event.extendedProps.type === 'monthly_task') {
                    document.getElementById('taskTitle').innerText = info.event.title;
                    document.getElementById('taskDescription').innerText = info.event.extendedProps.description ?? "N/A";
                    document.getElementById('taskLocation').innerText = info.event.extendedProps.location ?? "N/A";
                    document.getElementById('taskDoctor').innerText = info.event.extendedProps.doctor ?? "N/A";
                    document.getElementById('taskPin').innerText = info.event.extendedProps.pin ?? "N/A";
                    document.getElementById('taskStart').innerText = info.event.start.toLocaleString();
                    document.getElementById('taskEnd').innerText = info.event.end ? info.event.end.toLocaleString() : "N/A";
                    //Status
                    let badge = document.getElementById('taskStatus');
                    document.getElementById('taskStatus').innerText = info.event.extendedProps.status ?? "N/A";
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
                    let mrLabel = "";
                    let mrValue = "";
                    if(info.event.extendedProps.type === "monthly_task") {
                        mrLabel = "MR Name:";
                        mrValue = info.event.extendedProps.mr_name ?? "N/A";
                    } else if(info.event.extendedProps.type === "task") {
                        mrLabel = "Assigned MR:";
                        mrValue = info.event.extendedProps.assigned_mr ?? "N/A";
                    }
                    document.getElementById("taskAssignedContainer").innerHTML = `<strong>${mrLabel}</strong> <span>${mrValue}</span>`;

                    let titleEl = info.el.querySelector(".fc-event-title");
                    if(titleEl) {
                        titleEl.innerText = info.event.title;
                    }

                    taskModalInstance.show();
                } 
                else if(info.event.extendedProps.type === 'event') {
                    //Event modal
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