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
                        <div class="card-title">Tasks Calendar Approved by Manager</div>
                    </div>
                    <!--Calendar-->
                    <div id="calendar"></div>
                    <!--Task Detail Modal-->
                    <div class="modal fade" id="taskModal" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Task Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <p><strong>Title:</strong> <span id="taskTitle"></span></p>
                                    <p><strong>Description:</strong> <span id="taskDescription"></span></p>
                                    <p><strong>Location:</strong> <span id="taskLocation"></span></p>
                                    <p><strong>Start:</strong> <span id="taskStart"></span></p>
                                    <p><strong>End:</strong> <span id="taskEnd"></span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events),
            eventDisplay: 'block',
            eventClick: function(info) {
                let task = info.event.extendedProps;
                document.getElementById('taskTitle').innerText = info.event.title;
                document.getElementById('taskDescription').innerText = task.description ?? 'N/A';
                document.getElementById('taskLocation').innerText = task.location ?? 'N/A';
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