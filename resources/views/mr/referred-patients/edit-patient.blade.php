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
                        <h4 class="card-title">Edit Patient</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mr.patients.store', $patient_detail->id) }}" method="POST">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="patient_name" class="form-control" value="{{ old('patient_name', $patient_detail->patient_name) }}" placeholder="Enter name">
                                        @error('patient_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="number" name="contact_no" class="form-control" value="{{ old('contact_no', $patient_detail->contact_no) }}" placeholder="Enter contact number">
                                        @error('contact_no')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address', $patient_detail->address) }}" placeholder="Enter address">
                                        @error('address')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Disease</label>
                                        <input type="text" name="disease" class="form-control" value="{{ old('disease', $patient_detail->disease) }}" placeholder="Enter disease">
                                        @error('disease')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Referred To</label>
                                        <input type="text" name="referred_to" class="form-control" value="{{ old('referred_to', $patient_detail->referred_to) }}" placeholder="Enter referred to">
                                        @error('referred_to')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Status</label>
                                        <select name="status" class="form-control">
                                            <option value="" selected disabled>Select</option>
                                            <option value="Active" @if($patient_detail->status == 'Active') selected @endif>Active</option>
                                            <option value="Pending" @if($patient_detail->status == 'Pending') selected @endif>Pending</option>
                                            <option value="Suspend" @if($patient_detail->status == 'Suspend') selected @endif>Suspend</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Update</button>
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