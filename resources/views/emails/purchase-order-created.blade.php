<!DOCTYPE html>
<html>
<head>
    <title>Purchase Order Created</title>
</head>
<body>
    <h2>Hello {{ $po->vendor->name }},</h2>
    <p>You have received a new Purchase Order from <strong>{{ $po->purchaseManager->name }}</strong>.</p>
    <p><strong>Order ID:</strong> #{{ $po->id }}</p>
    <p><strong>Order Date:</strong> {{ $po->order_date }}</p>
    <p><strong>Total Amount:</strong> ₹{{ number_format($po->grand_total, 2) }}</p>

    <p>Please review and confirm the order in your dashboard:</p>
    <a href="{{ url('vendor/purchase-orders/' . $po->id) }}">View Purchase Order</a>

    <p style="margin-top:20px;">Regards,<br><strong>PharmaPixxelu Team</strong></p>
</body>
</html>
