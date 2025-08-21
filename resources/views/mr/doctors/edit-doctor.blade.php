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
                  <div class="card-title">Edit Doctor</div>
               </div>
               <div class="card-body">
                  <form action="{{ route('mr.update.doctor', $doctor_detail->id) }}" method="POST" enctype="multipart/form-data" autocomplete="off">
                     @csrf
                     <div class="row">
                        <!-- Area Name -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="area_name">Area Name</label>
                              <input type="text" class="form-control" id="area_name" name="area_name"
                                 value="{{ old('area_name', $doctor_detail->area_name) }}" placeholder="Enter Area Name">
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
                                 value="{{ old('area_block', $doctor_detail->area_block) }}" placeholder="Enter Area Block">
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
                              value="{{ old('area_block', $doctor_detail->area_block) }}"
                                 placeholder="Enter District">
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
                                 value="{{ old('state', $doctor_detail->state) }}" placeholder="Enter State">
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
                                 value="{{ old('area_code', $doctor_detail->area_code) }}" placeholder="Enter Area Code">
                              @error('area_code')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- Doctor Name -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="doctor_name">Doctor Name</label>
                              <input type="text" class="form-control" id="doctor_name" name="doctor_name"
                                 value="{{ old('doctor_name', $doctor_detail->doctor_name) }}" placeholder="Enter Doctor Name">
                              @error('doctor_name')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- Doctor Contact -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="doctor_contact">Doctor Contact</label>
                              <input type="text" class="form-control" id="doctor_contact" name="doctor_contact"
                                 value="{{ old('doctor_contact', $doctor_detail->doctor_contact) }}" placeholder="Enter Contact">
                              @error('doctor_contact')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- Location -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="location">Location</label>
                              <input type="text" class="form-control" id="location" name="location"
                                 value="{{ old('location', $doctor_detail->location) }}" placeholder="Enter Location">
                              @error('location')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!-- Remarks -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="remarks">Remarks</label>
                              <input type="text" class="form-control" id="remarks" name="remarks"
                                 value="{{ old('remarks', $doctor_detail->remarks) }}" placeholder="Enter remarks">
                              @error('remarks')
                              <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                         <!-- Visit Type -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="visit_type">Visit Type</label>
                              <select class="form-control" id="visit_type" name="visit_type">
                                 <option value="" disabled="selected">Select Visit</option>
                                 <option value="Ngo" {{ old('visit_type', $doctor_detail->visit_type) == 'Ngo' ? 'selected' : '' }}>Ngo</option>
                                 <option value="Asha" {{ old('visit_type', $doctor_detail->visit_type) == 'Asha' ? 'selected' : '' }}>Asha</option>
                                 <option value="Doctor" {{ old('visit_type', $doctor_detail->visit_type) == 'Doctor' ? 'selected' : '' }}>Doctor</option>
                                 <option value="Religious" {{ old('visit_type', $doctor_detail->visit_type) == 'Religious' ? 'selected' : '' }}>Religious</option>
                                  <option value="Places" {{ old('visit_type', $doctor_detail->visit_type) == 'Places' ? 'selected' : '' }}>Places</option>
                                   <option value="Other" {{ old('visit_type', $doctor_detail->visit_type) == 'Other' ? 'selected' : '' }}>Other</option>
                              </select>
                           </div>
                        </div>
                        <!-- image -->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="image">Image</label>
                              <input
                                 type="file" name="image"
                                 class="form-control"
                                 id="exampleFormControlFile1"
                                 />
                           </div>
                           @if($doctor_detail->picture)
                           <img src = "{{ asset('public/uploads/doctors/' .$doctor_detail->picture) }}">
                           @else
                           -
                           @endif

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