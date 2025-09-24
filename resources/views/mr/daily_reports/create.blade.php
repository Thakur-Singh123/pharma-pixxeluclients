@extends('mr.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
                @endif
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Add Daily Report</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mr.daily-reports.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Report Date</label>
                                        <input type="date" name="report_date" class="form-control start-date"  placeholder="Enter report date" value="{{ old('report_date', now()->format('Y-m-d')) }}">
                                        @error('report_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Doctors-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="doctor_id">Doctor Name</label>
                                        <select class="form-control" id="doctor_id" name="doctor_id">
                                            <option value="" disabled selected>Select Doctor</option>
                                            <!--Get doctors-->
                                            @foreach($assignedDoctors as $doctor)
                                            <option value="{{ $doctor->id }}">
                                                {{ $doctor->doctor_name }}
                                            </option>
                                            @endforeach
                                            @error('doctor_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Area Served</label>
                                        <input type="area_name" name="area_name" class="form-control" value="{{ old('area_name') }}" placeholder="Enter area served">
                                        @error('area_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Total Visit</label>
                                        <input type="number" name="total_visits" class="form-control" value="{{ old('total_visits') }}" placeholder="Enter total visits">
                                        @error('total_visits')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Patients Reffered</label>
                                        <input type="number" name="patients_referred" class="form-control" value="{{ old('patients_referred') }}" placeholder="Enter patients reffered">
                                        @error('patients_referred')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Notes</label>
                                        <textarea name="notes" class="form-control" rows="4" placeholder="Enter any notes" >{{ old('notes') }}</textarea>
                                        @error('notes')
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
@endsection