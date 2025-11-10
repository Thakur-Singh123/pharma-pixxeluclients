@extends('vendor.layouts.master')
@section('content')
<div class="container">
  <div class="page-inner">

    {{-- Date Filter --}}
    <div class="mb-4 d-flex align-items-center gap-2">
        <label class="me-2">Date Filter:</label>
        <form id="filterForm" method="GET" action="{{ route('vendor.dashboard') }}">
            <select name="date_range" class="form-select" style="width: 180px" onchange="this.form.submit()">
                <option value="today" {{ request('date_range')=='today' ? 'selected' : '' }}>Today</option>
                <option value="this_week" {{ request('date_range')=='this_week' ? 'selected' : '' }}>This Week</option>
                <option value="this_month" {{ request('date_range')=='this_month' ? 'selected' : '' }}>This Month</option>
                <option value="this_year" {{ request('date_range')=='this_year' ? 'selected' : '' }}>This Year</option>
                <option value="all" {{ request('date_range')=='all' ? 'selected' : '' }}>All</option>
            </select>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="dashboard-summary" style="display:flex; gap:20px; flex-wrap:wrap;">
        <div class="summary-card big-card" style="flex:1; background-image: url('{{ asset('public/admin/images/bg.png') }}');">
            <div class="summary-icon"><img src="{{ asset('public/admin/images/Group 34455.png') }}" alt="Pending Icon"></div>
            <div class="summary-text">
                <h2>{{ $pendingCount }}</h2>
                <h5>Pending Orders</h5>
            </div>
        </div>

        <div class="summary-card big-card" style="flex:1; background-image: url('{{ asset('public/admin/images/bg.png') }}');">
            <div class="summary-icon"><img src="{{ asset('public/admin/images/attendance 1.png') }}" alt="Completed Icon"></div>
            <div class="summary-text">
                <h2>{{ $completedCount }}</h2>
                <h5>Completed Orders</h5>
            </div>
        </div>

        <div class="summary-card big-card" style="flex:1; background-image: url('{{ asset('public/admin/images/bg.png') }}');">
            <div class="summary-icon"><img src="{{ asset('public/admin/images/Group.png') }}" alt="Total Approved Icon"></div>
            <div class="summary-text">
                <h2>₹{{ number_format($approvedTotal, 2) }}</h2>
                <h5>Completed Orders Total</h5>
            </div>
        </div>
    </div>

    {{-- Graph --}}
    <div class="mt-4" style="background:#fff; padding:15px; border-radius:12px; box-shadow:0 4px 12px rgba(0,0,0,0.08);">
        <canvas id="ordersChart" height="120"></canvas>
    </div>

  </div>
</div>

{{-- Chart JS --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
const ctx = document.getElementById('ordersChart').getContext('2d');

// Gradients for bars
const gradientPending = ctx.createLinearGradient(0, 0, 0, 150);
gradientPending.addColorStop(0, 'rgba(255, 165, 0, 0.8)');
gradientPending.addColorStop(1, 'rgba(255, 165, 0, 0.3)');

const gradientCompleted = ctx.createLinearGradient(0, 0, 0, 150);
gradientCompleted.addColorStop(0, 'rgba(40, 167, 69, 0.8)');
gradientCompleted.addColorStop(1, 'rgba(40, 167, 69, 0.3)');

const ordersChart = new Chart(ctx, {
    type: 'bar',
    data: {
        labels: ['Orders'], // Single group
        datasets: [
            {
                label: 'Pending',
                data: [{{ $pendingCount }}],
                backgroundColor: gradientPending,
                borderRadius: 10,
                barPercentage: 0.5,
            },
            {
                label: 'Completed',
                data: [{{ $completedCount }}],
                backgroundColor: gradientCompleted,
                borderRadius: 10,
                barPercentage: 0.5,
            }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'top', labels: { font: { size: 14 } } },
            tooltip: {
                backgroundColor: '#fff',
                titleColor: '#333',
                bodyColor: '#333',
                borderColor: '#ddd',
                borderWidth: 1,
                titleFont: { weight: 'bold', size: 14 },
                bodyFont: { size: 13 }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { font: { size: 13 } },
                grid: { color: 'rgba(0,0,0,0.05)' }
            },
            x: {
                ticks: { font: { size: 13 } },
                grid: { display: false }
            }
        }
    }
});
</script>


<style>
.big-card {
    padding: 40px 25px;
    font-size: 1.5rem;
    border-radius: 15px;
    text-align: center;
    box-shadow: 0 5px 20px rgba(0,0,0,0.08);
    transition: transform 0.2s ease;
}
.big-card:hover { transform: translateY(-5px); }
.big-card h2 { font-size: 3rem; margin-bottom:10px; }
.big-card h5 { font-size: 1.2rem; color: #555; }
</style>

@endsection
