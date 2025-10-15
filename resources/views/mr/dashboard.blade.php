@extends('mr.layouts.master')
@section('content')
<div class="container">
  <div class="page-inner">

    <!-- First Row -->
    <div class="row dashboard-row">
      <!-- Visitors Card -->
      <div class="col-lg-6 col-md-12 mb-3">
        <div class="dashboard-card">
          <div class="card-header">Visitors</div>
          <canvas id="chartBar" class="chart-glow"></canvas>
        </div>
      </div>

      <!-- Reports Card with Buttons -->
      <div class="col-lg-6 col-md-12 mb-3">
        <div class="dashboard-card">
          <div class="card-header d-flex justify-content-between align-items-center">
            {{-- Left side: Reports label --}}
            <div>Reports</div>
            {{-- Right side: Month / Week / Year buttons --}}
            <!-- <div>
              <button class="btn btn-sm btn-primary filter-btn" data-type="week">Week</button>
              <button class="btn btn-sm btn-primary filter-btn" data-type="month">Month</button>
              <button class="btn btn-sm btn-primary filter-btn" data-type="year">Year</button>
            </div> -->
          </div>
          <canvas id="chartLine" class="chart-glow"></canvas>
        </div>
      </div>
    </div>

    <!-- Second Row -->
    <div class="row dashboard-row">
      <!-- Revenue Sources -->
      <div class="col-md-4 mb-3">
        <div class="dashboard-card">
          <div class="card-header">Revenue Sources</div>
          <canvas id="chartDoughnut" class="chart-glow"></canvas>
        </div>
      </div>

      <!-- Orders Distribution -->
      <div class="col-md-4 mb-3">
        <div class="dashboard-card">
          <div class="card-header">Orders Distribution</div>
          <canvas id="chartPie" class="chart-glow"></canvas>
        </div>
      </div>

      <!-- User Activity -->
      <div class="col-md-4 mb-3">
        <div class="dashboard-card">
          <div class="card-header">User Activity</div>
          <canvas id="chartPolar" class="chart-glow"></canvas>
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

  const doughnutColors = ['rgba(75, 192, 192, 0.9)', 'rgba(54, 162, 235, 0.9)', 'rgba(255, 99, 132, 0.9)'];
  const pieColors = ['rgba(153, 102, 255, 0.9)', 'rgba(255, 159, 64, 0.9)', 'rgba(75, 192, 192, 0.9)'];

// Bar Chart (Visitors)
new Chart(barCtx, {
  type: 'bar',
  data: {
    labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
    datasets: [{ 
      label: '', // set empty string to avoid 'undefined'
      data: [12,19,10,15,7,20,10,5,4,2,15,20],
      backgroundColor: barGradient, 
      borderWidth: 1
    }]
  },
  options: { 
    responsive: true, 
    animation: { duration: 1500 },
    plugins: {
      legend: { display: false } // hide legend completely
    }
  }
});


  // Line Chart (Reports) WITHOUT label and borderColor
  // Line Chart (Reports) WITHOUT label and borderColor, no legend
new Chart(lineCtx, {
  type: 'line',
  data: {
    labels: [
      @foreach($DailyReport as $v)
        '{{ \Carbon\Carbon::parse($v['day'])->format("D") }}',
      @endforeach
    ],
    datasets: [{
      data: [
        @foreach($DailyReport as $v)
          {{ $v['total'] }},
        @endforeach
      ],
      backgroundColor: lineGradient,
      fill: true,
      tension: 0.4
    }]
  },
  options: {
    responsive: true,
    animation: { duration: 1500 },
    plugins: {
      legend: { display: false } // ✅ Hide the legend to remove "undefined" color box
    },
    scales: {
      y: { beginAtZero: true, ticks: { stepSize: 1 } }
    }
  }
});


  // Doughnut Chart (Revenue)
  new Chart(document.getElementById('chartDoughnut'), {
    type: 'doughnut',
    data: { 
      labels:['Product A','Product B','Product C'], 
      datasets:[{ data:[300,150,100], backgroundColor:doughnutColors }] 
    },
    options: { responsive:true, animation:{ animateScale:true, animateRotate:true } }
  });

  // Pie Chart (Orders)
  new Chart(document.getElementById('chartPie'), {
    type: 'pie',
    data: { 
      labels:['Online','Offline','Direct'], 
      datasets:[{ data:[120,90,60], backgroundColor:pieColors }] 
    },
    options: { responsive:true, animation:{ animateScale:true } }
  });

  // Polar Area Chart (User Activity)
  new Chart(document.getElementById('chartPolar'), {
    type: 'polarArea',
    data: { 
      labels:['USA','China','Europe','India','Brazil'], 
      datasets:[{ data:[11,16,7,14,9], backgroundColor:doughnutColors }] 
    },
    options: { responsive:true, animation:{ duration:1500 } }
  });

  // Minimal JS for buttons
  document.querySelectorAll('.filter-btn').forEach(btn => {
    btn.addEventListener('click', function() {
      const type = this.dataset.type;
      console.log("Filter clicked:", type);
      // You can later integrate AJAX to reload chart data
    });
  });
</script>
@endsection
