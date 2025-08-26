@extends('manager.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">

                    {{-- Success Message --}}
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
                                    <!-- Title -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="title">Location</label>
                                            <input type="text" class="form-control" id="location" name="location"
                                                value="{{ old('location') }}" placeholder="Enter Task Title">
                                            @error('title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Description -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="description">Note</label>
                                            <textarea class="form-control" id="note" name="note">{{ old('note') }}</textarea>
                                            @error('note')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Start Date -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="start_date">Visit Date</label>
                                            <input type="date" class="form-control" id="visit_date" name="visit_date"
                                                value="{{ old('visit_date') }}">
                                            @error('visit_date')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="planned" {{ old('status') == 'planned' ? 'selected' : '' }}>Planned</option>
                                                <option value="cancelled" {{ old('status') == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                                <option value="completed" {{ old('status') == 'completed' ? 'selected' : '' }}>Completed</option>
                                            </select>
                                            @error('status')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Assign to MR -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mr_id">Assign to MR</label>
                                            <select class="form-control" id="mr_id" name="mr_id" required>
                                                @foreach ($mrs as $mr)
                                                    <option value="{{ $mr->id }}">{{ $mr->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('mr_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <!--Select Doctor -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="mr_id">Select Doctor</label>
                                            <select class="form-control" id="doctor_id" name="doctor_id" required>
                                                @foreach ($doctors as $doctor)
                                                    <option value="{{ $doctor->id }}">{{ $doctor->doctor_name }}</option>
                                                @endforeach
                                            </select>
                                            @error('doctor_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card-action">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <a href="{{ route('manager.tasks.index') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
