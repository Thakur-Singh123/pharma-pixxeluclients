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
                        <form action="{{ route('mr.patients.update',$patient_detail->id) }}" method="POST">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name',$patient_detail->name) }}" placeholder="Enter name">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input type="number" name="age" class="form-control" value="{{ old('age',$patient_detail->age) }}" placeholder="Enter age">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Gender</label>
                                        <select name="gender" class="form-control">
                                            <option value="" selected disabled>Select</option>
                                            <option value="Male" @if($patient_detail->gender == 'Male') selected @endif>Male</option>
                                            <option value="Female" @if($patient_detail->gender == 'Female') selected @endif>Female</option>
                                            <option value="Other" @if($patient_detail->gender == 'Other') selected @endif>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Disease</label>
                                        <input type="text" name="disease" class="form-control" value="{{ old('disease',$patient_detail->disease) }}" placeholder="Enter disease">
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Contact Number</label>
                                        <input type="number" name="contact_number" class="form-control" value="{{ old('contact_number',$patient_detail->contact_number) }}" placeholder="Enter contact number">
                                        @error('contact_number')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <textarea name="address" class="form-control" placeholder="Enter address">{{ old('address',$patient_detail->address) }}</textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                            @if($patient_detail->status != 'approved')
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                            @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection