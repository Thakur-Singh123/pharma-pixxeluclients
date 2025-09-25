@extends('manager.layouts.master')
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
                    <div class="card-flexd">
                        <div class="d-flex justify-content-end mb-3">
                            <h4 class="tasks-heading">Calendar For Approval</h4>
                            <form action="{{ route('manager.tasks.approveAll') }}" method="POST" class="me-2">
                                @csrf
                                <input type="hidden" name="current_month" id="current_month">
                                <button type="submit" class="btn btn-success-appoval">Approve All</button>
                            </form>
                            <form action="{{ route('manager.tasks.rejectAll') }}" method="POST">
                                @csrf
                                <input type="hidden" name="current_month" id="reject_month">
                                <button type="submit" class="btn btn-danger rejected-all">Reject All</button>
                            </form>
                        </div>
                        <div class="card-body">
                            <!--Calendar-->
                            <div id="calendar"></div>
                            <!--Task Modal-->
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
                                                        <label>Doctor Name</label>
                                                        <input type="text" class="form-control" id="doctor_name_display" readonly>
                                                        <input type="hidden" id="doctor_id" name="doctor_id">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>MR Name</label>
                                                        <input type="text" class="form-control" id="mr_name_display" readonly>
                                                        <input type="hidden" id="mr_id" name="mr_id">
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
                                                <button type="submit" class="btn btn-success">Update</button>
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            <!--End Task Modal-->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    let calendar;
    let approvedMonths = [];  // Track approved months
    let rejectedMonths = [];  // Track rejected months

    document.addEventListener('DOMContentLoaded', () => {
        const calendarEl = document.getElementById('calendar');

        calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            events: @json($events),
            eventClick: ({ event }) => openEditTaskModal({
                id: event.id,
                title: event.title || '',
                description: event.extendedProps.description || '',
                start_date: event.startStr,
                end_date: event.endStr || event.startStr,
                location: event.extendedProps.location || '',
                status: event.extendedProps.status || 'pending',
                doctor_id: event.extendedProps.doctor_id || '',
                doctor_name: event.extendedProps.doctor_name || '',
                mr_id: event.extendedProps.mr_id || '',
                mr_name: event.extendedProps.mr_name || '',
                pin_code: event.extendedProps.pin_code || ''
            }),
        });

        calendar.render();

        function updateCurrentMonthInput() {
            const currentDate = calendar.getDate();
            const year = currentDate.getFullYear();
            const month = currentDate.getMonth() + 1;

            const currentMonthValue = `${year}-${('0' + month).slice(-2)}`;
            const nextMonthValue = `${year}-${('0' + (month + 1)).slice(-2)}`;

            document.getElementById('current_month').value = currentMonthValue;
            document.getElementById('reject_month').value = currentMonthValue;

            // Buttons access
            const approveBtn = document.querySelector('.btn-success-appoval');
            const rejectBtn = document.querySelector('.btn-danger');

            // Check if already approved/rejected
            if (approvedMonths.includes(currentMonthValue)) {
                approveBtn.disabled = true;
            } else {
                approveBtn.disabled = false;
            }

            if (rejectedMonths.includes(currentMonthValue)) {
                rejectBtn.disabled = true;
            } else {
                rejectBtn.disabled = false;
            }

            // Sirf current & next month me hi enable buttons
            const today = new Date();
            const thisMonth = today.getMonth() + 1;
            const nextMonth = thisMonth + 1;

            if (month === thisMonth || month === nextMonth) {
                approveBtn.style.display = "inline-block";
                rejectBtn.style.display = "inline-block";
            } else {
                approveBtn.style.display = "none";
                rejectBtn.style.display = "none";
            }
        }

        updateCurrentMonthInput();

        calendar.on('datesSet', () => {
            updateCurrentMonthInput();
        });

        // On Approve Submit
        const approveForm = document.querySelector("form[action*='approveAll']");
        approveForm.addEventListener("submit", function (e) {
            const selectedMonth = document.getElementById("current_month").value;
            if (approvedMonths.includes(selectedMonth)) {
                e.preventDefault();
                alert("This month is already approved!");
            } else {
                approvedMonths.push(selectedMonth);
            }
        });

        // On Reject Submit
        const rejectForm = document.querySelector("form[action*='rejectAll']");
        rejectForm.addEventListener("submit", function (e) {
            const selectedMonth = document.getElementById("reject_month").value;
            if (rejectedMonths.includes(selectedMonth)) {
                e.preventDefault();
                alert("This month is already rejected!");
            } else {
                rejectedMonths.push(selectedMonth);
            }
        });
    });

    function formatDate(dateString) {
        if (!dateString) return '';
        const d = new Date(dateString);
        return `${d.getFullYear()}-${('0'+(d.getMonth()+1)).slice(-2)}-${('0'+d.getDate()).slice(-2)}`;
    }

    function openEditTaskModal(task) {
        const fields = ['task_id', 'title', 'description', 'location', 'status', 'pin_code'];
        fields.forEach(f => document.getElementById(f).value = task[f] || '');
        document.getElementById('doctor_name_display').value = task.doctor_name;
        document.getElementById('doctor_id').value = task.doctor_id;
        document.getElementById('mr_name_display').value = task.mr_name;
        document.getElementById('mr_id').value = task.mr_id;

        const startInput = document.getElementById('start_date');
        const endInput = document.getElementById('end_date');
        startInput.value = formatDate(task.start_date);
        endInput.value = formatDate(task.end_date);

        const minStart = task.start_date || new Date().toISOString().split('T')[0];
        startInput.setAttribute('min', minStart);
        endInput.setAttribute('min', startInput.value);
        startInput.addEventListener('change', () => endInput.setAttribute('min', startInput.value));

        const form = document.getElementById('taskForm');
        form.onsubmit = (e) => {
            if (endInput.value < startInput.value) {
                e.preventDefault();
                alert('Error: End Date cannot be earlier than Start Date!');
            }
        };

        new bootstrap.Modal(document.getElementById('taskModal')).show();
        form.action = "{{ route('manager.tasks.update', ':id') }}".replace(':id', task.id);
    }
</script>

@endsection