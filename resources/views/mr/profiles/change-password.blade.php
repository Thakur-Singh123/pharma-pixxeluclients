@extends('mr.layouts.master')
@section('content')
<div class="container">
   <div class="page-inner">
      <div class="row">
         <div class="col-md-12">
            @if(session('success'))
               <div class="alert alert-success">
                  {{ session('success') }}
               </div>
            @endif
            @if(session('unsuccess'))
               <div class="alert alert-danger">
                  {{ session('unsuccess') }}
               </div>
            @endif
            <div class="card">
               <div class="card-header">
                  <div class="card-title">Update Password</div>
               </div>
               <div class="card-body">
                  <form action="{{ route('mr.submit.change.password', $user_profile->id) }}" method="POST" enctype="multipart/form-data">
                     @csrf
                     <div class="row">
                        <!--left side column fields-->
                        <div class="col-md-6">
                           <div class="form-group mb-3">
                              <label for="name">UserName</label>
                              <input type="text" class="form-control" value="{{ old('name', $user_profile->name) }}" disabled>
                           </div>
                           <div class="form-group mb-3">
                              <label for="password" class="@error('password') is-invalid @enderror">New Password</label>
                              <div class="input-group">
                                 <input type="password" name="password" class="form-control" id="password" placeholder="Enter new password">
                                 <span class="input-group-text toggle-password" toggle="#password"><i class="fas fa-eye"></i></span>
                              </div>
                              @error('password')
                              <span class="invalid-feedback d-block" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
                              @enderror
                           </div>
                        </div>
                        <!--right side column fields-->
                        <div class="col-md-6">
                           <div class="form-group mb-3">
                              <label for="email">Email Address</label>
                              <input type="email" class="form-control" value="{{ old('email', $user_profile->email) }}" disabled>
                           </div>
                           <div class="form-group mb-3">
                              <label for="confirm_password" class="@error('confirm_password') is-invalid @enderror">Confirm Password</label>
                              <div class="input-group">
                                 <input type="password" name="confirm_password" id="confirm_password" class="form-control" placeholder="Enter confirm password">
                                 <span class="input-group-text toggle-password" toggle="#confirm_password"><i class="fas fa-eye"></i></span>
                              </div>
                              @error('confirm_password')
                              <span class="invalid-feedback d-block" role="alert">
                                 <strong>{{ $message }}</strong>
                              </span>
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
<!--password toggle js-->
<script>
   document.querySelectorAll(".toggle-password").forEach(function(toggle) {
      toggle.addEventListener("click", function() {
         const input = document.querySelector(this.getAttribute("toggle"));
         const icon = this.querySelector("i");
         if (input.type === "password") {
            input.type = "text";
            icon.classList.remove("fa-eye");
            icon.classList.add("fa-eye-slash");
         } else {
            input.type = "password";
            icon.classList.remove("fa-eye-slash");
            icon.classList.add("fa-eye");
         }
      });
   });
</script>
@endsection