@extends('mr.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">
                {{-- Success Message --}}
                @if (session('success'))
                <div class="alert alert-success">
                {{ session('success') }}
                </div>
                @endif
                <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">All Doctors</h4>
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
                                                    style="width: 242.688px;">S No.
                                                </th>
                                                <th class="sorting_asc" tabindex="0"
                                                    aria-controls="basic-datatables" rowspan="1"
                                                    colspan="1" aria-sort="ascending"
                                                    style="width: 242.688px;">Doctor Name
                                                </th>
                                                <th class="sorting" tabindex="0"
                                                    aria-controls="basic-datatables" rowspan="1"
                                                    colspan="1"
                                                    style="width: 366.578px;">Doctor Contact
                                                </th>
                                                <th class="sorting" tabindex="0"
                                                    aria-controls="basic-datatables" rowspan="1"
                                                    colspan="1"
                                                    style="width: 366.578px;">Location
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $count = 1 @endphp
                                            @foreach ($assignedDoctors as $doctor)
                                            <tr role="row">
                                                <td class="sorting_1">{{ $count++ }}</td>
                                                <td>{{ $doctor->doctor_name }}</td>
                                                <td>{{ $doctor->doctor_contact }}</td>
                                                <td>{{ $doctor->location }}</td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                        </table>
                                        {{ $assignedDoctors->links('pagination::bootstrap-5') }}
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