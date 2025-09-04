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
                  <div class="card-title">Edit Profile</div>
               </div>
               <div class="card-body">
                  <form action="{{ route('mr.update.profile', $user_profile->id) }}" method="POST" enctype="multipart/form-data">
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
                              <label for="name" class="@error('name') is-invalid @enderror">Name</label>
                              <input type="text" name="name" class="form-control" value="{{ old('name', $user_profile->name) }}" placeholder="Enter name">
                              @error('name')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                           <div class="form-group mb-3">
                              <label for="phone" class="@error('phone') is-invalid @enderror">Phone</label>
                              <input type="number" name="phone" id="phone" class="form-control" value="{{ old('phone', $user_profile->phone) }}" placeholder="Enter phone number">
                              @error('phone')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                              <div class="form-group mb-3">
                              <label for="city" class="@error('city') is-invalid @enderror">City</label>
                              <input type="text" name="city" id="city" class="form-control" value="{{ old('city', $user_profile->city) }}" placeholder="Enter city">
                              @error('city')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                           <div class="form-group mb-3">
                              <label for="joining_date" class="@error('joining_date') is-invalid @enderror">Joining Date</label>
                              <input type="date" name="joining_date" id="joining_date" class="form-control" value="{{ old('joining_date', $user_profile->joining_date ?? now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}">
                              @error('joining_date')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                        </div>
                        <!--right side column fields-->
                        <div class="col-md-6">
                           <div class="form-group mb-3 ">
                              <label for="email">Email Address</label>
                              <input type="email" class="form-control" value="{{ old('email', $user_profile->email) }}" placeholder="Enter email address" disabled="email">
                           </div>
                           <div class="form-group mb-3 ">
                              <label for="territory">Territory</label>
                              <input type="text" name="territory" class="form-control" value="{{ old('territory', $user_profile->territory) }}" placeholder="Enter territory">
                           </div>
                           <div class="form-group mb-3">
                              <label for="state" class="@error('state') is-invalid @enderror">State</label>
                              <input type="text" name="state" id="state" class="form-control" value="{{ old('state', $user_profile->state) }}" placeholder="Enter state">
                              @error('state')
                              <span class="invalid-feedback" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                           <div class="form-group mb-3 ">
                              <label for="status">Status</label>
                              <input type="text" class="form-control" value="{{ old('status', $user_profile->status) }}" placeholder="Enter status" disabled="email">
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