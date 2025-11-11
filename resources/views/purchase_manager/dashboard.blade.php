@extends('purchase_manager.layouts.master') @section('content') 
<div class="container">
  <div class="page-inner">
    <div class="dashboard-summary">
      <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
        <div class="summary-icon"> <img src="{{ asset('public/admin/images/Group.png') }}" alt="Visitors Icon"> </div>
        <div class="summary-text">
          <h5>Total Orders</h5>
          <h2>{{ $total_orders }}</h2>
        </div>
      </div>
      <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
        <div class="summary-icon"> <img src="{{ asset('public/admin/images/Group 34455.png') }}" alt="Tasks Icon"> </div>
        <div class="summary-text">
          <h5>Delivered</h5>
          <h2>{{ $delivered_orders }}</h2>
        </div>
      </div>
      <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
        <div class="summary-icon"> <img src="{{ asset('public/admin/images/attendance 1.png') }}" alt="Attendance Icon"> </div>
        <div class="summary-text">
          <h5>Approved</h5>
          <h2>{{ $approved_orders }}</h2>
        </div>
      </div>
      <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
        <div class="summary-icon"> <img src="{{ asset('public/admin/images/sales 1.png') }}" alt="Sales Icon"> </div>
        <div class="summary-text">
          <h5>Pending</h5>
          <h2>{{ $pending_orders }}</h2>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection