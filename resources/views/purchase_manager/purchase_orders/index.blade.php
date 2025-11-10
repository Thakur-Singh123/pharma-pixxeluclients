@extends('purchase_manager.layouts.master')
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
                        </div>

                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover align-middle">
                                    <thead>
                                        <tr>
                                            <th>PO #</th>
                                            <th>Date</th>
                                            <th>Vendor Name</th>
                                            <th>Vendor Email</th>
                                            <th>Nature Of Vendor</th>
                                            <th class="text-end">Subtotal</th>
                                            <th class="text-end">Discount</th>
                                            <th class="text-end">Grand Total</th>
                                            <th>Status</th>
                                            <th style="width:120px;">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($orders as $po)
                                            <tr>
                                                <td>#{{ $po->id }}</td>
                                                <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M, Y') }}</td>
                                                <td> {{ $po->vendor?->name ?? '—' }}</td>
                                                <td> {{ $po->vendor?->email ?? '—' }}</td>
                                                <td>{{ $po->nature_of_vendor }}</td>
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
                                                <td>
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
                                                </td>
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
