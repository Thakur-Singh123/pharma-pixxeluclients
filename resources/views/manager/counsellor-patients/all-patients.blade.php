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
                                <h4 class="card-title">All Patient Bookings</h4>
                                <form method="GET" action="{{ route('manager.all.cpatients') }}" class="d-flex gap-2 align-items-center">
                                    {{-- Counsellor Filter --}}
                                    <select name="counsellor_id" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Counsellors</option>
                                        @foreach ($counsellors as $counsellor)
                                        <option value="{{ $counsellor->id }}" {{ request('counsellor_id') == $counsellor->id ? 'selected' : '' }}>
                                            {{ $counsellor->name }}
                                        </option>
                                        @endforeach
                                    </select>
                                    {{-- Booking Done Filter --}}
                                    <select name="booking_done" class="form-control" onchange="this.form.submit()">
                                        <option value="">All Bookings</option>
                                        <option value="yes" {{ request('booking_done') == 'yes' ? 'selected' : '' }}>Yes</option>
                                        <option value="no" {{ request('booking_done') == 'no' ? 'selected' : '' }}>No</option>
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
                                                role="grid">
                                                <thead>
                                                    <tr role="row">
                                                        <th>Sr No.</th>
                                                        <th>Patient Name</th>
                                                        <th>Counsellor Name</th>
                                                        <th>Mobile No.</th>
                                                        <th>Mail ID</th>
                                                        <th>Department</th>
                                                        <th>UHID No.</th>
                                                        <th>Booking Amount</th>
                                                        <th>Remark</th>
                                                        <th>Booking Status</th>
                                                        <th>Action</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $count = 1; @endphp
                                                    @forelse ($patients as $patient)
                                                    <tr>
                                                        <td>{{ $count++ }}.</td>
                                                        <td>{{ $patient->patient_name }}</td>
                                                        <td>{{ $patient->counsellor->name ?? 'N/A' }}</td>
                                                        <td>{{ $patient->mobile_no }}</td>
                                                        <td>{{ $patient->email ?? '—' }}</td>
                                                        <td>
                                                            {{ $patient->department }}
                                                            @if ($patient->department == 'Others' && !empty($patient->other_department))
                                                            <br>
                                                            <small
                                                            class="text-muted">({{ $patient->other_department }})</small>
                                                            @endif
                                                        </td>
                                                        <td>{{ $patient->uhid_no ?? '—' }}</td>
                                                        <td>
                                                            @if ($patient->booking_amount)
                                                            ₹{{ number_format($patient->booking_amount, 2) }}
                                                            @else
                                                            —
                                                            @endif
                                                        </td>
                                                        <td>{{ $patient->remark ?? '—' }}</td>
                                                        <td>{{ $patient->booking_done ?? '—' }}</td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <a href="{{ url('manager/counsellor-booking-edit/' . $patient->id) }}"
                                                                    class="icon-button edit-btn custom-tooltip"
                                                                    data-tooltip="Edit">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <a href="{{ url('manager/counsellor-booking-delete/' . $patient->id) }}"
                                                                    class="icon-button delete-btn custom-tooltip"
                                                                    data-tooltip="Delete">
                                                                    <i class="fa fa-trash"></i>
                                                                </a>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="9" class="text-center">No patient record
                                                            found
                                                        </td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                                </table>
                                                <!-- Pagination -->
                                                {{ $patients->links('pagination::bootstrap-5') }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- card-body -->
                        </div>
                        <!-- card -->
                    </div>
                    <!-- col -->
                </div>
                <!-- row -->
            </div>
        </div>
    </div>
</div>
@endsection