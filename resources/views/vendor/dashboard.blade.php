@extends('vendor.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <!--<div class="card-header d-flex justify-content-between align-items-center">
          <h4 class="card-title">OverViews</h4>
        </div> -->
            <!--boxes section-->
            <div class="dashboard-summary">
                <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
                    <div class="summary-icon">
                        <img src="{{ asset('public/admin/images/Group 34455.png') }}" alt="Pending Icon">
                    </div>
                    <div class="summary-text">
                        <h2>{{ $pendingCount }}</h2>
                        <h5>Pending Orders</h5>
                    </div>
                </div>

                <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
                    <div class="summary-icon">
                        <img src="{{ asset('public/admin/images/attendance 1.png') }}" alt="Approved Icon">
                    </div>
                    <div class="summary-text">
                        <h2>{{ $approvedCount }}</h2>
                        <h5>Approved Orders</h5>
                    </div>
                </div>

                <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
                    <div class="summary-icon">
                        <img src="{{ asset('public/admin/images/Group.png') }}" alt="Rejected Icon">
                    </div>
                    <div class="summary-text">
                        <h2>{{ $rejectedCount }}</h2>
                        <h5>Rejected Orders</h5>
                    </div>
                </div>

                <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
                    <div class="summary-icon">
                        <img src="{{ asset('public/admin/images/Group.png') }}" alt="Total Sales Icon">
                    </div>
                    <div class="summary-text">
                        <h2>₹{{ number_format($approvedTotal, 2) }}</h2>
                        <h5>Approved Orders Total</h5>
                    </div>
                </div>

            </div>
        </div>
    </div>
@endsection
