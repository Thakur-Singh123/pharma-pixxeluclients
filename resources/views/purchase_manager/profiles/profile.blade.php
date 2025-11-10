@extends('purchase_manager.layouts.master')
@section('content')
<style>
.card-title {
   margin: 10px 0px 10px 10px;
   color: #2a2f5b;
   font-size: 20px;
   font-weight: 600;
   line-height: 1.6;
}
.avatar-edit {
   width: 40px;
   height: 40px;
   background: linear-gradient(135deg, #007a4d, #00b873); 
   border-radius: 50%;
   display: flex;
   justify-content: center;
   align-items: center;
   color: white;
   font-size: 16px;
   cursor: pointer;
   transition: all 0.3s ease-in-out;
   box-shadow: 0px 4px 12px rgba(0, 122, 77, 0.3);
   border: none;
   text-decoration: none;
   position: relative;
}
.avatar-edit:hover {
   transform: translateY(-2px);
   box-shadow: 0px 6px 18px rgba(0, 122, 77, 0.4);
   background: linear-gradient(135deg, #00945f, #00cc88);
   color: #fff;
}
.avatar-edit i {
   pointer-events: none;
}
.card {
   border-radius: 12px;
   box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
   border: none;
   height: 520px;
}
.card-header {
   background-color: #f8f9fa;
   border-bottom: 1px solid #e0e0e0;
   font-size: 20px;
   font-weight: 600;
   padding: 20px 24px;
}
.card-body p {
   font-size: 14px;
   margin-bottom: 12px;
   color: #242222d9;
}
.card-body strong {
   color: #242121;
   font-size: 16px;
}
.card-body .row {
   margin: 0px 0px 0px 100px;
}
.text-center img {
   box-shadow: 0 0 12px rgba(0, 0, 0, 0.1);
   transition: transform 0.3s ease;
}
.text-center img:hover {
   transform: scale(1.05);
}
.status-green {
   color: #28a745;
   font-weight: 800;
}
.custom-tooltip {
   position: relative;
}
.custom-tooltip::after {
   content: 'Edit Profile'; 
   position: absolute;
   bottom: 100%; 
   left: 50%;
   transform: translateX(-50%);
   background-color: #000;
   color: #fff;
   padding: 6px 10px;
   border-radius: 4px;
   white-space: nowrap;
   opacity: 0;
   visibility: hidden;
   font-size: 13px;
   transition: opacity 0.3s;
   z-index: 10;
}
.custom-tooltip:hover::after {
   opacity: 1;
   visibility: visible;
}
</style>
<div class="container">
   <div class="page-inner">
      <div class="row justify-content-center">
         <div class="col-md-10">
            <div class="card">
               <div class="card-header d-flex justify-content-between align-items-center">
                  <div class="card-title">Profile Details</div>
                  <a href="{{ url('purchase-manager/edit-profile') }}" class="avatar-edit custom-tooltip">
                     <i class="fas fa-pencil-alt"></i>
                  </a>
               </div>
               <div class="card-body">
                  <!--check if profile image exists or not-->
                  <div class="text-center mb-4">
                     @if($user_profile->image)
                        <img src="{{ url('public/uploads/users/' . $user_profile->image) }}" alt="User Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                     @else
                        <img src="{{ url('public/uploads/users/default.png') }}" alt="Default Avatar" style="width: 150px; height: 150px; border-radius: 50%; object-fit: cover;">
                     @endif
                  </div>
                  <div class="row">
                     <div class="col-md-6">
                        <p><strong>Name:-</strong> {{ $user_profile->name }}</p>
                        <p><strong>Phone:-</strong> {{ $user_profile->phone }}</p>
                        <p><strong>City:-</strong> {{ $user_profile->city }}</p>
                     </div>
                     <div class="col-md-6">
                        <p><strong>Email Address:-</strong> {{ $user_profile->email }}</p>
                        <p><strong>DOB:-</strong> {{ \Carbon\Carbon::parse($user_profile->dob)->format('d M, Y') }}</p>
                        <p><strong>State:-</strong> {{ $user_profile->state }}</p>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>
@endsection