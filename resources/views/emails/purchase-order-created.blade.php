<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Purchase Order Notification</title>
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
        box-shadow: 0 8px 28px rgba(0, 0, 0, 0.08);
        overflow: hidden;
        border: 1px solid #e1e6ec;
    }
    .email-header {
        background: linear-gradient(135deg, #4a90e2, #0052cc);
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
        color: #1a73e8;
        font-size: 24px;
        margin-bottom: 15px;
        font-weight: 600;
    }
    .email-body p {
        font-size: 15px;
        margin: 10px 0;
    }
    .order-card {
        background: #f9fbff;
        border-left: 4px solid #1a73e8;
        border-radius: 8px;
        padding: 20px 25px;
        margin: 25px 0;
        box-shadow: 0 2px 8px rgba(0, 91, 234, 0.1);
    }
    .order-card p {
        margin: 6px 0;
        font-size: 15px;
    }
    .order-card strong {
        color: #0d47a1;
    }
    .btn {
        display: inline-block;
        margin-top: 25px;
        padding: 12px 32px;
        background: linear-gradient(135deg, #4a90e2, #0052cc);
        color: #fff !important;
        text-decoration: none;
        border-radius: 6px;
        font-weight: 600;
        letter-spacing: 0.4px;
        box-shadow: 0 4px 14px rgba(0, 91, 234, 0.3);
        transition: all 0.3s ease;
    }
    .btn:hover {
        background: linear-gradient(135deg, #0052cc, #003b99);
        box-shadow: 0 6px 16px rgba(0, 91, 234, 0.45);
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
        <div class="email-header">
            <div class="header-text">Hospital Purchase Panel</div>
        </div>
        <div class="email-body">
            <h2>Purchase Order Created Successfully</h2>
            <p>Hello <strong>{{ $po->vendor->name }}</strong>,</p>
            <p>
                A new <strong>Purchase Order</strong> has been successfully created by 
                <strong>{{ $po->purchaseManager->name }}</strong>.  
                Please review the details of your order below.
            </p>
            <div class="order-card">
                <p><strong>Order ID:</strong> #{{ $po->id }}</p>
                <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</p>
                <p><strong>Subtotal:</strong> ₹{{ number_format($po->subtotal, 2) }}</p>
                <p><strong>Discount:</strong> ₹{{ number_format($po->discount_total, 2) }}</p>
                <p><strong>Grand Total:</strong> ₹{{ number_format($po->grand_total, 2) }}</p>
                <p><strong>Notes:</strong> {{ $po->notes ?? 'N/A' }}</p>
            </div>
            <p style="margin-top: 20px;">Warm regards,<br><strong>Hospital Procurement Team</strong></p>
        </div>
        <div class="email-footer">
            © {{ date('Y') }} <strong>Hospital</strong>. All Rights Reserved.
        </div>

    </div>
</body>
</html>
