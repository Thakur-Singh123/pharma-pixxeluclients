@extends('mr.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                {{-- Alerts --}}
                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif
                @if (session('error'))
                    <div class="alert alert-danger">{{ session('error') }}</div>
                @endif

                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Tasks Calendar Approved by Manager</h4>
                    </div>
                    <div class="card-body">
                        {{-- Calendar --}}
                        <div id="calendar"></div>
                    </div>
                </div>

                {{-- Task Detail Modal --}}
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
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                {{-- End Modal --}}
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    // Show next month by default
    var today = new Date();
    var nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, 1);

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: nextMonth,
        events: @json($events),
        eventDisplay: 'block',
        eventClick: function(info) {
            let task = info.event.extendedProps;
            document.getElementById('taskTitle').innerText = info.event.title;
            document.getElementById('taskDescription').innerText = task.description ?? 'N/A';
            document.getElementById('taskDoctor').innerText = task.doctor ?? 'N/A';
            document.getElementById('taskLocation').innerText = task.location ?? 'N/A';
            document.getElementById('taskPinCode').innerText = task.pin ?? 'N/A';
            const options = { day: '2-digit', month: 'short', year: 'numeric' };
            document.getElementById('taskStart').innerText = info.event.start.toLocaleDateString('en-US', options);
            document.getElementById('taskEnd').innerText = info.event.end ? info.event.end.toLocaleDateString('en-US', options) : 'N/A';

            var modal = new bootstrap.Modal(document.getElementById('taskModal'));
            modal.show();
        }
    });

    calendar.render();
});
</script>
@endsection
