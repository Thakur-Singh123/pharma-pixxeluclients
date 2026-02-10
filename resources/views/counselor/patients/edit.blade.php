@extends('counselor.layouts.master')
@section('content')
@php
//Extract "Other" department note if saved like "Others (Cardiology)"
$mainDept = $patient->department;
$otherDeptValue = '';
if (Str::startsWith($patient->department, 'Others (')) {
    $mainDept = 'Others';
    $otherDeptValue = Str::between($patient->department, 'Others (', ')');
}
@endphp
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                @if (session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Edit Patient Booking</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('counselor.bookings.update', $patient->id) }}" method="POST"
                            autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!--Patient Name-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="patient_name">Patient Name</label>
                                        <input type="text" class="form-control" id="patient_name" name="patient_name"
                                            value="{{ old('patient_name', $patient->patient_name) }}"
                                            placeholder="Enter patient name">
                                        @error('patient_name')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Mobile Number-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="mobile_no">Mobile Number</label>
                                        <input type="number" class="form-control" id="mobile_no" name="mobile_no"
                                            value="{{ old('mobile_no', $patient->mobile_no) }}"
                                            placeholder="Enter mobile number">
                                        @error('mobile_no')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Email-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="email">Mail ID</label>
                                        <input type="email" class="form-control" id="email" name="email"
                                            value="{{ old('email', $patient->email) }}"
                                            placeholder="Enter email address">
                                        @error('email')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Department-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="department">Department</label>
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
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Other Department Textarea-->
                                <div class="col-md-12" id="otherDepartmentBox" style="display: {{ old('department', $mainDept) == 'Others' ? 'block' : 'none' }};">
                                    <div class="form-group">
                                        <label for="other_department">Specify Other Department</label>
                                        <textarea class="form-control" id="other_department" name="other_department" rows="3"
                                            placeholder="Enter other department name here...">{{ old('other_department', $otherDeptValue) }}</textarea>
                                    </div>
                                </div>
                                <!--UHID Number-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="uhid_no">UHID No.</label>
                                        <input type="text" class="form-control" id="uhid_no" name="uhid_no"
                                            value="{{ old('uhid_no', $patient->uhid_no) }}"
                                            placeholder="Enter UHID number">
                                        @error('uhid_no')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Booking Amount-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="booking_amount">Booking Amount</label>
                                        <input type="number" class="form-control" id="booking_amount" name="booking_amount"
                                            value="{{ old('booking_amount', $patient->booking_amount) }}"
                                            placeholder="Enter booking amount (optional)">
                                        @error('booking_amount')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Booking Done (Select Dropdown)-->
                                {{-- 
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="booking_done">Booking Done</label>
                                        <select class="form-control" id="booking_done" name="booking_done">
                                            <option value="" disabled>Select Option</option>
                                            <option value="Yes" {{ old('booking_done', $patient->booking_done) == 'Yes' ? 'selected' : '' }}>Yes</option>
                                            <option value="No" {{ old('booking_done', $patient->booking_done) == 'No' ? 'selected' : '' }}>No</option>
                                        </select>
                                        @error('booking_done')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                --}}
                                <!--Remarks-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remark</label>
                                        <textarea name="remark" class="form-control" rows="2" placeholder="Any additional remark">
                                            {{ old('remark', $patient->remark) }}
                                        </textarea>
                                        @error('remark')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
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
        const departmentSelect = document.getElementById('department');
        const otherBox = document.getElementById('otherDepartmentBox');
        function toggleOtherBox() {
            if (departmentSelect.value === 'Others') {
                otherBox.style.display = 'block';
            } else {
                otherBox.style.display = 'none';
                document.getElementById('other_department').value = '';
            }
        }
       toggleOtherBox();
       departmentSelect.addEventListener('change', toggleOtherBox);
    });
</script>
@endsection