@extends('manager.layouts.master')
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
                        <h4 class="card-title">Edit Daily Report</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manager.reports.update.daily', $report_detail->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row mb-2">
                                <div class="col-md-3">
                                <label>Report Date</label>
                                <input type="date" name="report_date" class="form-control compact-input"
                                    value="{{ old('report_date',$report_detail->report_date) }}" required>
                                    @error('report_date')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            @foreach($report_detail->report_details as $detail)
                            <div class="dynamic-row">
                                <div class="row">
                                    <div class="col-md-3">
                                        <label>Doctor Name</label>
                                        <select class="form-control compact-input" name="doctor_id[]" required>
                                            <option value="" disabled selected>Select Doctor</option>
                                            <!--Get doctors-->
                                            @foreach($assignedDoctors as $doctor)
                                            <option value="{{ $doctor->id }}" @if($detail->doctor_id == $doctor->id) selected @endif>
                                                {{ $doctor->doctor_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Area Served</label>
                                        <input type="text" name="area_name[]" class="form-control compact-input" value="{{ old('area_name', $detail->area_name) ?? '' }}" placeholder="Enter area served" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Total Visit</label>
                                        <input type="number" name="total_visits[]" class="form-control compact-input" value="{{ old('total_visits', $detail->total_visits) ?? '' }}" placeholder="Enter total visits" required>
                                    </div>
                                    <div class="col-md-3">
                                        <label>Patients Referred</label>
                                        <input type="number" name="patients_referred[]" class="form-control compact-input" value="{{ old('patients_referred', $detail->patients_referred) ?? '' }}" placeholder="Enter patients referred" required>
                                    </div>
                                </div>
                                <div class="row mt-1">
                                    <div class="col-md-12">
                                        <label>Notes</label>
                                        <textarea name="notes[]" class="form-control compact-textarea" placeholder="Enter any notes" required>{{ old('notes', $detail->notes) ?? '' }}</textarea>
                                    </div>
                                </div>
                            </div>
                            @endforeach
                            <div id="dynamic-rows"></div>
                            <div class="d-flex justify-content-end">
                                <button type="button" id="add-more" class="btn btn-success">Add More</button>
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