<!DOCTYPE html>
<!-- Created by CodingLab |www.youtube.com/c/CodingLabYT-->
<html lang="en" dir="ltr">
   <head>
      <meta charset="UTF-8">
      <!--<title> Login and Registration Form in HTML & CSS | CodingLab </title>-->
      <link rel="stylesheet" href="{{ asset('public/admin/assets/css/style.css') }}">
      <!-- Fontawesome CDN Link -->
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
   </head>
   <style>
      span.invalid-feedback {
      color: red;
      font-size: 12px;
      }
   </style>
   <body>
      <div class="container">
         <input type="checkbox" id="flip">
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
                  <div class="title">Login</div>
                  <form method="POST" action="{{ route('login') }}">
                     @csrf
                     <div class="input-boxes">
                        <div class="input-box">
                           <i class="fas fa-envelope"></i>
                           <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" autofocus>
                        </div>
                        @error('email')
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                        <div class="input-box">
                           <i class="fas fa-lock"></i>
                           <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="current-password">
                           @error('password')
                           <span class="invalid-feedback" role="alert">
                           <strong>{{ $message }}</strong>
                           </span>
                           @enderror
                        </div>
                        <!-- <div class="text"><a href="#">Forgot password?</a></div> -->
                        <div class="button input-box">
                           <input type="submit" value="Sumbit">
                        </div>
                        <!-- <div class="text sign-up-text">Don't have an account? <label for="flip">Sigup now</label></div> -->
                     </div>
                  </form>
               </div>
               <!-- <div class="signup-form">
                  <div class="title">Signup</div>
                  <form method="POST" action="{{ route('register') }}">
                                @csrf
                    <div class="input-boxes">
                      <div class="input-box">
                        <i class="fas fa-user"></i>
                         <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Enter your name">
                  
                                        @error('name')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                      </div>
                      <div class="input-box">
                        <i class="fas fa-envelope"></i>
                         <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email address">
                  
                                        @error('email')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                      </div>
                      <div class="input-box">
                        <i class="fas fa-lock"></i>
                     <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Enter your password">
                  
                                        @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                      </div>
                  
                                    <div class="input-box"> 
                        <i class="fas fa-lock"></i>
                    <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Enter your confirm password">
                      </div>
                      <div class="button input-box">
                        <input type="submit" value="Sumbit">
                      </div>
                      <div class="text sign-up-text">Already have an account? <label for="flip">Login now</label></div>
                    </div>
                  </form>
                  </div> -->
            </div>
         </div>
      </div>
   </body>
</html>