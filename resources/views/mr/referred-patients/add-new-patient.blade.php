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
                    <h4 class="card-title">Add Patient</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('mr.patients.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <!--Patient Name-->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Name</label>
                                    <input type="text" name="patient_name" class="form-control" value="{{ old('patient_name') }}" placeholder="Enter name">
                                    @error('patient_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!--Contact Number-->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Contact Number</label>
                                    <input type="number" name="contact_no" class="form-control" value="{{ old('contact_no') }}" placeholder="Enter contact number">
                                    @error('contact_no')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!--Address-->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Enter address">
                                    @error('address')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!--Gender-->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="" disabled selected>Select</option>
                                        <option value="Male" {{ old('gender')=='Male' ? 'selected' : '' }}>Male</option>
                                        <option value="Female" {{ old('gender')=='Female' ? 'selected' : '' }}>Female</option>
                                        <option value="Other" {{ old('gender')=='Other' ? 'selected' : '' }}>Other</option>
                                    </select>
                                    @error('gender')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!--Emergency Contact-->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Emergency Contact</label>
                                    <input type="number" name="emergency_contact" class="form-control" value="{{ old('emergency_contact') }}" placeholder="Enter emergency number">
                                    @error('emergency_contact')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <!--Assigned Doctor-->
                            <!-- <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Assigned Doctor</label>
                                    <select name="doctor_id" class="form-control">
                                        <option value="" disabled selected>Select Doctor</option>
                                        @foreach($all_doctors as $doctor)
                                        <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }} ({{ $doctor->specialist ?? 'N/A' }})</option>
                                        @endforeach
                                    </select>
                                    @error('doctor_id')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div> -->
                            <!--Status-->
                            <div class="col-md-6 col-lg-4">
                                <div class="form-group">
                                    <label>Status</label>
                                    <select name="status" class="form-control">
                                        <option value="" selected disabled>Select</option>
                                        <option value="Active">Active</option>
                                        <option value="Pending">Pending</option>
                                        <option value="Suspend">Suspend</option>
                                    </select>
                                </div>
                            </div>
                            <!--Medical History-->
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label>Medical History / Allergies</label>
                                    <textarea name="medical_history" class="form-control" rows="3" placeholder="Enter medical history, allergies, or past diseases">{{ old('medical_history') }}</textarea>
                                    @error('medical_history')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <!--Referred Details Section-->
                        <div class="col-12 mt-3">
                            <h5 class="refer-heading">Referred Details</h5>
                            <div class="row">
                                <!--Referred By Contact Number-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Referred By Contact</label>
                                        <input type="number" name="referred_contact" class="form-control" value="{{ old('referred_contact') }}" placeholder="Enter contact number">
                                    </div>
                                </div>
                                <!--Preferred Doctor Name-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Preferred Doctor</label>
                                        <input type="text" name="preferred_doctor" class="form-control" value="{{ old('preferred_doctor') }}" placeholder="Enter doctor name">
                                    </div>
                                </div>
                                <!--Place Referred-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Place Referred</label>
                                        <input type="text" name="place_referred" class="form-control" value="{{ old('place_referred') }}" placeholder="Enter place referred">
                                    </div>
                                </div>
                                <!--Bill Amount-->
                                <div class="col-md-3">
                                    <div class="form-group">
                                        <label>Bill Amount</label>
                                        <input type="number" name="bill_amount" class="form-control" value="{{ old('bill_amount') }}" placeholder="Enter bill amount">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--Buttons-->
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
@endsection