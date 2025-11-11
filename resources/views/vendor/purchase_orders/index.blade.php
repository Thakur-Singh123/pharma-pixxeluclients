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

                            <form method="GET" action="{{ route('vendor.purchase-orders.index') }}"
                                class="d-flex gap-2 align-items-center">

                                {{-- Delivery Status Filter --}}
                                <select name="is_delivered" class="form-control" style="width: 160px"
                                    onchange="this.form.submit()">
                                    <option value="">All Delivery</option>
                                    <option value="pending" {{ request('is_delivered') == 'pending' ? 'selected' : '' }}>
                                        Pending</option>
                                    <option value="completed"
                                        {{ request('is_delivered') == 'completed' ? 'selected' : '' }}>Completed</option>
                                </select>

                                {{-- Date Range Filter --}}
                                <select name="date_range" class="form-control" style="width: 180px"
                                    onchange="this.form.submit()">
                                    <option value="">All</option>
                                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today
                                    </option>
                                    <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>
                                        This Week</option>
                                    <option value="this_month"
                                        {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                    <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>
                                        This Year</option>
                                </select>

                                {{-- Export CSV --}}
                                <a href="{{ route('vendor.purchase-orders.export', request()->only('is_delivered', 'date_range')) }}"
                                    class="btn btn-primary">
                                    Export CSV
                                </a>

                            </form>
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>PO #</th>
                                            <th>Date</th>
                                            <th class="text-end">Vendor Name</th>
                                            <th class="text-end">Vendor Email</th>
                                            {{-- <th class="text-end">Items</th> --}}
                                            <!-- <th class="text-end">Subtotal</th>
                                            <th class="text-end">Discount</th>
                                            <th class="text-end">Grand Total</th> -->
                                            <th>Delivery</th>
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
                                                </td>
                                                <td>
                                                    {{ $po->vendor?->email ?? '—' }}
                                                </td>
                                                {{-- <td class="text-end">{{ $po->items_count ?? ($po->items->count() ?? 0) }} --}}
                                                </td>
                                                <!-- <td class="text-end">₹{{ number_format($po->subtotal, 2) }}</td>
                                                <td class="text-end">₹{{ number_format($po->discount_total, 2) }}</td>
                                                <td class="text-end fw-semibold">₹{{ number_format($po->grand_total, 2) }}
                                                </td> -->
                                                <td>
                                                    <form
                                                        action="{{ route('vendor.purchase-orders.update.delivery', $po->id) }}"
                                                        method="POST" class="delivery-form">
                                                        @csrf
                                                        <select name="is_delivered" class="custom-status-dropdown"
                                                            onchange="this.form.submit()">
                                                            <option value="pending"
                                                                {{ $po->is_delivered == 'pending' ? 'selected' : '' }}>
                                                                Pending</option>
                                                            <option value="completed"
                                                                {{ $po->is_delivered == 'completed' ? 'selected' : '' }}>
                                                                Completed</option>
                                                        </select>
                                                    </form>
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
