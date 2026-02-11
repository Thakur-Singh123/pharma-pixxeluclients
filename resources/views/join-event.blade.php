<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Join Event</title>
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
<style>
* { box-sizing: border-box; margin: 0; padding: 0; }
body {
    font-family: 'Inter', Arial, sans-serif;
    background: linear-gradient(135deg, #6366f1, #3b82f6);
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
}
.container {
    background: rgba(255,255,255,0.95);
    backdrop-filter: blur(12px);
    border-radius: 20px;
    max-width: 600px;
    width: 100%;
    padding: 25px 30px;
    box-shadow: 0 15px 35px rgba(0,0,0,0.2);
}
h2 {
    text-align: center;
    font-size: 26px;
    color: #1e293b;
    margin-bottom: 20px;
    font-weight: 700;
}
.event-info {
    background: #f3f4f6;
    padding: 16px 20px;
    border-radius: 12px;
    margin-bottom: 20px;
    border: 1px solid #d1d5db;
}
.event-info h3 {
    font-size: 18px;
    margin-bottom: 12px;
    font-weight: 700;
    color: #1f2937;
}
.info-row {
    display: flex;
    justify-content: space-between;
    margin-bottom: 6px;
}
.info-row strong {
    flex: 0 0 100px;
    font-weight: 600;
    color: #334155;
}
.info-row span {
    flex: 1;
    color: #111827;
    font-weight: 500;
}
.success {
    background: #d1fae5;
    color: #065f46;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 16px;
    text-align: center;
}
.error {
    background: #fee2e2;
    color: #b91c1c;
    padding: 10px 12px;
    border-radius: 8px;
    margin-bottom: 16px;
}
.form-row {
    display: flex;
    gap: 12px;
    margin-bottom: 12px;
}
.form-row input {
    flex: 1;
}
input, textarea {
    width: 100%;
    padding: 12px 14px;
    border-radius: 10px;
    border: 1px solid #d1d5db;
    font-size: 14px;
    background: #f9fafb;
    transition: all 0.3s;
}
input:focus, textarea:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.25);
    background: #fff;
}
textarea { min-height: 60px; resize: vertical; }
.checkbox-wrapper {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 16px;
}
.checkbox-wrapper input[type="checkbox"] {
    accent-color: #3b82f6;
    width: 18px;
    height: 18px;
}
button {
    width: 100%;
    padding: 14px;
    border-radius: 10px;
    border: none;
    font-weight: 600;
    font-size: 15px;
    background: linear-gradient(90deg, #3b82f6, #2563eb);
    color: #fff;
    cursor: pointer;
    transition: all 0.3s;
    box-shadow: 0 6px 18px rgba(59,130,246,0.35);
}
button:hover {
    background: linear-gradient(90deg,#2563eb,#1e40af);
    transform: translateY(-1px);
}
button:active { transform: scale(0.97); }
@media(max-width:600px){
    .form-row { flex-direction: column; }
}
</style>
</head>
<body>
<div class="container">
    <h2>Join Event</h2>
    <div class="event-info">
        <h3>Event Details</h3>
        <div class="info-row"><strong>Title:</strong><span>{{ $event->title }}</span></div>
        <div class="info-row"><strong>Doctor:</strong><span>{{ $event->doctor_detail->doctor_name ?? 'N/A' }}</span></div>
        <div class="info-row"><strong>Area:</strong><span>{{ $event->doctor_detail->area_name ?? 'N/A' }}, {{ $event->doctor_detail->district ?? 'N/A' }}, {{ $event->doctor_detail->state ?? 'N/A' }}</span></div>
        <div class="info-row"><strong>Area Code:</strong><span>{{ $event->doctor_detail->area_code ?? 'N/A' }}</span></div>
    </div>

    @if(session('success')) <div class="success">{{ session('success') }}</div> @endif
    @if($errors->any()) <div class="error">@foreach($errors->all() as $error)<p>- {{ $error }}</p>@endforeach</div> @endif

    <form method="POST" action="{{ url('/join-event/' . $event->id) }}">
        @csrf
        <div class="form-row">
            <input type="text" name="name" placeholder="Enter Name" required>
        </div>
        <div class="form-row">
            <input type="email" name="email" placeholder="Enter Email" required>
            <input type="text" name="age" placeholder="Enter Age" required>
        </div>
        <div class="form-row">
            <input type="text" name="sex" placeholder="Enter Sex" required>
            <input type="number" name="phone" placeholder="Enter Phone Number" required>
        </div>
        <div class="form-row">
            <input type="number" name="pin_code" placeholder="Enter Pin Code" required>
        </div>
        <textarea name="disease" placeholder="Any weakness, IVF, Allergy, Asthma, Diabetes, Heart problems, High BP, body pains etc (optional)"></textarea>
        <textarea name="address" placeholder="Enter Address"></textarea>
         <div class="checkbox-wrapper">
            <input type="checkbox" name="health_declare" value="1" required>
            <label>I declare that I am fit to participate in this activity at my own risk.</label>
        </div>
        <button type="submit">Register</button>
    </form>
</div>
</body>
</html>
