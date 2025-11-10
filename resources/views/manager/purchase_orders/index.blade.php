@extends('manager.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <h4 class="card-title">All Purchase Orders</h4>

                                    {{-- Filter by Purchase Manager --}}
                                    <form method="GET" action="{{ route('manager.purchase-manager.index') }}">
                                        <select name="purchase_manager_id" class="form-control"
                                            onchange="this.form.submit()">
                                            <option value="">All Purchase Orders</option>
                                            @foreach ($pms as $pm)
                                                <option value="{{ $pm->id }}"
                                                    {{ request('purchase_manager_id') == $pm->id ? 'selected' : '' }}>
                                                    {{ $pm->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </form>
                                </div>

                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div id="basic-datatables_wrapper"
                                            class="dataTables_wrapper container-fluid dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="basic-datatables"
                                                        class="display table table-striped table-hover dataTable"
                                                        role="grid" aria-describedby="basic-datatables_info">
                                                        <thead>
                                                            <tr role="row">
                                                                <th style="width: 80px;">Sr No.</th>
                                                                <th style="width: 120px;">PO #</th>
                                                                <th style="width: 140px;">Date</th>
                                                                <th style="width: 220px;">Vendor</th>
                                                                <th style="width: 220px;">First Item</th>
                                                                <th style="width: 110px;" class="text-end">Items</th>
                                                                <th style="width: 140px;" class="text-end">Subtotal</th>
                                                                <th style="width: 140px;" class="text-end">Discount</th>
                                                                <th style="width: 160px;" class="text-end">Grand Total</th>
                                                                <th style="width: 180px;">Purchase Manager</th>
                                                                <th style="width: 120px;">Status</th>
                                                                <th style="width: 220px;">Approval</th>
                                                                <th style="width: 140px;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = ($orders->currentPage() - 1) * $orders->perPage() + 1; @endphp
                                                            @forelse ($orders as $po)
                                                                <tr role="row">
                                                                    <td>{{ $count++ }}.</td>
                                                                    <td>#{{ $po->id }}</td>
                                                                    <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M, Y') }}
                                                                    </td>
                                                                    <td>
                                                                        {{ $po->vendor?->name ?? '—' }}
                                                                        @if ($po->vendor?->email)
                                                                            <br><small
                                                                                class="text-muted">{{ $po->vendor->email }}</small>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        @php
                                                                            $firstItem = $po->items[0] ?? null;
                                                                        @endphp
                                                                        {{ $firstItem?->product_name ?? '—' }}
                                                                        @if (!empty($firstItem?->type))
                                                                            <br><small
                                                                                class="text-muted">({{ $firstItem->type }})</small>
                                                                        @endif
                                                                    </td>
                                                                    <td class="text-end">
                                                                        {{ $po->items_count ?? ($po->items->count() ?? 0) }}
                                                                    </td>
                                                                    <td class="text-end">
                                                                        ₹{{ number_format($po->subtotal, 2) }}</td>
                                                                    <td class="text-end">
                                                                        ₹{{ number_format($po->discount_total, 2) }}</td>
                                                                    <td class="text-end fw-semibold">
                                                                        ₹{{ number_format($po->grand_total, 2) }}</td>
                                                                    <td>
                                                                        {{ $po->purchaseManager?->name ?? '—' }}
                                                                        @if ($po->purchaseManager?->email)
                                                                            <br><small
                                                                                class="text-muted">{{ $po->purchaseManager->email }}</small>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <span
                                                                            class="status-badge
                                                                        {{ $po->status === 'pending' ? 'status-pending' : '' }}
                                                                        {{ $po->status === 'approved' ? 'status-approved' : '' }}
                                                                        {{ $po->status === 'rejected' ? 'status-suspend' : '' }}">
                                                                            {{ ucfirst($po->status) }}
                                                                        </span>
                                                                    </td>
                                                                    <td style="display: flex; gap: 5px; flex-wrap: wrap;">
                                                                        @if ($po->status === 'pending')
                                                                            <form method="POST"
                                                                                action="{{ route('manager.purchase-manager.approvals.approve', $po->id) }}">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button class="btn btn-success btn-sm">
                                                                                    Approve
                                                                                </button>
                                                                            </form>
                                                                            <form method="POST"
                                                                                action="{{ route('manager.purchase-manager.approvals.reject', $po->id) }}">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button class="btn btn-danger btn-sm">
                                                                                    Reject
                                                                                </button>
                                                                            </form>
                                                                        @elseif($po->status === 'approved')
                                                                            <form method="POST"
                                                                                action="{{ route('manager.purchase-manager.approvals.reject', $po->id) }}">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button class="btn btn-danger btn-sm">
                                                                                    Reject
                                                                                </button>
                                                                            </form>
                                                                        @elseif($po->status === 'rejected')
                                                                            <form method="POST"
                                                                                action="{{ route('manager.purchase-manager.approvals.approve', $po->id) }}">
                                                                                @csrf
                                                                                @method('PATCH')
                                                                                <button class="btn btn-success btn-sm">
                                                                                    Approve
                                                                                </button>
                                                                            </form>
                                                                        @endif
                                                                    </td>
                                                                    <td>
                                                                        <div class="form-button-action">
                                                                            @if (Route::has('manager.purchase-manager.edit'))
                                                                                <a href="{{ route('manager.purchase-manager.edit', $po->id) }}"
                                                                                    class="icon-button edit-btn custom-tooltip"
                                                                                    data-tooltip="Edit">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </a>
                                                                            @endif

                                                                            @if (Route::has('manager.purchase-manager.destroy'))
                                                                                <form
                                                                                    action="{{ route('manager.purchase-manager.destroy', $po->id) }}"
                                                                                    method="POST" style="display:inline;">
                                                                                    @csrf
                                                                                    @method('DELETE')
                                                                                    <a href="#"
                                                                                        class="icon-button delete-btn custom-tooltip"
                                                                                        data-tooltip="Delete"
                                                                                        onclick="event.preventDefault(); this.closest('form').submit();">
                                                                                        <i class="fa fa-trash"></i>
                                                                                    </a>
                                                                                </form>
                                                                            @endif
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="13" class="text-center">No record found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    {{ $orders->links('pagination::bootstrap-5') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div> <!-- card-body -->
                                </div> <!-- card -->
                            </div> <!-- col -->
                        </div> <!-- row -->
                    </div> <!-- page-inner row -->
                </div>
            </div>
        </div>
    </div>
@endsection
