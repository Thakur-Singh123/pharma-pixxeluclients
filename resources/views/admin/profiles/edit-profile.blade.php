@extends('admin.layouts.master')
@section('content')
<div class="container">
   <div class="page-inner">
      <div class="row">
         <div class="col-md-12">
           <!--success message-->
           @include('admin.notification')
            <div class="card">
               <div class="card-header">
                  <div class="card-title">Edit Profile</div>
               </div>
               <div class="card-body">
                  <form action="{{ route('admin.update.profile', $user_profile->id) }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="avatar-upload">
                        <div class="avatar-edit">
                           <input type="file" name="image" id="imageUpload" accept=".png, .jpg, .jpeg">
                           <label for="imageUpload"><i class="fas fa-pencil-alt"></i></label>
                        </div>
                        <div class="add-new-student-pic">
                           <div class="avatar-preview">
                              @if($user_profile->image)
                                 <img id="imagePreview" src="{{ url('public/uploads/users/' .$user_profile->image) }}" alt="User Avatar">
                              @else
                                 <img id="imagePreview" src="{{ url('public/uploads/users/default.png') }}" alt="Default Avatar">
                              @endif
                           </div>
                        </div>
                     </div>
                     <br>
                     <div class="row">
                        <!--left side column fields-->
                        <div class="col-md-6">
                           <div class="form-group mb-3">
                              <label for="first_name" class="@error('first_name') is-invalid @enderror">First Name</label>
                              <input type="text" name="first_name" class="form-control" value="{{ old('first_name', $user_profile->first_name) }}" placeholder="Enter first name">
                              @error('first_name')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                           <div class="form-group mb-3 ">
                              <label for="email">Email Address</label>
                              <input type="email" class="form-control" value="{{ old('email', $user_profile->email) }}" placeholder="Enter email address" disabled="email">
                           </div>
                           <div class="form-group mb-3">
                              <label for="address"class="@error('address') is-invalid @enderror">Address</label>
                              <input type="text" name="address" class="form-control" value="{{ old('address', $user_profile->address) }}" placeholder="Enter address">
                              @error('address')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                           <div class="form-group mb-3">
                              <label for="mobile" class="@error('mobile') is-invalid @enderror">Mobile</label>
                              <input type="text" name="mobile" id="mobile" class="form-control" value="{{ old('mobile', $user_profile->mobile) }}" placeholder="Enter mobile number">
                              @error('mobile')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                        </div>
                        <!--right side column fields-->
                        <div class="col-md-6">
                           <div class="form-group mb-3">
                              <label for="last_name" class="@error('last_name') is-invalid @enderror">Last Name</label>
                              <input type="text" name="last_name" class="form-control" value="{{ old('last_name', $user_profile->last_name) }}" placeholder="Enter last name">
                              @error('last_name')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                           <div class="form-group mb-3">
                              <label for="dob" class="@error('first_name') is-invalid @enderror">Dob</label>
                              @if ($user_profile->dob)
                                 <input type="date" name="dob" id="dob" class="form-control" value="{{ old('dob', $user_profile->dob) }}">
                              @else 
                                 <input type="date" name="dob" class="form-control" value="{{ \Carbon\Carbon::now()->format('Y-m-d') }}">
                              @endif
                              @error('dob')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                           <div class="form-group mb-3">
                              <label class="@error('gender') is-invalid @enderror">Gender</label>
                              <div class="d-flex gap-4">
                                 <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="male" value="Male"
                                    @if(old('gender', $user_profile->gender) == 'Male') checked @endif>
                                    <label class="form-check-label" for="male">Male</label>
                                 </div>
                                 <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="female" value="Female"
                                    @if(old('gender', $user_profile->gender) == 'Female') checked @endif>
                                    <label class="form-check-label" for="female">Female</label>
                                 </div>
                                 <div class="form-check">
                                    <input class="form-check-input" type="radio" name="gender" id="other" value="Other"
                                    @if(old('gender', $user_profile->gender) == 'Other') checked @endif>
                                    <label class="form-check-label" for="other">Other</label>
                                 </div>
                              </div>
                              @error('gender')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                              <br>
                              <div class="form-group mb-3">
                                 <label for="status">Status</label>
                                 <input type="text" class="form-control" value="{{ $user_profile->status }}" disabled="status">
                              </div>
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
//upload user profile image
document.getElementById("imageUpload").addEventListener("change", function (event) {
   const input = event.target;
   const reader = new FileReader();
   reader.onload = function (e) {
      document.getElementById("imagePreview").src = e.target.result;
   };
   if (input.files && input.files[0]) {
      reader.readAsDataURL(input.files[0]);
   }
});
</script>
@endsection