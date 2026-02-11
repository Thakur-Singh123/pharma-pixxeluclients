@extends('counselor.layouts.master')
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
                                <div class="card-header">
                                    <h4 class="card-title">All Patient Bookings</h4>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <div id="basic-datatables_wrapper"
                                            class="dataTables_wrapper container-fluid dt-bootstrap4">
                                            <div class="row">
                                                <div class="col-sm-12">
                                                    <table id="basic-datatables" class="display table table-striped table-hover dataTable" role="grid">
                                                        <thead>
                                                            <tr role="row">
                                                                <th>Sr No.</th>
                                                                <th>Patient Name</th>
                                                                <th>Mobile No.</th>
                                                                <th>Mail ID</th>
                                                                <th>Department</th>
                                                                <th>UHID No.</th>
                                                                <th>Booking Amount</th>
                                                                <th>Remark</th>
                                                                <th>Booking Status</th>
                                                                <th>Booking Date</th>
                                                                <th>Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1; @endphp
                                                            @forelse ($patients as $patient)
                                                                <tr>
                                                                    <td>{{ $count++ }}.</td>
                                                                    <td>{{ $patient->patient_name }}</td>
                                                                    <td>{{ $patient->mobile_no }}</td>
                                                                    <td>{{ $patient->email ?? '—' }}</td>
                                                                    <td>
                                                                        {{ $patient->department }}
                                                                        @if ($patient->department == 'Others' && !empty($patient->other_department))<br>
                                                                            <small
                                                                                class="text-muted">({{ $patient->other_department }})
                                                                            </small>
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
                                                                    <td>
                                                                        <form
                                                                            action="{{ route('counselor.bookings.status', $patient->id) }}"
                                                                            method="POST" class="d-inline">
                                                                            @csrf
                                                                            @method('PATCH')
                                                                            <select name="booking_done"
                                                                                class="custom-status-dropdown"
                                                                                onchange="this.form.submit()">
                                                                                <option value="yes" {{ $patient->booking_done == 'yes' ? 'selected' : '' }}>Yes</option>
                                                                                <option value="no" {{ $patient->booking_done == 'no' ? 'selected' : '' }}>No</option>
                                                                                <option value="on_hold" {{ $patient->booking_done == 'on_hold' ? 'selected' : '' }}>On Hold</option>
                                                                            </select>
                                                                        </form>
                                                                    </td>
                                                                    <td>{{ $patient->booking_date ?? '—' }}</td>
                                                                    <td>
                                                                        <div class="form-button-action">
                                                                            <a href="{{ route('counselor.bookings.edit', $patient->id) }}"
                                                                                class="icon-button edit-btn custom-tooltip"
                                                                                data-tooltip="Edit">
                                                                                <i class="fa fa-edit"></i>
                                                                            </a>
                                                                            <form
                                                                                action="{{ route('counselor.bookings.destroy', $patient->id) }}"
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
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                            @empty
                                                                <tr>
                                                                    <td colspan="9" class="text-center">
                                                                        No patient record found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    <!--Pagination-->
                                                    {{ $patients->links('pagination::bootstrap-5') }}
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
