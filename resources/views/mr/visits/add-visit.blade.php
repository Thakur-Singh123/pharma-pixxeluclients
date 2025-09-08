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
                                            value="{{ old('area_name') }}" placeholder="Enter Area Name">
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
                                            value="{{ old('area_block') }}" placeholder="Enter Area Block">
                                        @error('area_block')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--District-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="district">Distrcit</label>
                                        <input type="text" class="form-control" id="district" name="district"
                                            placeholder="Enter District">
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
                                            value="{{ old('state') }}" placeholder="Enter State">
                                        @error('state')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Employee Code-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="area_code">Area Code</label>
                                        <input type="number" class="form-control" id="area_code" name="area_code"
                                            value="{{ old('area_code') }}" placeholder="Enter Area Code">
                                        @error('area_code')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="status">Visit Type</label>
                                        <select name="visit_type" id="visit_type" class="form-control">
                                            <option value="doctor">Doctor Visit</option>
                                            <option value="religious_places">Religious Places</option>
                                            <option value="other">Other Visit (NGOs, Asha workers etc.)</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4" id="doctor_fields" style="display: block;">
                                    <div class="form-group">
                                        <label for="status">Doctor</label>
                                        <select name="doctor_id" class="form-control">
                                            <option value="">Please select</option>
                                            @foreach ($assignedDoctors as $doctor)
                                            <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--Religious Sub-type-->
                                <div class="col-md-6 col-lg-4" id="religious_subtype_div" style="display: none;">
                                    <div class="form-group">
                                        <label for="religious_type">Religious Type</label>
                                        <select name="religious_type" id="religious_type" class="form-control">
                                            <option value="" selected disabled>Select Place</option>
                                            <option value="temple">Temple</option>
                                            <option value="gurudwara">Gurudwara</option>
                                        </select>
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
    document.getElementById('visit_type').addEventListener('change', function() {
        if (this.value === 'doctor') {
            document.getElementById('doctor_fields').style.display = 'block';
            document.getElementById('other_fields').style.display = 'none';
        } else {
            document.getElementById('doctor_fields').style.display = 'none';
            document.getElementById('other_fields').style.display = 'block';
        }
    });
</script>
<script>
    document.getElementById('visit_type').addEventListener('change', function () {
        let visitType = this.value;
        document.getElementById('religious_subtype_div').style.display = 'none';
        if (visitType === 'religious_places') {
            document.getElementById('religious_subtype_div').style.display = 'block';
        }
    });
</script>
@endsection