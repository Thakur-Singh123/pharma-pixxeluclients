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
                        <div class="card-title">Edit MR</div>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manager.mrs.update',$mr_detail->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!--Name-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="name">Name</label>
                                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name',$mr_detail->name) }}" placeholder="Enter name">
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Email-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="email2">Email Address</label>
                                        <input type="email" class="form-control" id="email2" name="email" value="{{ old('email',$mr_detail->email) }}" placeholder="Enter email" autocomplete="new-email" disabled>
                                        @error('email')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Password-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="password">Password</label>
                                        <input type="password" class="form-control" id="password" name="password" value="{{ $mr_detail->password }}" placeholder="Enter password" autocomplete="new-password">
                                        @error('password')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Phone-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="phone">Phone Number</label>
                                        <input type="number" class="form-control" id="phone" name="phone" value="{{ old('phone',$mr_detail->phone) }}" placeholder="Enter phone number">
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Employee Code-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="employee_code">Employee Code</label>
                                        <input type="number" class="form-control" id="employee_code" name="employee_code" value="{{ old('employee_code',$mr_detail->employee_code) }}" placeholder="Enter employee code" disabled>
                                        @error('employee_code')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Territory-->
                                {{-- <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="territory">Territory</label>
                                        <input type="text" class="form-control" id="territory" name="territory" value="{{ old('territory',$mr_detail->territory) }}" placeholder="Enter territory">
                                        @error('territory')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div> --}}
                                <!--City-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="city">City</label>
                                        <input type="text" class="form-control" id="city" name="city" value="{{ old('city',$mr_detail->name) }}" placeholder="Enter city">
                                        @error('city')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--State-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="state">State</label>
                                        <input type="text" class="form-control" id="state" name="state" value="{{ old('state',$mr_detail->state) }}" placeholder="Enter state">
                                        @error('state')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Joining Date-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="joining_date">Joining Date</label>
                                        <input type="date" class="form-control" id="joining_date" name="joining_date" value="{{ old('joining_date',$mr_detail->joining_date) }}">
                                        @error('joining_date')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Status-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="status">Status</label>
                                        <select class="form-control" id="status" name="status">
                                        <option value="" disabled="selected">Select Status</option>
                                        <option value="Active" {{ old('status',$mr_detail->status) == 'Active' ? 'selected' : '' }}>Active</option>
                                        <option value="Pending" {{ old('status',$mr_detail->status) == 'Pending' ? 'selected' : '' }}>Inactive</option>
                                        </select>
                                        @error('status')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Update</button>
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