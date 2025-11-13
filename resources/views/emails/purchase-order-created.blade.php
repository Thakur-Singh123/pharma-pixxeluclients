<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Purchase Order Notification</title>
        <style>
            body {
                margin: 0;
                padding: 0;
                background-color: #f4f7fb;
                font-family: "Segoe UI", Roboto, Arial, sans-serif;
                color: #333;
            }
            .email-container {
                max-width: 720px;
                margin: 40px auto;
                background-color: #ffffff;
                border-radius: 14px;
                box-shadow: 0 10px 35px rgba(0, 0, 0, 0.08);
                overflow: hidden;
                border: 1px solid #e2e8f0;
            }
            .email-header {
                background: linear-gradient(135deg, #0052cc, #2563eb);
                padding: 32px 25px;
                text-align: center;
                color: #ffffff;
            }
            .email-header .header-text {
                font-size: 26px;
                font-weight: 700;
                letter-spacing: 0.4px;
            }
            .email-body {
                padding: 35px 45px;
                line-height: 1.8;
            }
            .email-body h2 {
                color: #1a73e8;
                font-size: 22px;
                margin-bottom: 18px;
                font-weight: 600;
            }
            .email-body p {
                font-size: 15px;
                margin: 10px 0;
            }
            .order-card {
                background: #f9fbff;
                border: 1px solid #dbe5f7;
                border-radius: 10px;
                padding: 22px 25px;
                margin: 30px 0;
                box-shadow: 0 3px 8px rgba(37, 99, 235, 0.05);
            }
            .order-card p {
                margin: 6px 0;
                font-size: 15px;
            }
            .order-card strong {
                color: #0d47a1;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                margin-top: 18px;
                border-radius: 8px;
                overflow: hidden;
                font-size: 14px;
            }
            thead tr {
                background: linear-gradient(135deg, #2563eb, #0052cc);
                color: #ffffff;
            }
            th, td {
                border: 1px solid #e1e6ec;
                padding: 10px 12px;
                text-align: left;
            }
            tr:nth-child(even) td {
                background-color: #f8fafc;
            }
            .btn {
                display: inline-block;
                margin-top: 25px;
                padding: 12px 32px;
                background: linear-gradient(135deg, #4a90e2, #0052cc);
                color: #fff !important;
                text-decoration: none;
                border-radius: 8px;
                font-weight: 600;
                letter-spacing: 0.4px;
                box-shadow: 0 4px 14px rgba(0, 91, 234, 0.3);
                transition: all 0.3s ease;
            }
            .btn:hover {
                background: linear-gradient(135deg, #0052cc, #003b99);
                box-shadow: 0 6px 18px rgba(0, 91, 234, 0.45);
            }
            .email-footer {
                background: #f8fafc;
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
                <div class="header-text">AdPeople</div>
            </div>
            <div class="email-body">
                <h2>Purchase Order Created Successfully </h2>
                <p>Hello <strong>{{ $po->vendor->name }}</strong>,</p>
                <p>
                A new <strong>Purchase Order</strong> has been successfully created by 
                <strong>{{ $po->purchaseManager->name }}</strong>.
                Please review the order details below.
                </p>
                <div class="order-card">
                <p><strong>Order ID:</strong> #{{ $po->id }}</p>
                <p><strong>Order Date:</strong> {{ \Carbon\Carbon::parse($po->order_date)->format('d M Y') }}</p>
                <p><strong>Nature of Vendor:</strong> {{ $po->nature_of_vendor ?? 'N/A' }}</p>
                <p><strong>Notes:</strong> {{ $po->notes ?? 'N/A' }}</p>
                <h3 style="margin-top: 20px; color: #1a73e8;">Order Items</h3>
                <table>
                    <thead>
                        <tr>
                            <th>Sr. No</th>
                            <th>Product Name</th>
                            <th>Type</th>
                            <th>Quantity</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $count = 1; @endphp
                        @foreach($po->items as $item)
                        <tr>
                            <td>{{ $count ++ }}.</td>
                            <td>{{ $item->product_name }}</td>
                            <td>{{ $item->type ?? '-' }}</td>
                            <td>{{ $item->quantity }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                </div>
                <p style="margin-top: 30px;">Warm regards,<br><strong>AdPeople Team</strong></p>
            </div>
            <div class="email-footer">
                © {{ date('Y') }} <strong>AdPeople</strong>. All Rights Reserved.
            </div>
        </div>
    </body>
</html>