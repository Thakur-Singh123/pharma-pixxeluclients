@extends('counselor.layouts.master')
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
          <img src="{{ asset('public/admin/images/Group.png') }}" alt="Visitors Icon">
        </div>
        <div class="summary-text">
          <h2>{{ number_format($total_visits) }}</h2>
          <h5>Total Visitors</h5>
        </div>
      </div>
      <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
        <div class="summary-icon">
          <img src="{{ asset('public/admin/images/Group 34455.png') }}" alt="Tasks Icon">
        </div>
        <div class="summary-text">
          <h2>{{ number_format($total_completed_task) }}</h2>
          <h5>Tasks Completed</h5>
        </div>
      </div>
      <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
        <div class="summary-icon">
          <img src="{{ asset('public/admin/images/attendance 1.png') }}" alt="Attendance Icon">
        </div>
        <div class="summary-text">
          <h2>{{ number_format($total_attendances) }}%</h2>
          <h5>Attendance</h5>
        </div>
      </div>
      <div class="summary-card" style="background-image: url('{{ asset('public/admin/images/bg.png') }}');">
        <div class="summary-icon"> 
          <img src="{{ asset('public/admin/images/sales 1.png') }}" alt="Sales Icon">
        </div>
        <div class="summary-text"> 
          <h2>{{ number_format($total_sales) }}</h2> 
          <h5>Sales</h5>
        </div>
      </div>
    </div>
    <!--reports-->
    <div class="row dashboard-row">
      <div class="col-lg-6 col-md-12 mb-3">
        <div class="dashboard-card">
          <div class="card-header">Visitors (Monthly)</div>
          <canvas id="chartBar" class="chart-glow"></canvas>
        </div>
      </div>
      <div class="col-lg-6 col-md-12 mb-3">
        <div class="dashboard-card">
          <div class="card-header">Reports (Weekly)</div>
          <canvas id="chartLine" class="chart-glow"></canvas>
        </div>
      </div>
    </div>
    <!--clients-->
    <div class="row dashboard-row">
      <div class="col-md-6 mb-3">
        <div class="dashboard-card">
          <div class="card-header">Clients (Status)</div>
          <canvas id="chartClients" class="chart-glow"></canvas>
        </div>
      </div>
      <!--clients-->
      <div class="col-md-6 mb-3">
        <div class="dashboard-card">
          <div class="card-header">TA / DA (Travel)</div>
          <canvas id="chartTADA" class="chart-glow"></canvas>
        </div>
      </div>
    </div>
  </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const barCtx = document.getElementById('chartBar').getContext('2d');
  const barGradient = barCtx.createLinearGradient(0, 0, 0, 400);
  barGradient.addColorStop(0, 'rgba(75, 192, 192, 0.9)');
  barGradient.addColorStop(1, 'rgba(75, 192, 192, 0.3)');
  
  const lineCtx = document.getElementById('chartLine').getContext('2d');
  const lineGradient = lineCtx.createLinearGradient(0, 0, 0, 400);
  lineGradient.addColorStop(0, 'rgba(255, 99, 132, 0.5)');
  lineGradient.addColorStop(1, 'rgba(255, 99, 132, 0)');
   
  const clientsCtx = document.getElementById('chartClients').getContext('2d');
  const tadaCtx = document.getElementById('chartTADA').getContext('2d');
  
  //Visitors
  new Chart(barCtx, {
    type: 'bar',
    data: {
      labels: [
        @foreach($monthlyData as $v)
          '{{ $v["month"] }}',
        @endforeach
      ],
      datasets: [{
        label: 'Visits',
        data: [
          @foreach($monthlyData as $v)
            {{ $v["total"] }},
          @endforeach
        ],
        backgroundColor: barGradient,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      animation: { 
        duration: 1500 
      },
      plugins: { 
        legend: { 
          display: false 
        } 
      },
        scales: { 
        y: { 
          beginAtZero: true, ticks: { 
            stepSize: 10 
          }, 
          title: { 
            display:true, 
            text:'Visitors Count' 
          } 
        },
      }
    }
  });
  //Reports
  new Chart(lineCtx, {
    type: 'line',
    data: {
      labels: [
        @foreach($weeklyData as $v)
          '{{ \Carbon\Carbon::parse($v["day"])->format("D") }}',
        @endforeach
      ],
      datasets: [{
        label: 'Reports',
        data: [
          @foreach($weeklyData as $v)
            {{ $v["total"] }},
          @endforeach
        ],
        backgroundColor: lineGradient,
        borderColor: 'rgba(255,99,132,0.8)',
        fill: true,
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      animation: { 
        duration: 1500 
      },
      plugins: { 
        legend: { 
          display: false 
        },
        tooltip: { 
          enabled: true, 
          mode: 'index',
          intersect: false, 
          callbacks: {
            label: function(context) {
              return `Reports: ${
                context.parsed.y
              }`;
            }
          }
        }
      },
      interaction: {
        mode: 'nearest',
        axis: 'x',
        intersect: false
      },
      scales: { 
        y: { 
          beginAtZero: true, 
          ticks: { 
            stepSize: 10 
          }, 
          title: { 
            display:true, 
            text:'Reports Count' 
          } 
        },
      }
    }
  });
  //Clients
  new Chart(clientsCtx, {
    type: 'bar',
    data: {
      labels: ['Approved','Pending','Rejected'],
      datasets: [{
        label: 'Clients',
        data: [
          {{ $is_approved ?? 0 }}, 
          {{ $is_pending ?? 0 }},  
          {{ $is_reject ?? 0 }}    
        ],
        backgroundColor: [
          'rgba(75, 192, 75, 0.8)',  
          'rgba(255, 14, 66, 0.93)', 
          'rgba(255, 159, 64, 0.8)'  
        ],
        borderColor: '#fff',
        borderWidth: 2
      }]
    },
    options: {
      responsive: true,
      animation: { 
        duration: 1500 
      },
      plugins: { 
        legend: { 
          display: false
        },
        tooltip: { 
          enabled: true 
        }
      },
      scales: { 
        y: {
          beginAtZero: true, 
          ticks: { 
            stepSize: 10 
          }, 
          title: {
            display:true, text:'Number of Clients' 
          } 
        },
      }
    }
  });
  //TA/DA Travel
  const tadaGradient = tadaCtx.createLinearGradient(0, 0, 0, 400);
  tadaGradient.addColorStop(0, 'rgba(255, 159, 64, 0.9)');
  tadaGradient.addColorStop(1, 'rgba(255, 159, 64, 0.3)');
  //TA/DA
  new Chart(tadaCtx, {
    type: 'bar',
    data: {
      labels: ['Bus','Train','Flight','Car','Bike'],
      datasets: [{
        label: 'Travel Expenses',
        data: [
          {{ $bus ?? 0 }},
          {{ $train ?? 0 }},
          {{ $flight ?? 0 }},
          {{ $car ?? 0 }},
          {{ $bike ?? 0 }},
        ],
        backgroundColor: tadaGradient,
        borderWidth: 1
      }]
    },
    options: {
      responsive: true,
      animation: { 
        duration: 1500 
      },
      plugins: { 
        legend: { 
          display: false 
        } 
      },
      scales: { 
        y: { 
          beginAtZero: true, 
          ticks: {
             stepSize: 10 
            }, 
          title: { 
            display:true, 
            text:'TA/DA Counts' 
          } 
        },
      }
    }
  });
</script>
@endsection