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
                        <div class="card-title">Add Visit Plan</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manager.visit-plans.store') }}" method="POST" autocomplete="off">
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
                                <!--Description-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Enter description">{{ old('description') }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Plan Type-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="plan_type">Plan Type</label>
                                        <select class="form-control" id="plan_type" name="plan_type">
                                            <option value="" disabled>Select Plan</option>
                                            <option value="monthly" {{ old('plan_type') == 'monthly' ? 'selected' : '' }}>Monthly</option>
                                            <option value="weekly" {{ old('plan_type') == 'weekly' ? 'selected' : '' }}>Weekly</option>
                                        </select>
                                        @error('plan_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Visit Category-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="visit_category">Visit Category</label>
                                        <select class="form-control" id="visit_category" name="visit_category">
                                            <option value="" disabled>Select Category</option>
                                            <option value="hospital" {{ old('visit_category') == 'hospital' ? 'selected' : '' }}>Hospital Visit</option>
                                            <option value="doctor" {{ old('visit_category') == 'doctor' ? 'selected' : '' }}>Doctor Meeting</option>
                                            <option value="area" {{ old('visit_category') == 'area' ? 'selected' : '' }}>Area</option>
                                            <option value="camp" {{ old('visit_category') == 'camp' ? 'selected' : '' }}>Camp</option>
                                            <option value="event" {{ old('visit_category') == 'event' ? 'selected' : '' }}>Event</option>
                                        </select>
                                        @error('visit_category')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Start Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control" id="start_date" name="start_date" value="{{ old('start_date') }}">
                                        @error('start_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control" id="end_date" name="end_date" value="{{ old('end_date') }}">
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Location-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location') }}" placeholder="Enter location">
                                        @error('location')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Assign to MR-->
                                {{-- 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="assigned_to">Assign to MR</label>
                                        <select class="form-control" id="assigned_to" name="assigned_to">
                                            @foreach ($mrs as $mr)
                                            <option value="{{ $mr->id }}" {{ old('assigned_to') == $mr->id ? 'selected' : '' }}>
                                                {{ $mr->name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('assigned_to')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                --}}
                                <!-- Select Doctor -->
                                {{-- 
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_id">Select Doctor</label>
                                        <select class="form-control" id="doctor_id" name="doctor_id">
                                            @foreach ($doctors as $doctor)
                                            <option value="{{ $doctor->id }}" {{ old('doctor_id') == $doctor->id ? 'selected' : '' }}>
                                                {{ $doctor->doctor_name }}
                                            </option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                --}}
                                <!--Status-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="" disabled>Select Status</option>
                                            <option value="open" {{ old('status') == 'open' ? 'selected' : '' }}>Open</option>
                                            <option value="interested" {{ old('status') == 'interested' ? 'selected' : '' }}>Interested</option>
                                            <option value="assigned" {{ old('status') == 'assigned' ? 'selected' : '' }}>Assigned</option>
                                            <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Lock(checkbox)-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>
                                            <input type="checkbox" name="is_locked" value="1" {{ old('is_locked') ? 'checked' : '' }}>Lock this plan
                                        </label>
                                        @error('is_locked')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <a href="{{ route('manager.visit-plans.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection