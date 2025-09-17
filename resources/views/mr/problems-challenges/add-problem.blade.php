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
                        <h4 class="card-title">Add Problem / Challenge</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mr.problems.store') }}" method="POST" autocomplete="off">
                            @csrf
                            <div class="row">
                                <!--Title-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title') }}" placeholder="Enter title">
                                        @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Camp type-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="camp_type">Camp type</label>
                                        <input type="text" class="form-control" id="camp_type" name="camp_type" value="{{ old('camp_type') }}" placeholder="Enter camp type">
                                        @error('camp_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Start Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Problem Start Date</label>
                                        <input type="date" class="form-control start-date" id="start_date" name="start_date" value="{{ old('start_date', now()->format('Y-m-d')) }}">
                                        @error('start_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">Problem End Date</label>
                                        <input type="date" class="form-control end-date" id="end_date" name="end_date" value="{{ old('end_date', now()->format('Y-m-d')) }}">
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_name">Doctor Name</label>
                                        <input type="text" class="form-control" id="doctor_name" name="doctor_name" value="{{ old('doctor_name') }}" placeholder="Enter doctor name">
                                        @error('doctor_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="visit_name">Visits Area</label>
                                        <input type="text" class="form-control" id="visit_name" name="visit_name" value="{{ old('visit_name') }}" placeholder="Enter visit area">
                                        @error('visit_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Description-->
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label>Description</label>
                                        <textarea name="description" class="form-control" rows="4"
                                            placeholder="Enter description">{{ old('description') }}</textarea>
                                        @error('description')
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