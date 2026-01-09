<!DOCTYPE html>
<!--Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="en" dir="ltr">
   <head>
      <meta charset="UTF-8">
      <!--<title> Login and Registration Form in HTML & CSS | CodingLab </title>-->
      <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
      <!-- Fontawesome CDN Link -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/css/intlTelInput.css"/>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
   <style>
      span.invalid-feedback {
         color: red;
         font-size: 12px;
      }
      .form-row {
         display: flex;
         gap: 15px;  
         margin-bottom: 15px;
      }
      .form-row .input-box {
         flex: 1; 
      }
      input#phone {
         height: 50px;
      }
      .loaderss.com_ajax_loader {
         margin: -50px 0px 0px 100px;
      }
      .forms .form-content .input-box i {
         margin-top: 0px;
      }
      small.text-danger {
         color: red;
         font-size: 10px;
      }
      .alert.alert-danger {
         color: red;
         font-size: 14px;
      }
      input[type="file"], select {
         width: 100%;
         font-size: 14px;
         color: #333;
         background-color: #fff;
      }
      .input-box label {
         font-weight: 500;
         margin-bottom: 5px;
         color: #444;
      }.signup-header h3 {
         font-weight: 600;
      }
      #backToSignup:hover {
         color: #0056b3;
         transform: scale(1.1);
         transition: 0.2s;
      }
      .forgot-password-wrap {
         display: block;
         text-align: right;
      }

      .forgot-password-link {
         font-size: 12px;
         color: #ff0013;
         text-decoration: none;
         font-weight: 500;
      }
      .account-delete-alert {
         display: flex;
         gap: 10px;
         align-items: flex-start;
         border-radius: 10px;
         padding: 14px 16px;
         font-size: 12px;
         margin-bottom: 100px;
         color: green;
      }
      .success-alert {
         padding: 12px 15px;
         font-size: 12px;
         text-align: center;*/
         color: green;
         margin: 0px 0px 190px 0px;
      }
   </style>
   <body>
      <div class="container">
         <input type="checkbox" id="flip" {{ session('openSignup') ? 'checked' : '' }}>
         <div class="cover">
            <div class="front">
               <img src="{{ asset('public/admin/images/frontImg.jpg') }}" alt="">
               <div class="text">
                  <span class="text-1">Every new friend is a <br> new adventure</span>
                  <span class="text-2">Let's get connected</span>
               </div>
            </div>
            <div class="back">
               <img class="backImg" src="{{ asset('public/admin/images/backImg.jpg') }}" alt="">
               <div class="text">
                  <span class="text-1">Complete miles of journey <br> with one step</span>
                  <span class="text-2">Let's get started</span>
               </div>
            </div>
         </div>
         <div class="forms">
            <div class="form-content">
               <div class="login-form">
                  @if(session('success'))
                     <div class="success-alert account-delete-alert">
                        {{ session('success') }}
                     </div>
                  @endif
                  <div class="title">Login</div>
                  <form method="POST" action="{{ route('login') }}">
                     @csrf
                     <div class="input-boxes">
                        <div class="input-box">
                           <i class="fas fa-envelope"></i>
                           <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" placeholder="Enter email address">
                           @error('email')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                        <div class="input-box">
                           <i class="fas fa-lock"></i>
                           <input id="password" type="password" class="form-control" name="password" placeholder="Enter password">
                              @error('password')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                        <div class="text-end mb-3 forgot-password-wrap">
                           <a href="{{ route('password.request') }}" class="forgot-password-link">
                              Forgot Password?
                           </a>
                        </div>
                        <div class="button input-box">
                           <input type="submit" value="Sumbit">
                        </div>
                        @if (session('error'))
                           <div class="alert alert-danger" id="successMsg" style="margin-top:30px;">
                              {{ session('error') }}
                           </div>
                        @endif
                        <div class="text sign-up-text">Don't have an account? <label for="flip">Sigup now</label></div>
                     </div>
                  </form>
               </div>
               <div class="signup-form">
                  <div class="signup-header" style="display:flex; justify-content: space-between; align-items:center; margin-bottom: 10px;">
                     <h3 id="signupRoleTitle" style="font-size:18px; color:#333; margin:0;">Signup</h3>
                     <span id="backToSignup" style="cursor:pointer; font-size:20px; color:#007bff; display:none;">
                        <i class="fas fa-arrow-left"></i>
                     </span>
                  </div>
                  <form id="signupForm" method="POST" action="{{ route('register') }}" enctype="multipart/form-data" autocomplete="off">
                     @csrf
                     <div class="form-row">
                        <div class="input-box">
                           <i class="fas fa-user"></i>
                           <input id="name" type="text" name="name" value="{{ old('name') }}" placeholder="Enter name">
                           @error('name', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                        <div class="input-box">
                           <i class="fas fa-envelope"></i>
                           <input id="email" type="email" name="email" value="{{ old('email') }}" placeholder="Enter email" autocomplete="new-email">
                           @error('email', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                     </div>
                     <div class="form-row">
                        <div class="input-box">
                           <i class="fas fa-lock"></i>
                           <input id="password" type="password" name="password" value="{{ old('password') }}" placeholder="Enter password" autocomplete="new-password">
                           @error('password', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror                       
                        </div>
                        <div class="input-box">
                           <i class="fas fa-lock"></i>
                           <input id="password-confirm" type="password" name="password_confirmation" value="{{ old('password_confirmation') }}" placeholder="Confirm password">
                        </div>
                     </div>
                     <div class="form-row">
                        <div class="input-box">
                           <i class="fas fa-phone"></i>
                           <input id="phone" type="tel" name="phone" value="{{ old('phone') }}" placeholder="Enter phone">
                           @error('phone', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                        <div class="input-box">
                           <i class="fas fa-city"></i>
                           <input id="city" type="text" name="city" value="{{ old('city') }}" placeholder="Enter city">
                           @error('city', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                     </div>
                     <div class="form-row">
                        <div class="input-box">
                           <i class="fas fa-map"></i>
                           <input id="state" type="text" name="state" value="{{ old('state') }}" placeholder="Enter state">
                           @error('state', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                        <div class="input-box">
                           <i class="fas fa-calendar"></i>
                           <input id="joining_date" type="date" name="joining_date" value="{{ old('joining_date',  now()->format('Y-m-d')) }}" min="{{ now()->format('Y-m-d') }}">
                           @error('joining_date', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                     </div>
                     <!--Vendor Fields-->
                     <div class="vendor-fields" style="display:none;">
                        <div class="form-row">
                           <div class="input-box">
                              <i class="fas fa-briefcase"></i>
                              <input type="text" name="nature_work" placeholder="Enter nature of work" value="{{ old('nature_work') }}">
                           
                           </div>
                        </div>
                     </div>
                   <div class="form-row">
                     <div class="input-box">
                           <input type="file" id="document_file" name="document_file" accept=".jpg,.jpeg,.png,.pdf" style="padding: 10px; border-radius: 5px;">
                           @error('document_file', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                     </div>
                     <div class="form-row role-row">
                        <div class="input-box" style="flex: 1;">
                           <select id="can_sale" name="can_sale" style="height: 50px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                              <option value="" disabled selected>Select type</option>
                              <option value="0" {{ old('can_sale') == '0' ? 'selected' : '' }}>Visit</option>
                              <option value="1" {{ old('can_sale') == '1' ? 'selected' : '' }}>Sales</option>
                           </select>
                           @error('can_sale', 'register')
                              <small class="text-danger">{{ $message }}</small>
                           @enderror
                        </div>
                     </div>
                  </div>
                  <div class="form-row">
                     <div class="input-box">
                        <select id="user_type" name="user_type" style="height: 50px; padding: 10px; border: 1px solid #ccc; border-radius: 5px;">
                           <option value="">Choose role</option>
                           <option value="vendor">Vendor</option>
                           <option value="purchase_manager">Purchase Manager</option>
                           <option value="counsellor">Counsellor</option>
                        </select>
                        @error('user_type', 'register')
                           <small class="text-danger">{{ $message }}</small>
                        @enderror
                     </div>
                  </div>
                  <div class="loaderss com_ajax_loader" style="display:none;">
                     <img src="{{ asset('public/admin/images/200w.gif') }}">
                  </div>
                  <div class="button input-box">
                     <input type="submit" id="submitBtn" value="Submit">
                  </div>
                  @if(session('success'))
                  <div id="successMsg" style="display:none; color: green; margin-top: 30px;">
                     {{ session('success') }}
                  </div>
                  @endif
                  <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
               </form>
               </div>
            </div>
         </div>
      </div>
   </body>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/intlTelInput.min.js"></script>
   <script>
      document.getElementById("name").addEventListener("input", function () {
        this.value = this.value.replace(/[^A-Za-z\s]/g, ""); 
      });
   </script>
   <script>
      const phoneInputField = document.querySelector("#phone");
      const phoneInput = window.intlTelInput(phoneInputField, {
         initialCountry: "in",
         preferredCountries: ["in", "us", "gb"],
         utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js"
      });

      phoneInputField.addEventListener("input", function () {
         let dialCode = "+" + phoneInput.getSelectedCountryData().dialCode + "  ";
         if (!this.value.startsWith(dialCode)) {
            this.value = dialCode + this.value.replace(/[^0-9]/g, "");
         } else {
            let afterCode = this.value.slice(dialCode.length).replace(/[^0-9]/g, "");
            this.value = dialCode + afterCode;
         }
      });

      phoneInputField.addEventListener("countrychange", function () {
         const countryData = phoneInput.getSelectedCountryData();
         const dialCode = "+" + countryData.dialCode + "  ";
         this.value = dialCode;
      });

      const defaultCountry = phoneInput.getSelectedCountryData();
      phoneInputField.value = "+" + defaultCountry.dialCode + "  ";
   </script>
   <script>
      document.getElementById("signupForm").addEventListener("submit", function(e){
         e.preventDefault();
         document.getElementById("submitBtn").style.display = "none";
         document.querySelector(".loaderss").style.display = "block";
         setTimeout(function(){
            e.target.submit();
         }, 3000);
      });
      window.addEventListener("load", function(){
         let successBox = document.getElementById("successMsg");
         if(successBox){
            successBox.style.display = "block";
         }
      });
      window.addEventListener("load", function(){
         let successBox = document.getElementById("successMsg");
         if(successBox){
            setTimeout(function(){
               successBox.style.display = "none";
            }, 20000);
         }
      });
   </script>
   <script>
      document.querySelectorAll("input").forEach(function(inputField) {
         inputField.addEventListener("input", function() {
            let error = this.parentElement.querySelector("small.text-danger");
   
            if(!error){
               let next = this.parentElement.nextElementSibling;
               if(next && next.tagName === "SMALL" && next.classList.contains("text-danger")){
                     error = next;
               }
            }
            if(error){
               error.style.display = "none";
            }
         });
      });
   </script>










<script>
document.getElementById("user_type").addEventListener("change", function() {
   let selected = this.value;

   // Hide all custom field groups
   document.querySelectorAll(".vendor-fields, .purchase-fields, .counsellor-fields")
      .forEach(div => div.style.display = "none");

   // Elements
   let roleRow = document.querySelector(".role-row");
   let header = document.querySelector(".signup-header");
   let headerTitle = document.getElementById("signupRoleTitle");
   let backArrow = document.getElementById("backToSignup");

   // Hide can_sale row
   roleRow.style.display = "none";

   // Show arrow only when user selects a type
   backArrow.style.display = "inline";

if (selected === "vendor") {
   document.querySelector(".vendor-fields").style.display = "flex";
   headerTitle.textContent = "Signup - Vendor";
} else if (selected === "purchase_manager") {
   headerTitle.textContent = "Signup - Purchase Manager";
} else if (selected === "counsellor") {
   headerTitle.textContent = "Signup - Counsellor";


   } else {
      headerTitle.textContent = "Signup";
      backArrow.style.display = "none";
      roleRow.style.display = "flex";
   }
});

// Click back arrow to reset to default signup
document.getElementById("backToSignup").addEventListener("click", function() {
   const roleRow = document.querySelector(".role-row");
   const userTypeSelect = document.getElementById("user_type");
   const headerTitle = document.getElementById("signupRoleTitle");
   const backArrow = document.getElementById("backToSignup");

   // Reset visibility
   document.querySelectorAll(".vendor-fields, .purchase-fields, .counsellor-fields")
      .forEach(div => div.style.display = "none");

   headerTitle.textContent = "Signup";
   backArrow.style.display = "none";
   roleRow.style.display = "flex";
   userTypeSelect.value = "";
});
</script>



<script>
window.addEventListener("load", function() {
   let selectedType = "{{ session('selected_user_type') }}";

   if (selectedType) {
      const userTypeSelect = document.getElementById("user_type");
      userTypeSelect.value = selectedType;
      userTypeSelect.dispatchEvent(new Event("change"));
   }
});
</script>
<script>
   setTimeout(() => {
      const alertBox = document.querySelector('.account-delete-alert');
      if(alertBox){
         alertBox.style.display = 'none';
      }
   }, 8000);
</script>
</html>