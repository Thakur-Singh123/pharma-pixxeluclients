<!DOCTYPE html>
<html lang="en">
   <head>
      <meta charset="UTF-8">
      <title>Join Event</title>
      <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
      <style>
         * {
         box-sizing: border-box;
         }
         body {
         font-family: 'Inter', Arial, sans-serif;
         background: linear-gradient(135deg, #6366f1, #3b82f6);
         margin: 0;
         padding: 0;
         display: flex;
         justify-content: center;
         align-items: center;
         min-height: 100vh;
         }
         .container {
         background: rgba(255, 255, 255, 0.9);
         backdrop-filter: blur(12px);
         padding: 40px 35px;
         border-radius: 18px;
         box-shadow: 0 16px 40px rgba(0, 0, 0, 0.15);
         max-width: 520px;
         width: 100%;
         transition: transform 0.3s ease, box-shadow 0.3s ease;
         }
         .container:hover {
         transform: translateY(-6px);
         box-shadow: 0 24px 50px rgba(0, 0, 0, 0.2);
         }
         h2 {
         font-size: 28px;
         font-weight: 700;
         margin-bottom: 28px;
         color: #1e293b;
         text-align: center;
         }
         .event-info {
         background: #f3f4f6;
         padding: 28px;
         border-radius: 14px;
         margin-bottom: 30px;
         font-size: 16px; 
         color: #374151;
         border: 1px solid #d1d5db;
         }
         .event-info h3 {
         font-size: 22px; 
         margin-bottom: 22px;
         color: #1f2937;
         font-weight: 700;
         letter-spacing: 0.5px;
         }
         .info-row {
         display: flex;
         gap: 10px;
         justify-content: center;
         text-align: center;
         font-size: 17px; 
         margin-bottom: 8px; 
         }
         .event-details-center {
         display: flex;
         flex-direction: column;
         gap: 14px;
         width: 100%;
         }
         .info-row {
         display: flex;
         justify-content: space-between;
         align-items: flex-start;
         text-align: left;
         gap: 12px;
         }
         .info-row strong {
         flex: 0 0 120px;   
         color: #334155;
         font-weight: 700;
         font-size: 16px;
         }
         .info-row span {
         flex: 1;  
         color: #111827;
         font-weight: 500;
         font-size: 16px;
         }
         .success {
         color: #065f46;
         background: #d1fae5;
         padding: 14px 16px;
         border-radius: 10px;
         margin-bottom: 20px;
         font-weight: 500;
         text-align: center;
         box-shadow: 0 2px 8px rgba(16, 185, 129, 0.25);
         }
         .error {
         background: #fee2e2;
         color: #b91c1c;
         padding: 14px 16px;
         border-radius: 10px;
         margin-bottom: 20px;
         font-size: 14px;
         box-shadow: 0 2px 8px rgba(239, 68, 68, 0.25);
         }
         input {
         width: 100%;
         padding: 15px 18px;
         margin-bottom: 18px;
         border: 1px solid #d1d5db;
         border-radius: 12px;
         font-size: 15px;
         transition: border 0.3s, box-shadow 0.3s;
         background: #f9fafb;
         }
         input:focus {
         outline: none;
         border-color: #3b82f6;
         box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.25);
         background: #fff;
         }
         button {
         width: 100%;
         padding: 15px;
         font-size: 17px;
         font-weight: 600;
         color: #fff;
         background: linear-gradient(90deg, #3b82f6, #2563eb);
         border: none;
         border-radius: 12px;
         cursor: pointer;
         transition: all 0.3s ease;
         box-shadow: 0 6px 18px rgba(59, 130, 246, 0.35);
         }
         button:hover {
         background: linear-gradient(90deg, #2563eb, #1e40af);
         transform: translateY(-2px);
         }
         button:active {
         transform: scale(0.97);
         }
         @media (max-width: 600px) {
         .container {
         padding: 28px 20px;
         }
         }
      </style>
   </head>
   <body>
      <div class="container">
         <h2>Join Event</h2>
         <!-- Event Info -->
         <div class="event-info">
            <h3>📌 Event Details</h3>
            <div class="event-details-center">
               <div class="info-row">
                  <strong>Title:</strong>
                  <span class="highlight">{{ $event->title }}</span>
               </div>
               <div class="info-row">
                  <strong>Doctor:</strong>
                  <span class="highlight">{{ $event->doctor_detail->doctor_name ?? 'N/A' }}</span>
               </div>
               <div class="info-row">
                  <strong>Area:</strong>
                  <span class="highlight">
                  {{ $event->doctor_detail->area_name ?? 'N/A' }}, 
                  {{ $event->doctor_detail->district ?? 'N/A' }}, 
                  {{ $event->doctor_detail->state ?? 'N/A' }}
                  </span>
               </div>
               <div class="info-row">
                  <strong>Area Code:</strong>
                  <span class="highlight">{{ $event->doctor_detail->area_code ?? 'N/A' }}</span>
               </div>
            </div>
         </div>
         <!-- Success Message -->
         @if(session('success'))
         <div class="success">{{ session('success') }}</div>
         @endif
         <!-- Error Messages -->
         @if($errors->any())
         <div class="error">
            @foreach($errors->all() as $error)
            <p>- {{ $error }}</p>
            @endforeach
         </div>
         @endif
         <!-- Join Form -->
         <form method="POST" action="{{ url('/join-event/' . $event->id) }}">
            @csrf
            <input type="text" name="name" placeholder="Enter Your Name" required>
            <input type="number" name="phone" placeholder="Enter Phone Number" required>
            <button type="submit">Join Event Now 🚀</button>
         </form>
      </div>
   </body>
</html>