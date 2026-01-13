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
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h4 class="card-title">Edit Problem / Challenge</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manager.problems.update', $problem_detail->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!--Title-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title', $problem_detail->title) }}" placeholder="Enter title">
                                        @error('title')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Camp type-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="camp_type">Camp type</label>
                                        <input type="text" class="form-control" id="camp_type" name="camp_type" value="{{ old('camp_type', $problem_detail->camp_type) }}" placeholder="Enter camp type">
                                        @error('camp_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Start Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Problem Start Date</label>
                                        <input type="date" class="form-control start-date" id="start_date" name="start_date" value="{{ old('start_date', $problem_detail->start_date) }}">
                                        @error('start_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">Problem End Date</label>
                                        <input type="date" class="form-control end-date" id="end_date" name="end_date" value="{{ old('end_date', $problem_detail->end_date) }}">
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_name">Doctor Name</label>
                                        <input type="text" class="form-control" id="doctor_name" name="doctor_name" value="{{ old('doctor_name', $problem_detail->doctor_name) }}" placeholder="Enter doctor name">
                                        @error('doctor_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="visit_name">Visits Area</label>
                                        <input type="text" class="form-control" id="visit_name" name="visit_name" value="{{ old('visit_name', $problem_detail->visit_name) }}" placeholder="Enter visit area">
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
                                            placeholder="Enter description">{{ old('description', $problem_detail->description) }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!-- <div class="card-action">
                                @if($problem_detail->status != 'approved')
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <button type="reset" class="btn btn-danger">Cancel</button>
                                @endif
                            </div> -->
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection