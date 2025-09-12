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
                                    <h4 class="card-title">All Sales Data</h4>
                                    
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
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">Sr No.</th>
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">Company Name</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 366.578px;">Phone</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 366.578px;">Address</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Designation</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Doctor Name</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Perscription File</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Total Amount</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Discount</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Net Amount</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Payment Method</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Sales MR</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Action</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            @php $count = 1 @endphp
                                                            @forelse ($sales as $event)
                                                                <tr role="row">
                                                                    <td class="sorting_1">{{ $count++ }}.</td>
                                                                    <td>{{ $event->name }}</td>
                                                                    <td>{{ $event->phone }}</td>
                                                                    <td>{{ $event->address }}</td>
                                                                    <td>{{ $event->designation }}</td>
                                                                    <td>{{ $event->doctor_name }}</td>
                                                                    <td>{!! ($event->prescription_file) ? '<a href="' . asset('public/prescriptions/' . $event->prescription_file) . '" target="_blank">View</a>' : 'N/A' !!}</td>
                                                                    <td>{{ $event->total_amount }}</td>
                                                                    <td>{{ $event->discount }}</td>
                                                                    <td>{{ $event->net_amount }}</td>
                                                                    <td>{{ $event->payment_mode }}</td>
                                                                    <td>{{ $event->user?->name }}</td>
                                                                    <td>
                                                                        <div class="form-button-action">
                                                                            <a href="{{ route('manager.sales.edit', $event->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                                <i class="fa fa-edit"></i>
                                                                            </a>
                                                                        </div>
                                                                    </td>
                                                                </tr>
                                                           @empty
                                                                <tr>
                                                                    <td colspan="10" class="text-center">No record found
                                                                    </td>
                                                                </tr>
                                                            @endforelse
                                                        </tbody>
                                                    </table>
                                                    {{ $sales->links('pagination::bootstrap-5') }}
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
