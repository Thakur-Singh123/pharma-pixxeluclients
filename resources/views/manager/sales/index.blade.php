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
                                      <form method="GET" action="{{ route('manager.sales.index') }}">
                                        <select name="created_by" class="form-control" onchange="this.form.submit()">
                                            <option value="">All Sales</option>
                                            @foreach($mrs as $mr) {
                                                <option value="{{ $mr->id }}"{{ request('created_by') == $mr->id ? 'selected' : '' }}>
                                                    {{ $mr->name }}
                                                </option>
                                            }
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
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">Sr No.
                                                                </th>
                                                                <th class="sorting_asc" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1" aria-sort="ascending"
                                                                    style="width: 242.688px;">Company Name
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 366.578px;">Phone Number
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 366.578px;">Address
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Designation
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Doctor Name
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Perscription File
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Salt Name
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Brand Name</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Total Amount</th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Payment Method
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Sales MR
                                                                </th>
                                                                <th class="sorting" tabindex="0"
                                                                    aria-controls="basic-datatables" rowspan="1"
                                                                    colspan="1"
                                                                    style="width: 156.312px;">Action
                                                                </th>
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
                                                                    <td>
                                                                    <!--Check if image exists or not-->
                                                                        @if($event->prescription_file)
                                                                            <a href="{{ asset('public/prescriptions/' . $event->prescription_file) }}" target="_blank">View</a>
                                                                        @else
                                                                            N/A
                                                                        @endif
                                                                    </td>
                                                                    <td>{{ $event['items']['0']['salt_name'] }}</td>
                                                                    <td>{{ $event['items']['0']['brand_name'] }}</td>
                                                                    <td>{{ $event->total_amount }}</td>
                                                                    <td>{{ $event->payment_mode }}</td>
                                                                    <td>{{ $event->user?->name }}</td>
                                                                    <td>
                                                                        <div class="form-button-action">
                                                                            <a href="{{ route('manager.sales.edit', $event->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                                <i class="fa fa-edit"></i>
                                                                            </a>
                                                                            <form
                                                                                action="{{ route('manager.sales.destroy', $event->id) }}"
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
                                                                    <td colspan="10" class="text-center">
                                                                        No record found
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
