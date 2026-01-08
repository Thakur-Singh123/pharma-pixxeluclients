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
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                 <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">Events Created By Himself</h4>
                                </div>
                               <div class="delete-card">
    <h2>Delete Account</h2>
    <p>
        This action is permanent and cannot be undone.<br>
        Please enter your password to confirm.
    </p>

    <form method="POST" action="{{ route('account.delete') }}">
        @csrf

        <input type="password" name="password" placeholder="Enter your password" required>

        @error('password')
            <div style="color:red; font-size:12px;">{{ $message }}</div>
        @enderror

        <button type="submit" class="delete-btn">
            Permanently Delete My Account
        </button>
    </form>

    <a href="{{ url()->previous() }}" class="cancel-link">Cancel</a>
</div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
