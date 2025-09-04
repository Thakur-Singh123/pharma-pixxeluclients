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
                            <div class="card-header">
                                <h4 class="card-title">All Doctors</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="basic-datatables_wrapper"
                                        class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <table id="basic-datatables" class="display table table-striped table-hover dataTable" role="grid" aria-describedby="basic-datatables_info">
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
                                                                style="width: 242.688px;">Area Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 366.578px;">Area Block
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 187.688px;">District
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 84.5px;">State</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 184.234px;">Area Code
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Doctor Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Doctor Contact
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Location
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Remarks
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Attachment
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                aria-label="Salary: activate to sort column ascending"
                                                                style="width: 156.312px;">Status
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" style="width: 156.312px;">Action
                                                            </th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $count = 1 @endphp
                                                        <!--Get doctors-->
                                                        @forelse ($all_doctors as $doctor)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $doctor->area_name }}</td>
                                                            <td>{{ $doctor->area_block }}</td>
                                                            <td>{{ $doctor->district }}</td>
                                                            <td>{{ $doctor->state }}</td>
                                                            <td>{{ $doctor->area_code }}</td>
                                                            <td>{{ $doctor->doctor_name }}</td>
                                                            <td>{{ $doctor->doctor_contact }}</td>
                                                            <td>{{ $doctor->location }}</td>
                                                            <td>{{ $doctor->remarks }}</td>
                                                            <td>
                                                            <!--check if image exists or not-->
                                                            @if ($doctor->picture)
                                                                <a href="{{ asset('public/uploads/doctors/' . $doctor->picture) }}"
                                                                    target="_blank">View
                                                                </a>
                                                            @else
                                                                -
                                                            @endif
                                                            </td>
                                                            <td
                                                                style="color: {{ $doctor->status == 'pending' ? 'orange' : ($doctor->status == 'active' ? 'green' : 'red') }}">
                                                                {{ ucfirst($doctor->status) }}
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    <a href="{{ url('manager/doctors/edit', $doctor->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                    <a href="{{ url('manager/delete-doctor', $doctor->id) }}" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="10" class="text-center">No record found</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $all_doctors->links('pagination::bootstrap-5') }}
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