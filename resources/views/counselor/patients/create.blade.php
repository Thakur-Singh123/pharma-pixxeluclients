@extends('counselor.layouts.master')
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
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Add Patient Booking</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('counselor.bookings.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="row">
                                <!--Patient Name-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="patient_name">Patient Name</label>
                                        <input type="text" class="form-control" id="patient_name" name="patient_name"
                                            value="{{ old('patient_name') }}" placeholder="Enter patient name">
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
                                            value="{{ old('mobile_no') }}" placeholder="Enter mobile number">
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
                                            value="{{ old('email') }}" placeholder="Enter email address">
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
                                            <option value="" disabled selected>Select Department</option>
                                            <option value="Pediatrics" {{ old('department') == 'Pediatrics' ? 'selected' : '' }}>Pediatrics</option>
                                            <option value="OBS & GGYNAE" {{ old('department') == 'OBS & GGYNAE' ? 'selected' : '' }}>OBS & GGYNAE</option>
                                            <option value="Urology" {{ old('department') == 'Urology' ? 'selected' : '' }}>Urology</option>
                                            <option value="General Surgery" {{ old('department') == 'General Surgery' ? 'selected' : '' }}>General Surgery</option>
                                            <option value="Pediatric Surgery" {{ old('department') == 'Pediatric Surgery' ? 'selected' : '' }}>Pediatric Surgery</option>
                                            <option value="Internal Medicine" {{ old('department') == 'Internal Medicine' ? 'selected' : '' }}>Internal Medicine</option>
                                            <option value="IVF" {{ old('department') == 'IVF' ? 'selected' : '' }}>IVF</option>
                                            <option value="Others" {{ old('department') == 'Others' ? 'selected' : '' }}>Others</option>
                                        </select>
                                        @error('department')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Other Department Textarea -->
                                <div class="col-md-12" id="otherDepartmentBox" style="display: none;">
                                    <div class="form-group">
                                        <label for="other_department">Specify Other Department</label>
                                        <textarea class="form-control" id="other_department" name="other_department" rows="3"
                                            placeholder="Enter other department name here...">{{ old('other_department') }}</textarea>
                                    </div>
                                </div>
                                <!--UHID Number-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="uhid_no">UHID No.</label>
                                        <input type="text" class="form-control" id="uhid_no" name="uhid_no"
                                            value="{{ old('uhid_no') }}" placeholder="Enter UHID number">
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
                                        <input type="number" class="form-control" id="booking_amount"
                                            name="booking_amount" value="{{ old('booking_amount') }}"
                                            placeholder="Enter booking amount (optional)">
                                        @error('booking_amount')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Booking Done (Radio Buttons)-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Booking Status</label>
                                        <div class="booking-checkbox-group">
                                            <label class="booking-checkbox done">
                                                <input type="checkbox" name="booking_done" value="Yes" {{ old('booking_done') == 'Yes' ? 'checked' : '' }}>
                                                <span class="checkmark"></span>
                                                <span class="label-text">Done</span>
                                            </label>
                                            <label class="booking-checkbox not-done">
                                                <input type="checkbox" name="booking_done" value="No" {{ old('booking_done') == 'No' ? 'checked' : '' }}>
                                                <span class="checkmark"></span>
                                                <span class="label-text">Not Done</span>
                                            </label>
                                        </div>
                                        @error('booking_done')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Remarks-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label class="form-label">Remark</label>
                                        <textarea name="remark" class="form-control" rows="2" placeholder="Any additional remark">{{ old('remark') }}</textarea>
                                        @error('remark')
                                        <small class="text-danger">
                                            {{ $message }}
                                        </small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('counselor.bookings.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{--JavaScript--}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const departmentSelect = document.getElementById('department');
        const otherBox = document.getElementById('otherDepartmentBox');
        function toggleOtherBox() {
            if (departmentSelect.value === 'Others') {
                otherBox.style.display = 'block';
            } else {
                otherBox.style.display = 'none';
            }
        }
        toggleOtherBox();
        departmentSelect.addEventListener('change', toggleOtherBox);
    });
</script>
<script>
    document.querySelectorAll('.booking-checkbox input').forEach(cb => {
        cb.addEventListener('change', function () {
            document.querySelectorAll('.booking-checkbox input').forEach(other => {
                if (other !== this) other.checked = false;
            });
        });
    });
</script>
@endsection