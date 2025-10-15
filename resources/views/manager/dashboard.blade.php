@extends('manager.layouts.master')
@section('content')
<style>
/* ============================
   Dashboard Layout & Styling
============================ */
.dashboard-row {
    margin-bottom: 30px;
}

/* Card Styling */
.dashboard-card {
    border-radius: 15px;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
    padding: 25px;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: center;
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

/* Card Header */
.dashboard-card .card-header {
	font-weight: 700;
	font-size: 14px;
	margin-bottom: 20px;
	color: #34495e;
	text-align: left;
	margin: -15px 0px 0px -10px;
}

/* Remove hover lift effect */
.dashboard-card:hover {
    transform: none;
    box-shadow: 0 6px 20px rgba(0,0,0,0.08);
}

/* Chart Styling */
.dashboard-card canvas {
    height: 260px !important; /* uniform chart height */
    width: 100% !important;
    cursor: pointer;
    transition: filter 0.3s ease;
}

/* Glow effect */
.dashboard-card canvas.chart-glow:hover {
    filter: drop-shadow(0 0 15px rgba(255, 193, 7, 0.6))
            drop-shadow(0 0 20px rgba(255, 193, 7, 0.4));
}

/* Subtle pulsing glow animation */
.chart-glow {
    animation: pulseGlow 3s ease-in-out infinite;
}

@keyframes pulseGlow {
    0% { filter: drop-shadow(0 0 2px rgba(255,255,255,0.1)); }
    50% { filter: drop-shadow(0 0 8px rgba(255,255,255,0.2)); }
    100% { filter: drop-shadow(0 0 2px rgba(255,255,255,0.1)); }
}

/* Responsive Adjustments */
@media (max-width: 1200px) {
    .dashboard-card canvas { height: 220px !important; }
}
@media (max-width: 768px) {
    .dashboard-card canvas { height: 200px !important; }
}
</style>

<div class="container">
    <div class="page-inner">

        {{-- First Row: 2 Charts Full Width --}}
        <div class="row dashboard-row">
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="dashboard-card">
                    <div class="card-header">Reports</div>
                    <canvas id="chartBar" class="chart-glow"></canvas>
                </div>
            </div>
            <div class="col-lg-6 col-md-12 mb-3">
                <div class="dashboard-card">
                    <div class="card-header">Visitors</div>
                    <canvas id="chartLine" class="chart-glow"></canvas>
                </div>
            </div>
        </div>

        {{-- Second Row: 3 Charts --}}
        <div class="row dashboard-row">
            <div class="col-md-4 mb-3">
                <div class="dashboard-card">
                    <div class="card-header">Revenue Sources</div>
                    <canvas id="chartDoughnut" class="chart-glow"></canvas>
                </div>
            </div>
            <div class="col-md-4 mb-3">
                <div class="dashboard-card">
                    <div class="card-header">Orders Distribution</div>
                    <canvas id="chartPie" class="chart-glow"></canvas>
                </div>
            </div>
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
// ======== Gradients ========
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

// ======== Charts ========
new Chart(barCtx, {
    type: 'bar',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{ label: 'Sales', data: [12,19,10,15,7,20,10,05,04,02,15,20],
           backgroundColor: barGradient, borderColor: 'rgba(75,192,192,1)', borderWidth: 1
           }]
    },
    options: { responsive:true, animation:{duration:1500} }
});

new Chart(lineCtx, {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{ label:'Visitors', data:[50,60,40,80,70,90,100], backgroundColor:lineGradient, borderColor:'rgba(255,99,132,1)', borderWidth:2, fill:true, tension:0.4 }]
    },
    options: { responsive:true, animation:{duration:1500} }
});

new Chart(document.getElementById('chartDoughnut'), {
    type: 'doughnut',
    data: { labels:['Product A','Product B','Product C'], datasets:[{ data:[300,150,100], backgroundColor:doughnutColors }] },
    options: { responsive:true, animation:{animateScale:true, animateRotate:true} }
});

new Chart(document.getElementById('chartPie'), {
    type: 'pie',
    data: { labels:['Online','Offline','Direct'], datasets:[{ data:[120,90,60], backgroundColor:pieColors }] },
    options: { responsive:true, animation:{animateScale:true} }
});

new Chart(document.getElementById('chartPolar'), {
    type: 'polarArea',
    data: { labels:['USA','China','Europe','India','Brazil'], datasets:[{ data:[11,16,7,14,9], backgroundColor:doughnutColors }] },
    options: { responsive:true, animation:{duration:1500} }
});
</script>
@endsection
