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
                    <div class="card-header">
                        <div class="card-title">Edit Event</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mr.events.update',$event_detail->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!--Title-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$event_detail->title) }}" placeholder="Enter event title">
                                        @error('title')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Doctors-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="doctor_id">Doctor Name</label>
                                        <select class="form-control" id="doctor_id" name="doctor_id">
                                            <option value="" disabled selected>Select Doctor</option>
                                            <!--Get doctors-->
                                           @foreach($all_doctors as $doctor)
                                                <option value="{{ $doctor->id }}" {{ $event_detail->doctor_id == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->doctor_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <!--Location-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location',$event_detail->location) }}" placeholder="Enter location">
                                        @error('location')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Area Pin Code-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Area Pin Code</label>
                                        <input type="number" class="form-control" id="pin_code" name="pin_code" value="{{ old('pin_code', $event_detail->pin_code) }}" placeholder="Enter area pin code">
                                        @error('pin_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Start Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_datetime">Start Date & Time</label>
                                        <input type="datetime-local" class="form-control" id="start_datetime" name="start_datetime" value="{{ old('start_datetime',$event_detail->start_datetime) }}">
                                        @error('start_datetime')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_datetime">End Date & Time</label>
                                        <input type="datetime-local" class="form-control" id="end_datetime" name="end_datetime" value="{{ old('end_datetime',$event_detail->end_datetime) }}">
                                        @error('end_datetime')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Description-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Enter description">{{ old('description',$event_detail->description) }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Status-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                            <option value="" disabled>Select Status</option>
                                            <option value="pending" {{ old('status', $event_detail->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                                            <option value="in_progress" {{ old('status', $event_detail->status) == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                            <option value="completed" {{ old('status', $event_detail->status) == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Update</button>
                                <a href="{{ route('mr.events.index') }}" class="btn btn-danger">Cancel</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection