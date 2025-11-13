@extends('vendor.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Purchase Order Details #{{ $order->id }}</h4>
            </div>
            <div class="card-body">
                {{-- Basic Info --}}
                <div class="row mb-4">
                <div class="col-md-4 mb-2">
                    <h6 class="text-muted">Vendor</h6>
                    <p class="fw-bold">
                        {{ $order->vendor->name }}{{ $order->vendor->email ? ' - '.$order->vendor->email : '' }}
                    </p>
                </div>
                <div class="col-md-4 mb-2">
                    <h6 class="text-muted">Order Date</h6>
                    <p class="fw-bold">{{ \Carbon\Carbon::parse($order->order_date)->format('d M, Y') }}</p>
                </div>
                <div class="col-md-4 mb-2">
                    <h6 class="text-muted">Nature of Vendor</h6>
                    <p class="fw-bold">{{ $order->nature_of_vendor ?? '-' }}</p>
                </div>
                <div class="col-12 mb-2">
                    <h6 class="text-muted">Notes</h6>
                    <p class="fw-bold">{{ $order->notes ?? 'No notes available.' }}</p>
                </div>
                </div>
                <hr>
                {{-- Items Table --}}
                <div class="table-responsive">
                    <table class="table table-bordered align-middle" id="itemsTable">
                        <thead>
                            <tr>
                                <th style="min-width:200px; background-color:#28a745; color:#fff; border-color:#28a745;">Product</th>
                                <th style="min-width:120px; background-color:#28a745; color:#fff; border-color:#28a745;">Type</th>
                                <th style="min-width:100px; background-color:#28a745; color:#fff; border-color:#28a745;">Qty</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->items as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->type ?? '-' }}</td>
                                <td>{{ $item->quantity }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Optional hover effect --}}
<script>
    document.querySelectorAll('table tbody tr').forEach(row => {
        row.addEventListener('mouseenter', () => row.style.backgroundColor = '#f1f7ff');
        row.addEventListener('mouseleave', () => row.style.backgroundColor = '');
    });
</script>
@endsection