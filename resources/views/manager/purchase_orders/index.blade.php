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

                                    <form method="GET" action="{{ route('manager.purchase-manager.index') }}"
                                        class="d-flex gap-2 align-items-center">
                                        {{-- Status Filter --}}
                                        <select name="status" class="form-control" onchange="this.form.submit()">
                                            <option value="">All Status</option>
                                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>
                                                Pending</option>
                                            <option value="approved"
                                                {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                            <option value="rejected"
                                                {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                        </select>

                                        {{-- Date Range Filter --}}
                                        <select name="date_range" class="form-control" onchange="this.form.submit()">
                                             <option value="all" {{ request('date_range') == 'all' ? 'selected' : '' }}>
                                                All</option>
                                            <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>
                                                Today</option>
                                            <option value="this_week"
                                                {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week
                                            </option>
                                            <option value="this_month"
                                                {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month
                                            </option>
                                            <option value="this_year"
                                                {{ request('date_range') == 'this_year' ? 'selected' : '' }}>This Year
                                            </option>  
                                        </select>

                                        {{-- Purchase Manager Filter --}}
                                        <select name="purchase_manager_id" class="form-control"
                                            onchange="this.form.submit()">
                                            <option value="">All Purchase Managers</option>
                                            @foreach ($pms as $pm)
                                                <option value="{{ $pm->id }}"
                                                    {{ request('purchase_manager_id') == $pm->id ? 'selected' : '' }}>
                                                    {{ $pm->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                        {{-- Export CSV --}}
                                        <a href="{{ route('manager.purchase-orders.export', request()->only('is_delivered', 'date_range', 'status', 'purchase_manager_id')) }}"
                                            class="btn btn-primary">
                                            Export
                                        </a>
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
                                                                <th>Vendor Name</th>
                                                                <th>Vendor Email</th>
                                                                <th style="width: 220px;">Nature Of Vendor</th>
                                                                <th style="width: 140px;" class="text-end">Subtotal</th>
                                                                <th style="width: 140px;" class="text-end">Discount</th>
                                                                <th style="width: 160px;" class="text-end">Grand Total</th>
                                                                <th style="width: 180px;">Purchase Manager Name</th>
                                                                <th style="width: 180px;">Purchase Manager Email</th>
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
                                                                    <td> {{ $po->vendor?->name ?? '—' }}</td>
                                                                    <td> {{ $po->vendor?->email ?? '—' }}</td>
                                                                    <td>{{ $po->nature_of_vendor }}</td>
                                                                    <td class="text-end">
                                                                        ₹{{ number_format($po->subtotal, 2) }}</td>
                                                                    <td class="text-end">
                                                                        ₹{{ number_format($po->discount_total, 2) }}</td>
                                                                    <td class="text-end fw-semibold">
                                                                        ₹{{ number_format($po->grand_total, 2) }}</td>
                                                                    <td> {{ $po->purchaseManager?->name ?? '—' }}</td>
                                                                    <td> {{ $po->purchaseManager?->email ?? '—' }}</td>
                                                                    <td>
                                                                        <span
                                                                            class="status-badge
                                                                        {{ $po->status === 'pending' ? 'status-pending' : '' }}
                                                                        {{ $po->status === 'approved' ? 'status-approved' : '' }}
                                                                        {{ $po->status === 'rejected' ? 'status-suspend' : '' }}">
                                                                            {{ ucfirst($po->status) }}
                                                                        </span>
                                                                    </td>
                                                                    <td style="display: flex; gap: 5px;">
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
