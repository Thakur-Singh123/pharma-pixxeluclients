@extends('mr.layouts.master')
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
                  <div class="card-title">Edit Visit</div>
               </div>
               <div class="card-body">
                  <form action="{{ route('mr.update.visit', $visit_detail->id) }}" method="POST" autocomplete="off">
                     @csrf
                     <div class="row">
                        <!-- Area Name -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="area_name">Area Name</label>
                              <input type="text" class="form-control" id="area_name" name="area_name"
                                 value="{{ old('area_name', $visit_detail->area_name) }}" placeholder="Enter Area Name">
                              @error('area_name')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- Area Block -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="area_block">Area Block</label>
                              <input type="text" class="form-control" id="area_block" name="area_block"
                                 value="{{ old('area_block', $visit_detail->area_block) }}" placeholder="Enter Area Block">
                              @error('area_block')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- District -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="district">Distrcit</label>
                              <input type="text" class="form-control" id="district" name="district"
                                 value="{{ old('district', $visit_detail->district) }}" placeholder="Enter District">
                              @error('district')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- State -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="state">State</label>
                              <input type="text" class="form-control" id="state" name="state"
                                 value="{{ old('state', $visit_detail->state) }}" placeholder="Enter State">
                              @error('state')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- Employee Code -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="area_code">Area Code</label>
                              <input type="text" class="form-control" id="area_code" name="area_code"
                                 value="{{ old('area_code', $visit_detail->area_code) }}" placeholder="Enter Area Code">
                              @error('area_code')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- Status -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="status">Status</label>
                              <select class="form-control" id="status" name="status">
                              <option value="" disabled="selected">Select Status</option>
                              <option value="Active" {{ old('status', $visit_detail->status) == 'Active' ? 'selected' : '' }}>Active</option>
                              <option value="Pending" {{ old('status', $visit_detail->status) == 'Pending' ? 'selected' : '' }}>Pending</option>
                              <option value="Suspend" {{ old('status', $visit_detail->status) == 'Suspend' ? 'selected' : '' }}>Suspend</option>
                              <option value="Approved" {{ old('status', $visit_detail->status) == 'Approved' ? 'selected' : '' }}>Approved</option>
                              </select>
                              @error('status')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
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
@endsection