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
                            <h4 class="tasks-heading d-none">Calendar For Approval</h4>
                            <!--Approved button-->
                            <form action="{{ route('manager.tasks.approveAll') }}" method="POST" class="me-2">
                                @csrf
                                <input type="hidden" name="current_month" id="current_month">
                                <button type="submit" id="approveBtn" class="btn btn-success-appoval d-none">Approve All</button>
                            </form>
                            <!--Reject button-->
                            <form action="{{ route('manager.tasks.rejectAll') }}" method="POST">
                                @csrf
                                <input type="hidden" name="reject_month" id="reject_month">
                                <button type="submit" id="rejectBtn" class="btn btn-danger-reject d-none">Reject All</button>
                            </form>
                        </div>
                        <div class="card-body">
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
                                                        <label>Location</label>
                                                        <input type="text" class="form-control" id="location" name="location">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Area Pin Code</label>
                                                        <input type="number" class="form-control" id="pin_code" name="pin_code">
                                                    </div>
                                                        <div class="col-md-6 mb-3">
                                                        <label for="mr_id">Doctor Name</label>
                                                        <select class="form-control" id="doctor_id" name="doctor_id" required>
                                                            <option value="" disabled selected>Select</option>
                                                            @foreach ($all_doctors as $doctor)
                                                                <option value="{{ $doctor->id }}">
                                                                    {{ $doctor->doctor_name }} ({{ $doctor->specialist }})
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('doctor_id')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <!--Assign to MR-->
                                                    <div class="col-md-6 mb-3">
                                                        <label for="mr_id">Assign to MR</label>
                                                        <select class="form-control" id="mr_id" name="mr_id" required>
                                                            <option value="" disabled selected>Select</option>
                                                            @foreach ($mrs as $mr)
                                                                <option value="{{ $mr->id }}">
                                                                    {{ $mr->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                        @error('mr_id')
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>Start Date</label>
                                                        <input type="date" class="form-control" id="start_date" name="start_date">
                                                    </div>
                                                    <div class="col-md-6 mb-3">
                                                        <label>End Date</label>
                                                        <input type="date" class="form-control" id="end_date" name="end_date">
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
                            <!-- End Task Modal -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
let calendar;
const events = @json($events);
document.addEventListener('DOMContentLoaded', () => {
    const calendarEl = document.getElementById('calendar');
    const today = new Date();
    const nextMonthDate = new Date(today.getFullYear(), today.getMonth() + 1, 1); 
    calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        initialDate: nextMonthDate,
        events: events,
        eventDidMount: info => info.el.style.cursor = 'pointer',
        dayCellDidMount: info => info.el.style.cursor = 'pointer',
        eventClick: ({ event }) => openEditTaskModal({
            id: event.id,
            title: event.title || '',
            description: event.extendedProps.description || '',
            start_date: event.startStr,
            end_date: event.endStr || event.startStr,
            location: event.extendedProps.location || '',
            doctor_id: event.extendedProps.doctor_id || '',
            doctor_name: event.extendedProps.doctor_name || '',
            mr_id: event.extendedProps.mr_id || '',
            mr_name: event.extendedProps.mr_name || '',
            pin_code: event.extendedProps.pin_code || ''
        }),
    });
    calendar.render();
    updateCurrentMonthButtons();
    calendar.on('datesSet', updateCurrentMonthButtons);
});
function updateCurrentMonthButtons() {
    const currentDate = calendar.getDate();
    const year = currentDate.getFullYear();
    const month = currentDate.getMonth();
    const currentMonthValue = `${year}-${('0'+(month+1)).slice(-2)}`;
    document.getElementById('current_month').value = currentMonthValue;
    document.getElementById('reject_month').value = currentMonthValue;
    const approveBtn = document.getElementById('approveBtn');
    const rejectBtn = document.getElementById('rejectBtn');
    const heading = document.querySelector('.tasks-heading');
    const today = new Date();
    const nextMonth = new Date(today.getFullYear(), today.getMonth() + 1, 1);
    const nextMonthStr = `${nextMonth.getFullYear()}-${('0'+(nextMonth.getMonth()+1)).slice(-2)}`;
    if(currentMonthValue === nextMonthStr) {
        const monthEvents = events.filter(ev => ev.start && ev.start.startsWith(currentMonthValue));
        const hasRejected = monthEvents.some(ev => ev.extendedProps.is_approval == 0);
        const allApproved = monthEvents.length > 0 && monthEvents.every(ev => ev.extendedProps.is_approval == 1);
        heading?.classList.remove('d-none');
        if(hasRejected) {
            approveBtn?.classList.remove('d-none'); 
            rejectBtn?.classList.add('d-none');
        } else if(allApproved) {
            approveBtn?.classList.add('d-none');   
            rejectBtn?.classList.remove('d-none');
        } else {
            approveBtn?.classList.add('d-none');
            rejectBtn?.classList.add('d-none');
        }
    } else {
        approveBtn?.classList.add('d-none');
        rejectBtn?.classList.add('d-none');
        heading?.classList.add('d-none');
    }
}
function formatDate(dateString) {
    if (!dateString) return '';
    const d = new Date(dateString);
    return `${d.getFullYear()}-${('0'+(d.getMonth()+1)).slice(-2)}-${('0'+d.getDate()).slice(-2)}`;
}
function openEditTaskModal(task) {
    const fields = ['task_id', 'title', 'description', 'location', 'pin_code'];
    fields.forEach(f => document.getElementById(f).value = task[f] || '');
    document.getElementById('doctor_id').value = task.doctor_id;
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
        if(endInput.value < startInput.value){
            e.preventDefault();
            alert('End Date cannot be earlier than Start Date!');
        }
    };
    new bootstrap.Modal(document.getElementById('taskModal')).show();
    form.action = "{{ route('manager.tasks.update', ':id') }}".replace(':id', task.id);
}
</script>
@endsection
