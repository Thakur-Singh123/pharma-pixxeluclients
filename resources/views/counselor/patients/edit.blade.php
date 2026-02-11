@extends('counselor.layouts.master')

@section('content')
@php
$mainDept = $patient->department;
$otherDeptValue = '';

if (\Illuminate\Support\Str::startsWith($patient->department, 'Others (')) {
    $mainDept = 'Others';
    $otherDeptValue = \Illuminate\Support\Str::between($patient->department, 'Others (', ')');
}
@endphp

<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Patient Booking</div>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('counselor.bookings.update', $patient->id) }}"
                              method="POST"
                              enctype="multipart/form-data"
                              autocomplete="off">
                            @csrf
                            @method('PUT')

                            <div class="row">

                                <!-- Patient Name -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Patient Name</label>
                                        <input type="text" class="form-control"
                                               name="patient_name"
                                               value="{{ old('patient_name', $patient->patient_name) }}">
                                        @error('patient_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Mobile -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Mobile Number</label>
                                        <input type="number" class="form-control"
                                               name="mobile_no"
                                               value="{{ old('mobile_no', $patient->mobile_no) }}">
                                        @error('mobile_no')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Email</label>
                                        <input type="email" class="form-control"
                                               name="email"
                                               value="{{ old('email', $patient->email) }}">
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Department -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select class="form-control" id="department" name="department">
                                            <option value="" disabled>Select Department</option>
                                            <option value="Pediatrics" {{ old('department', $mainDept) == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                                            <option value="OBS & GGYNAE" {{ old('department', $mainDept) == 'OBS & GGYNAE' ? 'selected' : '' }}>OBS & GGYNAE</option>
                                            <option value="Urology" {{ old('department', $mainDept) == 'Urology' ? 'selected' : '' }}>Urology</option>
                                            <option value="General Surgery" {{ old('department', $mainDept) == 'General Surgery' ? 'selected' : '' }}>General Surgery</option>
                                            <option value="Pediatric Surgery" {{ old('department', $mainDept) == 'Pediatric Surgery' ? 'selected' : '' }}>Pediatric Surgery</option>
                                            <option value="Internal Medicine" {{ old('department', $mainDept) == 'Internal Medicine' ? 'selected' : '' }}>Internal Medicine</option>
                                            <option value="IVF" {{ old('department', $mainDept) == 'IVF' ? 'selected' : '' }}>IVF</option>
                                            <option value="Others" {{ old('department', $mainDept) == 'Others' ? 'selected' : '' }}>Others</option>
                                        </select>
                                        @error('department')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Other Department -->
                                <div class="col-md-12"
                                     id="otherDepartmentBox"
                                     style="display: {{ old('department', $mainDept) == 'Others' ? 'block' : 'none' }};">
                                    <div class="form-group">
                                        <label>Specify Other Department</label>
                                        <textarea class="form-control"
                                                  name="other_department"
                                                  rows="3">{{ old('other_department', $otherDeptValue) }}</textarea>
                                    </div>
                                </div>

                                <!-- UHID -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>UHID No.</label>
                                        <input type="text"
                                               class="form-control"
                                               name="uhid_no"
                                               value="{{ old('uhid_no', $patient->uhid_no) }}">
                                    </div>
                                </div>

                                <!-- Booking Amount -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Booking Amount</label>
                                        <input type="number"
                                               class="form-control"
                                               name="booking_amount"
                                               value="{{ old('booking_amount', $patient->booking_amount) }}">
                                    </div>
                                </div>

                                <!-- Booking Date -->
                                {{-- <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Booking Date</label>
                                        <input type="date"
                                               class="form-control"
                                               name="booking_date"
                                               value="{{ old('booking_date', $patient->booking_date) }}">
                                    </div>
                                </div> --}}

                                <!-- Estimated Amount -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Estimated Amount</label>
                                        <input type="number"
                                               class="form-control"
                                               name="estimated_amount"
                                               value="{{ old('estimated_amount', $patient->estimated_amount) }}">
                                    </div>
                                </div>

                                <!-- Booking Status -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Booking Status</label>

                                        @php
                                            $status = old('booking_done', $patient->booking_done);
                                        @endphp

                                        <div>
                                             <label class="booking-checkbox done">
                                                <input type="checkbox" name="booking_done" value="yes"
                                                    {{ $status == 'yes' ? 'checked' : '' }}>
                                                <span class="checkmark"></span>
                                                <span class="label-text">Done</span> 
                                            </label>

                                             <label class="booking-checkbox not-done">
                                                <input type="checkbox" name="booking_done" value="no"
                                                    {{ $status == 'no' ? 'checked' : '' }}>
                                                     <span class="checkmark"></span>
                                                <span class="label-text">Not Done</span>
                                            </label>

                                            <label class="booking-checkbox hold">
                                                <input type="checkbox" name="booking_done" value="on_hold"
                                                    {{ $status == 'on_hold' ? 'checked' : '' }}>
                                                     <span class="checkmark"></span>
                                                <span class="label-text">On Hold</span>
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                <!-- Booking Reason -->
                                <div class="col-md-12"
                                     id="reasonBox"
                                     style="display: {{ in_array($status, ['no','on_hold']) ? 'block' : 'none' }};">
                                    <div class="form-group">
                                        <label id="reasonLabel">
                                            {{ $status == 'on_hold' ? 'Reason for Hold' : 'Reason for No Booking' }}
                                        </label>

                                        <textarea name="booking_reason"
                                                  class="form-control"
                                                  rows="2">{{ old('booking_reason', $patient->booking_reason) }}</textarea>
                                    </div>
                                </div>

                                <!-- Attachment -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Attachment</label>
                                        <input type="file" class="form-control" name="attachment">

                                        @if($patient->attachment)
                                            <br>
                                            <a href="{{ asset(public_path($patient->attachment)) }}" target="_blank">
                                                View Existing File
                                            </a>
                                        @endif
                                    </div>
                                </div>

                                <!-- Remark -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Remark</label>
                                        <textarea name="remark"
                                                  class="form-control"
                                                  rows="2">{{ old('remark', $patient->remark) }}</textarea>
                                    </div>
                                </div>

                            </div>

                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('counselor.bookings.index') }}" class="btn btn-danger">Cancel</a>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {

    /* ===============================
       BOOKING STATUS TOGGLE
    ================================ */

    const checkboxes = document.querySelectorAll('input[name="booking_done"]');
    const reasonBox = document.getElementById('reasonBox');
    const reasonLabel = document.getElementById('reasonLabel');

    checkboxes.forEach(cb => {
        cb.addEventListener('change', function() {

            checkboxes.forEach(other => {
                if (other !== this) other.checked = false;
            });

            if (this.checked && this.value === 'on_hold') {
                reasonBox.style.display = 'block';
                reasonLabel.innerText = 'Reason for Hold';
            }
            else if (this.checked && this.value === 'no') {
                reasonBox.style.display = 'block';
                reasonLabel.innerText = 'Reason for No Booking';
            }
            else {
                reasonBox.style.display = 'none';
            }
        });
    });


    /* ===============================
       DEPARTMENT TOGGLE FIX
    ================================ */

    const departmentSelect = document.getElementById('department');
    const otherDeptBox = document.getElementById('otherDepartmentBox');

    function toggleDepartmentBox() {
        if (departmentSelect.value === 'Others') {
            otherDeptBox.style.display = 'block';
        } else {
            otherDeptBox.style.display = 'none';

            // Optional: clear value when hiding
            const textarea = otherDeptBox.querySelector('textarea');
            if (textarea) {
                textarea.value = '';
            }
        }
    }

    toggleDepartmentBox(); // page load pe run
    departmentSelect.addEventListener('change', toggleDepartmentBox);

});
</script>


@endsection
