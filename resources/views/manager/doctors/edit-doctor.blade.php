@extends('manager.layouts.master')
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
                        <div class="card-title">Edit Doctor</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manager.update.doctor', $doctor_detail->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                            @csrf
                            <div class="row">
                                <!--Hospital Name-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="hospital_name">Hospital Name</label>
                                        <input type="text" class="form-control" id="hospital_name" name="hospital_name"
                                            value="{{ old('hospital_name',$doctor_detail->hospital_name) }}" placeholder="Enter hospital name">
                                        @error('hospital_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Hospital Typ-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="hospital_type">Hospital Type</label>
                                        <input type="text" class="form-control" id="hospital_type" name="hospital_type"
                                            value="{{ old('hospital_type',$doctor_detail->hospital_type) }}" placeholder="Enter hospital type">
                                        @error('hospital_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Area Name-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="area_name">Area Name</label>
                                        <input type="text" class="form-control" id="area_name" name="area_name"
                                            value="{{ old('area_name', $doctor_detail->area_name) }}"
                                            placeholder="Enter area name">
                                        @error('area_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Area Block-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="area_block">Area Block</label>
                                        <input type="text" class="form-control" id="area_block" name="area_block"
                                            value="{{ old('area_block', $doctor_detail->area_block) }}"
                                            placeholder="Enter area block">
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
                                            value="{{ old('area_block', $doctor_detail->area_block) }}"
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
                                            value="{{ old('state', $doctor_detail->state) }}" placeholder="Enter state">
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
                                            value="{{ old('area_code', $doctor_detail->area_code) }}"
                                            placeholder="Enter area code">
                                        @error('area_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Doctor Name-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="doctor_name">Doctor Name</label>
                                        <input type="text" class="form-control" id="doctor_name" name="doctor_name"
                                            value="{{ old('doctor_name', $doctor_detail->doctor_name) }}"
                                            placeholder="Enter doctor name">
                                        @error('doctor_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Doctor Contact-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="doctor_contact">Doctor Contact</label>
                                        <input type="number" class="form-control" id="doctor_contact"
                                            name="doctor_contact"
                                            value="{{ old('doctor_contact', $doctor_detail->doctor_contact) }}"
                                            placeholder="Enter contact">
                                        @error('doctor_contact')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Location-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" id="location" name="location"
                                            value="{{ old('location', $doctor_detail->location) }}"
                                            placeholder="Enter location">
                                        @error('location')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Speciality-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="speciality">Speciality</label>
                                        <input type="text" class="form-control" id="speciality" name="speciality"
                                            value="{{ old('speciality', $doctor_detail->specialist) }}" placeholder="Enter speciality">
                                        @error('speciality')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Remarks-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="remarks">Remarks</label>
                                        <textarea name="remarks" rows="3" class="form-control" placeholder="Enter remarks">{{ old('remarks', $doctor_detail->remarks) }}</textarea>
                                        @error('remarks')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--image -->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="image">Image</label>
                                        <input type="file" name="image" class="form-control"
                                            id="exampleFormControlFile1" />
                                    </div>
                                    <!--check if image exists or not-->
                                    @if ($doctor_detail->picture)
                                        <img src = "{{ asset('public/uploads/doctors/' . $doctor_detail->picture) }}">
                                    @else
                                        -
                                    @endif
                                </div>
                                <!--Assigne MR-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="status">Assigne MR</label>
                                        <select class="form-control" id="visit_type" name="mr_id[]" multiple>
                                            <!--Get mrs-->
                                            @foreach ($mrs as $mr)
                                            <option value="{{ $mr->id }}" {{ in_array($mr->id, $assignedMrsIds) ? 'selected' : '' }}>
                                                {{ $mr->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--Status-->
                                <!-- <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select name="status" class="form-control">
                                            <option value="active" {{ $doctor_detail->status == 'active' ? 'selected' : '' }}>Active</option>
                                            <option value="pending" {{ $doctor_detail->status == 'pending' ? 'selected' : '' }}>Pending</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div> -->
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