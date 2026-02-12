@extends('purchase_manager.layouts.master')
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
                                <form method="GET" action="{{ route('purchase-manager.purchase-orders.index') }}" class="d-flex gap-2 align-items-center">
                                    {{--Status Filter--}}
                                    <select name="status" class="form-control" onchange="if(this.value==''){ window.location='{{ route('purchase-manager.purchase-orders.index') }}'; } else { this.form.submit(); }">
                                        <option value="" disabled selected>Select Status</option>
                                        <option value="">All Status</option>
                                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                                        <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                                        <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                                    </select>
                                    <input type="date"
                                        name="order_date"
                                        class="form-control"
                                        value="{{ request('order_date') }}"
                                        onchange="this.form.submit()"
                                    >
                                    {{--Date Range--}}
                                    <select name="date_range" class="form-control" onchange="this.form.submit()">
                                        <option value="all" {{ request('date_range') == 'all' ? 'selected' : '' }}>All</option>
                                        <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Today</option>
                                        <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>This Week</option>
                                        <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>This Month</option>
                                        <option value="this_year" {{ request('date_range') == 'this_year' ? 'selected' : '' }}>This Year</option>
                                    </select>
                                    {{--Vendor Filter--}}
                                    <select name="vendor_id" class="form-control" onchange="this.form.submit()">
                                        <option value="" disabled selected>Select Vendor</option>
                                        <option value="">All Vendors</option>
                                        <!--Get vendors-->
                                        @foreach ($vendors as $vendor)
                                        <option value="{{ $vendor->id }}" {{ request('vendor_id') == $vendor->id ? 'selected' : '' }}>
                                            {{ $vendor->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    {{--Export CSV Button --}}
                                    <a href="{{ route('purchase-manager.purchase-orders.export', request()->only('status', 'date_range', 'vendor_id')) }}"
                                        class="btn btn-primary">Export
                                    </a>
                                </form>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="basic-datatables_wrapper"
                                        class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table class="table table-striped table-hover align-middle">
                                                    <thead>
                                                        <tr>
                                                            <th style="width: 80px;">Sr No.</th>
                                                            <th style="width: 120px;">Purchase Order No #</th>
                                                            <th>Order Date</th>
                                                            <th>Vendor Name</th>
                                                            <th>Vendor Email</th>
                                                            <th>Nature Of Vendor</th>
                                                            <th>Status</th>
                                                            <th style="width:120px;">Action</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $count = ($orders->currentPage() - 1) * $orders->perPage() + 1; @endphp
                                                        @forelse ($orders as $po)
                                                        <tr>
                                                            <td>{{ $count++ }}.</td>
                                                            <td>#{{ $po->id }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($po->order_date)->format('d M, Y') }}</td>
                                                            <td> {{ $po->vendor?->name ?? '—' }}</td>
                                                            <td> {{ $po->vendor?->email ?? '—' }}</td>
                                                            <td>{{ $po->nature_of_vendor }}</td>
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
                                                                    @if($po->status != 'approved')
                                                                    <a href="{{ route('purchase-manager.purchase-orders.edit', $po->id) }}"
                                                                        class="icon-button edit-btn custom-tooltip"
                                                                        data-tooltip="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                    @else
                                                                    <a href="{{ route('purchase-manager.purchase-orders.edit', $po->id) }}"
                                                                        class="icon-button view-btn custom-tooltip"
                                                                        data-tooltip="View">
                                                                        <i class="fa fa-eye"></i>
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
                                                            <td colspan="8" class="text-center">No Purchase
                                                                Orders found.
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $orders->appends(request()->query())->links('pagination::bootstrap-5') }} 
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection