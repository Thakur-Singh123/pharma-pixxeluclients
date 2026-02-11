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
                        @if($task_detail->is_active != '1')
                            <div class="card-title">Edit Task</div>
                        @else
                            <div class="card-title">Task Detail</div>
                        @endif
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manager.tasks.update',$task_detail->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!--Title-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="title">Title</label>
                                        <input type="text" class="form-control" id="title" name="title" value="{{ old('title',$task_detail->title) }}" placeholder="Enter Task Title">
                                        @error('title')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Description-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="description">Description</label>
                                        <textarea class="form-control" id="description" name="description" placeholder="Enter description">{{ old('description',$task_detail->description) }}</textarea>
                                        @error('description')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Start Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="start_date">Start Date</label>
                                        <input type="date" class="form-control start-date" id="start_date" name="start_date" value="{{ old('start_date',$task_detail->start_date) }}">
                                        @error('start_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--End Date-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="end_date">End Date</label>
                                        <input type="date" class="form-control end-date" id="end_date" name="end_date" value="{{ old('end_date',$task_detail->end_date) }}">
                                        @error('end_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                 <!--Location-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="location">Location</label>
                                        <input type="text" class="form-control" id="location" name="location" value="{{ old('location',$task_detail->location) }}" placeholder="Enter location">
                                        @error('location')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="pin_code">Pin Code</label>
                                        <input type="Number" class="form-control" id="pin_code" name="pin_code" value="{{ old('pin_code',$task_detail->pin_code) }}" placeholder="Enter pin code">
                                        @error('pin_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mr_id">Doctor Name</label>
                                        <select class="form-control" id="doctor_id" name="doctor_id" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach ($all_doctors as $doctor)
                                                <option value="{{ $doctor->id }}" {{ old('doctor_id', $task_detail->doctor_id) == $doctor->id ? 'selected' : '' }}>
                                                    {{ $doctor->doctor_name }} ({{ $doctor->specialist }})
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('doctor_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Assign to MR-->
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="mr_id">Assign to MR</label>
                                        <select class="form-control" id="mr_id" name="mr_id" required>
                                            <option value="" selected disabled>Select</option>
                                            @foreach ($mrs as $mr)
                                                <option value="{{ $mr->id }}" {{ old('mr_id', $task_detail->mr_id) == $mr->id ? 'selected' : '' }}>
                                                    {{ $mr->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        @error('mr_id')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Status-->
                                <!-- <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                        <option value="" disabled>Select Status</option>
                                        <option value="pending" {{ old('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="in_progress" {{ old('status') == 'in_progress' ? 'selected' : '' }}>In Progress</option>
                                        <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div> -->
                            </div>
                            <div class="card-action">
                                @if($task_detail->is_active != '1')
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <a href="{{ route('manager.tasks.index') }}" class="btn btn-danger">Cancel</a>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener("DOMContentLoaded", function () {
    let today = new Date().toISOString().split("T")[0];
    let start = document.getElementById("start_date");
    let end = document.getElementById("end_date");
    if(!start.value) start.min = today;
    if(!end.value) end.min = today;
    start.addEventListener("change", function() {
        end.min = this.value;
    });
});
</script>
@endsection