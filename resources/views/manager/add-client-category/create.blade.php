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
                            <h4 class="card-title">Add Client Category</h4>
                        </div>
                        <div class="card-body">
                            <form action="{{ route('mr.clients.store') }}" method="POST" autocomplete="off">
                                @csrf
                                <div class="row">
                                    <!--Category type-->
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="category_type">Category</label>
                                            <input type="text" class="form-control" id="category_type"
                                                name="category_type" value="{{ old('category_type') }}"
                                                placeholder="Enter Category">
                                            @error('category_type')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-lg-4">
                                        <div class="form-group">
                                            <label for="status">Status</label>
                                            <select class="form-control" id="status" name="status">
                                                <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                                <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                            </select>
                                            @error('status')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="card-action mt-3">
                                        <button type="submit" class="btn btn-success">Submit</button>
                                        <button type="reset" class="btn btn-danger">Cancel</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
