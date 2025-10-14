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
                            <div class="row mb-2">
                                <div class="col-md-3">
                                    <label>Report Date</label>
                                    <input type="date" name="report_date" class="form-control compact-input"
                                        value="{{ old('report_date', now()->format('Y-m-d')) }}" required>
                                        @error('report_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                </div>
                            </div>
                            <div class="dynamic-row">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Doctor Name</label>
                                        <select class="form-control compact-input" name="doctor_id[]" required>
                                            <option value="" disabled selected>Select Doctor</option>
                                            <!--Get doctors-->
                                            @foreach($assignedDoctors as $doctor)
                                            <option value="{{ $doctor->id }}">
                                                {{ $doctor->doctor_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Area Served</label>
                                        <input type="text" name="area_name[]" class="form-control compact-input" placeholder="Enter area served" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Total Visit</label>
                                        <input type="number" name="total_visits[]" class="form-control compact-input" placeholder="Enter total visits" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Patients Referred</label>
                                        <input type="number" name="patients_referred[]" class="form-control compact-input" placeholder="Enter patients referred" required>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label>Notes</label>
                                        <textarea name="notes[]" class="form-control compact-textarea" placeholder="Enter any notes" required></textarea>
                                    </div>
                                </div>
                            </div>
                            <div id="dynamic-rows"></div>
                            <div class="d-flex justify-content-end">
                                <button type="button" id="add-more" class="btn btn-success">Add More</button>
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
<div id="row-template" style="display:none;">
    <div class="dynamic-row">
        <span class="remove-btn">&times;</span>
        <div class="row">
            <div class="col-md-3">
                <label>Doctor Name</label>
                <select class="form-control compact-input" name="doctor_id[]" required>
                    <option value="" disabled selected>Select Doctor</option>
                    <!--Get doctors-->
                    @foreach($assignedDoctors as $doctor)
                        <option value="{{ $doctor->id }}">
                            {{ $doctor->doctor_name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label>Area Served</label>
                <input type="text" name="area_name[]" class="form-control compact-input" placeholder="Enter area served" required>
            </div>
            <div class="col-md-3">
                <label>Total Visit</label>
                <input type="number" name="total_visits[]" class="form-control compact-input" placeholder="Enter total visits" required>
            </div>
            <div class="col-md-3">
                <label>Patients Referred</label>
                <input type="number" name="patients_referred[]" class="form-control compact-input" placeholder="Enter patients referred" required>
            </div>
        </div>
        <div class="row mt-2">
            <div class="col-md-12">
                <label>Notes</label>
                <textarea name="notes[]" class="form-control compact-textarea" placeholder="Enter any notes" required></textarea>
            </div>
        </div>
    </div>
</div>
@endsection