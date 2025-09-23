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
                  <div class="card-title">Edit Visit</div>
               </div>
               <div class="card-body">
                  <form action="{{ route('mr.update.visit', $visit_detail->id) }}" method="POST" autocomplete="off">
                     @csrf
                     <div class="row">
                        <!--Area Name-->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="area_name">Area Name</label>
                              <input type="text" class="form-control" id="area_name" name="area_name"
                                 value="{{ old('area_name', $visit_detail->area_name) }}" placeholder="Enter area name">
                              @error('area_name')
                                 <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!--Area Block-->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="area_block">Area Block</label>
                              <input type="text" class="form-control" id="area_block" name="area_block"
                                 value="{{ old('area_block', $visit_detail->area_block) }}" placeholder="Enter area block">
                              @error('area_block')
                                 <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!--District-->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="district">District</label>
                              <input type="text" class="form-control" id="district" name="district"
                                 value="{{ old('district', $visit_detail->district) }}" placeholder="Enter district">
                              @error('district')
                                 <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!--State-->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="state">State</label>
                              <input type="text" class="form-control" id="state" name="state"
                                 value="{{ old('state', $visit_detail->state) }}" placeholder="Enter state">
                              @error('state')
                                 <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                        <!--Area Pin Code-->
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                                 <label for="pin_code">Area Pin Code</label>
                                 <input type="number" class="form-control" id="pin_code" name="pin_code"
                                    value="{{ old('pin_code', $visit_detail->pin_code) }}" placeholder="Enter area pin code">
                                 @error('pin_code')
                                    <small class="text-danger">{{ $message }}</small>
                                 @enderror
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                                 <label for="visit_date">Visit Date</label>
                                 <input type="date" class="form-control start-date" id="visit_date" name="visit_date"
                                    value="{{ old('visit_date', $visit_detail->visit_date) }}" placeholder="Enter visit date">
                                 @error('visit_date')
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
                        <div class="col-md-8">
                           <div class="form-group">
                                 <label>Comments</label>
                                 <textarea name="comments" class="form-control" rows="4"
                                    placeholder="Enter comments">{{ old('comments', $visit_detail->comments) }}</textarea>
                                 @error('comments')
                                    <small class="text-danger">{{ $message }}</small>
                                 @enderror
                           </div>
                        </div>
                        <div class="col-md-6 col-lg-4">
                           <div class="form-group">
                              <label for="status">Visit Type</label>
                              <select name="visit_type" id="visit_type" class="form-control">
                                 <option value="doctor" {{ old('visit_type', $visit_detail->visit_type) == 'doctor' ? 'selected' : '' }}>Doctor Visit</option>
                                 <option value="bams_rmp_dental" {{ old('visit_type', $visit_detail->visit_type) == 'bams_rmp_dental' ? 'selected' : '' }}>BAMS RMP Dental</option>
                                 <option value="asha_workers" {{ old('visit_type', $visit_detail->visit_type) == 'asha_workers' ? 'selected' : '' }}>Asha Workers</option>
                                 <option value="health_workers" {{ old('visit_type', $visit_detail->visit_type) == 'health_workers' ? 'selected' : '' }}>Health Workers</option>
                                 <option value="anganwadi" {{ old('visit_type', $visit_detail->visit_type) == 'anganwadi' ? 'selected' : '' }}>Anganwadi / Balvatika</option>
                                 <option value="school" {{ old('visit_type', $visit_detail->visit_type) == 'school' ? 'selected' : '' }}>School</option>
                                 <option value="villages" {{ old('visit_type', $visit_detail->visit_type) == 'villages' ? 'selected' : '' }}>Villages</option>
                                 <option value="city" {{ old('visit_type', $visit_detail->visit_type) == 'city' ? 'selected' : '' }}>City</option>
                                 <option value="societies" {{ old('visit_type', $visit_detail->visit_type) == 'societies' ? 'selected' : '' }}>Societies</option>
                                 <option value="ngo" {{ old('visit_type', $visit_detail->visit_type) == 'ngo' ? 'selected' : '' }}>NGO</option>
                                 <option value="religious_places" {{ old('visit_type', $visit_detail->visit_type) == 'religious_places' ? 'selected' : '' }}>Religious Places</option>
                                 <option value="other" {{ old('visit_type', $visit_detail->visit_type) == 'other' ? 'selected' : '' }}>Other Visit (NGOs, Asha workers etc.)</option>
                              </select>
                           </div>
                        </div>
            
                           <div class="col-md-8 visit-extra visit-doctor" style="display:none;">
                           <div class="form-group">
                              <label for="status">Doctor</label>
                              <select name="doctor_id" class="form-control">
                                 <option value="">Please select</option>
                                 <!--Get doctors-->
                                 @foreach ($assignedDoctors as $doctor)
                                 <option value="{{ $doctor->id }}" {{ $visit_detail->doctor?->id == $doctor->id ? 'selected' : '' }}>
                                    {{ $doctor->doctor_name }} ({{ $doctor->specialist }}), {{ $doctor->hospital_name }} - {{ $doctor->hospital_type }}
                                 </option>
                                 @endforeach
                              </select>
                              @error('doctor_id')
                                 <small class="text-danger">{{ $message }}</small>
                              @enderror
                           </div>
                        </div>
                          <!--Villages-->
                                <div class="col-md-8 visit-extra visit-villages" style="display:none;">
                                    <div class="form-group">
                                        <label>Villages</label>
                                        <textarea name="villages" class="form-control" rows="3"
                                            placeholder="Enter village, pin code, contacts of sarpanch, panch, important person of village, designation">{{ old('villages',$visit_detail->villages) }}</textarea>
                                        @error('villages')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!--City-->
                                <div class="col-md-8 visit-extra visit-city" style="display:none;">
                                    <div class="form-group">
                                        <label>City</label>
                                        <textarea name="city" class="form-control" rows="3"
                                            placeholder="Enter city, sector/ward, important person">{{ old('city',$visit_detail->city) }}</textarea>
                                        @error('city')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!--Societies-->
                                <div class="col-md-8 visit-extra visit-societies" style="display:none;">
                                    <div class="form-group">
                                        <label>Societies</label>
                                        <textarea name="societies" class="form-control" rows="3"
                                            placeholder="Enter societies, contacts of past or present members, important persons">{{ old('societies',$visit_detail->societies) }}</textarea>
                                        @error('societies')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!--NGO-->
                                <div class="col-md-8 visit-extra visit-ngo" style="display:none;">
                                    <div class="form-group">
                                        <label>NGO</label>
                                        <textarea name="ngo" class="form-control" rows="3"
                                            placeholder="Enter ngo of the area, social activist, contact number">{{ old('ngo',$visit_detail->ngo) }}</textarea>
                                        @error('ngo')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                    <!--Religious Places-->
                                <div class="col-md-8 visit-extra visit-religious_places" style="display:none;">
                                    <div class="form-group">
                                        <label>Religious Place</label>
                                        <textarea name="religious_place_name" class="form-control" rows="3"
                                            placeholder="Enter religious places, contacts">{{ old('religious_place_name',$visit_detail->religious_place) }}</textarea>
                                        @error('religious_place_name')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>



                                <!--Other-->
                                <div class="col-md-8 visit-extra visit-other" style="display:none;">
                                    <div class="form-group">
                                        <label>Other Visit Details</label>
                                        <input type="text" name="other_visit_details" class="form-control"
                                            value="{{ old('other_visit_details',$visit_detail->other_visit) }}" placeholder="Enter details">
                                        @error('other_visit_details')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>

                                <!--School-->
                                <div class="col-md-8 visit-extra visit-school" style="display:none;">
                                    <div class="form-group">
                                        <label>School Type</label>
                                        <select name="school_type" class="form-control">
                                            <option value="">Please Select</option>
                                            <option value="Government" {{ old('school_type', $visit_detail->school_type) == 'Government' ? 'selected' : '' }}>Government</option>
                                            <option value="Private" {{ old('school_type', $visit_detail->school_type) == 'Private' ? 'selected' : '' }}>Private</option>
                                            <option value="Play" {{ old('school_type', $visit_detail->school_type) == 'Play' ? 'selected' : '' }}>Play School</option>
                                            <option value="Other" {{ old('school_type', $visit_detail->school_type) == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                        @error('school_type')
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
<script>
document.addEventListener("DOMContentLoaded", function() {
    const visitType = document.getElementById("visit_type");
    const allExtras = document.querySelectorAll(".visit-extra");

    function toggleFields() {
        allExtras.forEach(el => el.style.display = "none");
        if (visitType.value) {
            const target = document.querySelector(".visit-" + visitType.value);
            if (target) target.style.display = "block";
        }
    }

    visitType.addEventListener("change", toggleFields);
    toggleFields(); 
});
</script>
@endsection