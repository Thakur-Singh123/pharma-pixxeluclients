@extends('vendor.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="col-md-12">

                    @if (session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="card">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h4 class="card-title mb-0">All Purchase Orders</h4>

                            <form method="GET" action="{{ route('vendor.purchase-orders.index') }}" class="d-flex gap-2 align-items-center">
                                {{-- Status Filter --}}
                                <select name="status" class="form-select" style="width: 160px" onchange="this.form.submit()">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                    <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                </select>

                                {{-- Date Range Filter --}}
                                <input type="date" name="from_date" class="form-control"
                                    value="{{ request('from_date') }}" style="width: 150px" onchange="this.form.submit()">

                                <span class="mx-1">to</span>

                                <input type="date" name="to_date" class="form-control"
                                    value="{{ request('to_date') }}" style="width: 150px" onchange="this.form.submit()">

                                {{-- Reset Button --}}
                                @if(request()->has('from_date') || request()->has('to_date'))
                                    <a href="{{ route('vendor.purchase-orders.index') }}" class="btn btn-outline-secondary btn-sm">Reset</a>
                                @endif
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>PO #</th>
                                            <th>Date</th>
                                            <th>Vendor</th>
                                            <th class="text-end">Items</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-end">Discount</th>
                                            <th class="text-end">Grand Total</th>
                                            <th>Status</th>
                                            {{-- <th style="width:120px;">Action</th> --}}
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $po)
                                            <tr>
                                                <td>#{{ $po->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M, Y') }}</td>
                                                <td>
                                                    {{ $po->vendor?->name ?? '—' }}
                                                    @if ($po->vendor?->email)
                                                        <br><small class="text-muted">{{ $po->vendor->email }}</small>
                                                    @endif
                                                </td>
                                                <td class="text-end">{{ $po->items_count ?? ($po->items->count() ?? 0) }}
                                                </td>
                                                <td class="text-end">₹{{ number_format($po->subtotal, 2) }}</td>
                                                <td class="text-end">₹{{ number_format($po->discount_total, 2) }}</td>
                                                <td class="text-end fw-semibold">₹{{ number_format($po->grand_total, 2) }}
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
                                                {{-- <td>
                                                    <div class="form-button-action">
                                                        @if (Route::has('purchase-manager.purchase-orders.edit'))
                                                            <a href="{{ route('purchase-manager.purchase-orders.edit', $po->id) }}"
                                                                class="icon-button edit-btn custom-tooltip"
                                                                data-tooltip="Edit">
                                                                <i class="fa fa-edit"></i>
                                                            </a>
                                                        @endif

                                                        @if (Route::has('purchase-manager.purchase-orders.destroy'))
                                                            <form
                                                                action="{{ route('purchase-manager.purchase-orders.destroy', $po->id) }}"
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
                                                </td> --}}
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="8" class="text-center">No Purchase Orders found.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>

                            <!-- Pagination -->
                            {{ $orders->withQueryString()->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
@endsection
