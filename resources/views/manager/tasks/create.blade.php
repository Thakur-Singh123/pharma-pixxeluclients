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
                            <div class="card-title">Add MR</div>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('manager.tasks.store') }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <!-- Name -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Title</label>
                                            <input type="text" class="form-control" id="title" name="title"
                                                value="{{ old('title') }}" placeholder="Enter Full Name">
                                            @error('title')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="name">Description</label>
                                            <textarea class="form-control" name="description"></textarea>
                                            @error('description')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <!-- Status -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="Pending" {{ old('status') == 'pending' ? 'selected' : '' }}>
                                                    Pending</option>
                                                <option value="in_progress" {{ old('status') == 'Active' ? 'selected' : '' }}>
                                                   In Progress</option>
                                                <option value="Pending"
                                                    {{ old('status') == 'completed' ? 'selected' : '' }}>Completed
                                                </option>
                                            </select>
                                            @error('status')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="status">Assign to MR</label>
                                            <select class="form-control" name="mr_id" required>
                                                @foreach ($mrs as $mr)
                                                    <option value="{{ $mr->id }}">{{ $mr->name }}</option>
                                                @endforeach
                                            </select>
                                            @error('mr_id')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="card-action">
                                    <button type="submit" class="btn btn-success">Submit</button>
                                    <a href="{{ route('manager.mrs.index') }}" class="btn btn-danger">Cancel</a>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
