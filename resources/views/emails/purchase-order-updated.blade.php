<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Purchase Order Updated</title>
  <style>
    body {
      margin: 0;
      padding: 0;
      background-color: #f4f6fb;
      font-family: "Segoe UI", Roboto, Arial, sans-serif;
      color: #333;
    }

    .email-container {
      max-width: 700px;
      margin: 40px auto;
      background-color: #ffffff;
      border-radius: 12px;
      box-shadow: 0 8px 28px rgba(0,0,0,0.08);
      overflow: hidden;
      border: 1px solid #e1e6ec;
    }

    .email-header {
      background: linear-gradient(135deg, #f39c12, #e67e22);
      padding: 30px 25px;
      text-align: center;
      color: #ffffff;
    }

    .email-header .header-text {
      font-size: 24px;
      font-weight: 700;
      letter-spacing: 0.5px;
    }

    .email-body {
      padding: 35px 40px;
      line-height: 1.7;
    }

    .email-body h2 {
      color: #e67e22;
      font-size: 24px;
      margin-bottom: 15px;
      font-weight: 600;
    }

    .email-body p {
      font-size: 15px;
      margin: 10px 0;
    }

    .order-card {
      background: #fdf8f2;
      border-left: 4px solid #e67e22;
      border-radius: 8px;
      padding: 20px 25px;
      margin: 25px 0;
      box-shadow: 0 2px 8px rgba(230, 126, 34, 0.1);
    }

    .order-card p {
      margin: 6px 0;
      font-size: 15px;
    }

    .order-card strong {
      color: #d35400;
    }

    .btn {
      display: inline-block;
      margin-top: 25px;
      padding: 12px 32px;
      background: linear-gradient(135deg, #f39c12, #e67e22);
      color: #fff !important;
      text-decoration: none;
      border-radius: 6px;
      font-weight: 600;
      letter-spacing: 0.4px;
      box-shadow: 0 4px 14px rgba(230,126,34,0.3);
      transition: all 0.3s ease;
    }

    .btn:hover {
      background: linear-gradient(135deg, #e67e22, #d35400);
      box-shadow: 0 6px 16px rgba(230,126,34,0.45);
    }

    .email-footer {
      background: #f4f6fa;
      border-top: 1px solid #e1e6ec;
      padding: 25px 40px;
      font-size: 13px;
      color: #777;
      text-align: center;
    }

    .email-footer strong {
      color: #333;
    }

    @media (max-width: 620px) {
      .email-body, .email-footer, .email-header {
        padding: 20px;
      }

      .btn {
        width: 100%;
        text-align: center;
        display: block;
      }
    }
  </style>
</head>
<body>
  <div class="email-container">

    <!-- HEADER -->
    <div class="email-header">
      <div class="header-text">Hospital Purchase Panel</div>
    </div>

    <!-- BODY -->
    <div class="email-body">
      <h2>Purchase Order Updated</h2>
      <p>Hello <strong>{{ $order->vendor->name }}</strong>,</p>

      <p>
        Your <strong>Purchase Order</strong> has been <strong>updated</strong> by 
        <strong>{{ $order->purchaseManager->name }}</strong>.  
        Please review the updated details below.
      </p>

      <div class="order-card">
        <p><strong>Order ID:</strong> #{{ $order->id }}</p>
        <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($order->order_date)->format('d M Y') }}</p>
        <p><strong>Subtotal:</strong> ₹{{ number_format($order->subtotal, 2) }}</p>
        <p><strong>Discount:</strong> ₹{{ number_format($order->discount_total, 2) }}</p>
        <p><strong>Grand Total:</strong> ₹{{ number_format($order->grand_total, 2) }}</p>
        <p><strong>Notes:</strong> {{ $order->notes ?? 'N/A' }}</p>
      </div>

      <p style="margin-top: 20px;">Warm regards,<br><strong>Hospital Procurement Team</strong></p>

      <!-- Optional CTA -->
      <!-- <a href="#" class="btn">View Purchase Order</a> -->

    </div>

    <!-- FOOTER -->
    <div class="email-footer">
      © {{ date('Y') }} <strong>Hospital</strong>. All Rights Reserved.
    </div>

  </div>
</body>
</html>
