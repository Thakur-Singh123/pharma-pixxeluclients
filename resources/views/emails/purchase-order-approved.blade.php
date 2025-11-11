<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Purchase Order Approved</title>
  <style>
    body {
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      background-color: #f4f6fb;
      margin: 0;
      padding: 40px 0;
      color: #333;
    }

    .email-wrapper {
      max-width: 720px;
      margin: 0 auto;
      background: #ffffff;
      border-radius: 16px;
      box-shadow: 0 4px 12px rgba(0,0,0,0.08);
      overflow: hidden;
    }

    .email-header {
      background: linear-gradient(135deg, #2563eb, #1e40af);
      color: #fff;
      text-align: center;
      padding: 25px 15px;
    }

    .email-header h1 {
      margin: 0;
      font-size: 26px;
      letter-spacing: 0.5px;
    }

    .email-body {
      padding: 35px 40px;
    }

    .email-body h2 {
      color: #2563eb;
      font-size: 22px;
      margin-bottom: 12px;
    }

    .email-body p {
      font-size: 15px;
      line-height: 1.7;
      margin-bottom: 14px;
    }

    .details-box {
      background: #f8fafc;
      border-radius: 12px;
      padding: 15px 20px;
      border: 1px solid #e2e8f0;
      margin-top: 18px;
      margin-bottom: 25px;
    }

    .details-box p {
      margin: 8px 0;
      font-size: 15px;
    }

    .highlight {
      color: #0f172a;
      font-weight: 600;
    }

    .status {
      display: inline-block;
      background: #22c55e;
      color: #fff;
      padding: 4px 8px;
      border-radius: 20px;
      font-size: 10px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }

    .email-footer {
      background: #f1f5f9;
      text-align: center;
      padding: 20px 10px;
      font-size: 13px;
      color: #64748b;
      border-top: 1px solid #e2e8f0;
    }

    .email-footer a {
      color: #2563eb;
      text-decoration: none;
      font-weight: 600;
    }
  </style>
</head>
<body>
  <div class="email-wrapper">
    <!-- Header -->
    <div class="email-header">
      <h1>Hospital Purchase Panel</h1>
    </div>

    <!-- Body -->
    <div class="email-body">
      <h2>Purchase Order Approved</h2>
      <p>Hello <strong>{{ $po->vendor->name ?? 'Vendor' }}</strong>,</p>
      <p>We’re pleased to inform you that your Purchase Order has been <strong>approved</strong> by our Manager.</p>

      <div class="details-box">
        <p><span class="highlight">Purchase Order ID:</span> #{{ $po->id }}</p>
        <p><span class="highlight">Order Date:</span> {{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</p>
        <p><span class="highlight">Subtotal:</span> ₹{{ number_format($po->subtotal, 2) }}</p>
        <p><span class="highlight">Discount:</span> ₹{{ number_format($po->discount_total, 2) }}</p>
        <p><span class="highlight">Grand Total:</span> ₹{{ number_format($po->grand_total, 2) }}</p>
        <p><span class="highlight">Status:</span>Approved</p>
      </div>

      <p>Thank you for your continued partnership with <strong>Hospital Purchase Panel</strong>.  
      Our team will reach out soon for delivery coordination or any required follow-up.</p>

      <p style="margin-top: 25px;">Warm regards,<br><strong>Hospital Purchase Panel Team</strong></p>
    </div>

    <!-- Footer -->
    <div class="email-footer">
      © {{ date('Y') }} Hospital Purchase Panel. All rights reserved.<br>
    </div>
  </div>
</body>
</html>
