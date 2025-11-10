@extends('counselor.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="dashboard-summary">
                <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
                    <div class="summary-icon">
                        <img src="{{ asset('public/admin/images/sales 1.png') }}" alt="Revenue Icon">
                    </div>
                    <div class="summary-text">
                        <h2>₹{{ number_format($revenue, 2) }}</h2>
                        <h5>Revenue (Booked)</h5>
                    </div>
                </div>

                <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
                    <div class="summary-icon">
                        <img src="{{ asset('public/admin/images/Group.png') }}" alt="Total Bookings">
                    </div>
                    <div class="summary-text">
                        <h2>{{ number_format($total_bookings) }}</h2>
                        <h5>Total Bookings</h5>
                    </div>
                </div>

                <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
                    <div class="summary-icon">
                        <img src="{{ asset('public/admin/images/Group 34455.png') }}" alt="Pending">
                    </div>
                    <div class="summary-text">
                        <h2>{{ number_format($pending_bookings) }}</h2>
                        <h5>Pending Bookings</h5>
                    </div>
                </div>

                {{-- NEW: Repeated Customers --}}
                <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
                    <div class="summary-icon">
                        <img src="{{ asset('public/admin/images/sales 1.png') }}" alt="Repeat Icon">
                    </div>
                    <div class="summary-text">
                        <h2>{{ number_format($repeated_customers) }}</h2>
                        <h5>Repeated Customers</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
