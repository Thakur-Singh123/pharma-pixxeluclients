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
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Add Visit</h4>
                        <!--Add New Doctor Button-->
                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addDoctorModal">
                            <i class="fas fa-user-md"></i> Add New Doctor
                        </button>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mr.submit.visit') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="row">
                                <!--Area Name-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="area_name">Area Name</label>
                                        <input type="text" class="form-control" id="area_name" name="area_name"
                                            value="{{ old('area_name') }}" placeholder="Enter area name">
                                        @error('area_name')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!-- Area Block -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="area_block">Area Block</label>
                                        <input type="text" class="form-control" id="area_block" name="area_block"
                                            value="{{ old('area_block') }}" placeholder="Enter area block">
                                        @error('area_block')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--District-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="district">District</label>
                                        <input type="text" class="form-control" id="district" name="district"
                                            placeholder="Enter district">
                                        @error('district')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                    </div>
                                <!--State-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" id="state" name="state"
                                            value="{{ old('state') }}" placeholder="Enter state">
                                        @error('state')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Area Pin Code-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="pin_code">Area Pin Code</label>
                                        <input type="number" class="form-control" id="pin_code" name="pin_code"
                                            value="{{ old('pin_code') }}" placeholder="Enter area pin code">
                                        @error('pin_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="visit_date">Visit Date</label>
                                        <input type="date" class="form-control start-date" id="visit_date" name="visit_date"
                                            value="{{ old('visit_date', date('Y-m-d')) }}" placeholder="Enter visit date">
                                        @error('visit_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="status">Visit Type</label>
                                        <select name="visit_type" id="visit_type" class="form-control">
                                            <option value="doctor" {{ old('visit_type') == 'doctor' ? 'selected' : '' }}>Doctor Visit</option>
                                            <option value="religious_places" {{ old('visit_type') == 'religious_places' ? 'selected' : '' }}>Religious Places</option>
                                            <option value="other" {{ old('visit_type') == 'other' ? 'selected' : '' }}>Other Visit (NGOs, Asha workers etc.)</option>
                                        </select>
                                        @error('visit_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4" id="doctor_fields" style="display: block;">
                                    <div class="form-group">
                                        <label for="status">Doctor</label>
                                        <select name="doctor_id" class="form-control">
                                            <option value="">Please select</option>
                                            @foreach ($assignedDoctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }} ({{ $doctor->specialist }})
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Religious Places input -->
                                <div class="col-md-6 col-lg-4 visit-extra visit-religious" style="display:none;">
                                    <div class="form-group">
                                        <label>Religious Place Name</label>
                                        <input type="text" name="religious_place_name" id="religious_place_name" value="{{ old('religious_place_name') }}" class="form-control" placeholder="Enter place name">
                                        @error('religious_place_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Other Visit input-->
                                <div class="col-md-6 col-lg-4 visit-extra visit-other" style="display:none;">
                                    <div class="form-group">
                                        <label>Other Visit Details</label>
                                        <input type="text" name="other_visit_details" id="other_visit_details" class="form-control" value="{{ old('other_visit_details') }}" placeholder="Enter details">
                                        @error('other_visit_details')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Status-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="" disabled="selected">Select Status</option>
                                            <option value="Active" {{ old('status') == 'Active' ? 'selected' : '' }}>
                                            Active</option>
                                            <option value="Pending" {{ old('status') == 'Pending' ? 'selected' : '' }}>
                                            Pending</option>
                                            <option value="Suspend" {{ old('status') == 'Suspend' ? 'selected' : '' }}>
                                            Suspend</option>
                                            <option value="Approved"
                                            {{ old('status') == 'Approved' ? 'selected' : '' }}>Approved</option>
                                        </select>
                                        @error('status')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Comments</label>
                                        <textarea name="comments" class="form-control" rows="4"
                                            placeholder="Enter comments">{{ old('comments') }}</textarea>
                                        @error('comments')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal: Add New Doctor -->
<div class="modal fade" id="addDoctorModal" tabindex="-1" aria-labelledby="addDoctorModalLabel"
   aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="{{ route('mr.doctors.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add New Doctor</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <label>Doctor Name</label>
                            <input type="text" name="doctor_name" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Contact Number</label>
                            <input type="text" name="doctor_contact" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Location</label>
                            <input type="text" name="location" class="form-control" required>
                        </div>
                        <div class="col-md-6">
                            <label>Picture (Clinic Visit)</label>
                            <input type="file" name="image" class="form-control">
                        </div>
                        <div class="col-md-12 mt-3">
                            <label>Conversation Remarks</label>
                            <textarea name="remarks" rows="3" class="form-control"></textarea>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Doctor</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function() {
    const visitType = document.getElementById("visit_type");
    const doctorFields = document.getElementById("doctor_fields");
    const religiousFields = document.querySelector(".visit-religious");
    const otherFields = document.querySelector(".visit-other");
    function toggleFields() {
        doctorFields.style.display = "none";
        religiousFields.style.display = "none";
        otherFields.style.display = "none";
        if (visitType.value === "doctor") {
            doctorFields.style.display = "block";
        } else if (visitType.value === "religious_places") {
            religiousFields.style.display = "block";
        } else if (visitType.value === "other") {
            otherFields.style.display = "block";
        }
    }
    visitType.addEventListener("change", toggleFields);
    toggleFields();
});
</script>
@endsection