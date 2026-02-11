@extends('mr.layouts.master')
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
                            <h4 class="card-title">All Clients</h4>
                            <form method="GET" action="{{ route('mr.clients.index') }}" class="m-0 d-flex align-items-center" style="gap: 10px;">
                                <!--Category Filter-->
                                <select name="category_type" class="form-control"
                                    onchange="if(this.value==''){ window.location='{{ route('mr.clients.index') }}'; } else { this.form.submit(); }">
                                    <option value="all" disabled selected>Select Category</option>
                                    <option value="">All Categories</option>
                                    <option value="doctor" {{ request('category_type') == 'doctor' ? 'selected' : '' }}>Doctor</option>
                                    <option value="nurse" {{ request('category_type') == 'nurse' ? 'selected' : '' }}>Nurse</option>
                                    <option value="lab_technician" {{ request('category_type') == 'lab_technician' ? 'selected' : '' }}>Lab Technician</option>
                                    <option value="chemist" {{ request('category_type') == 'chemist' ? 'selected' : '' }}>Chemist</option>
                                    <option value="asha_worker" {{ request('category_type') == 'asha_worker' ? 'selected' : '' }}>Asha Worker</option>
                                    <option value="sarpanch" {{ request('category_type') == 'sarpanch' ? 'selected' : '' }}>Sarpanch</option>
                                    <option value="mc" {{ request('category_type') == 'mc' ? 'selected' : '' }}>MC</option>
                                    <option value="franchisee" {{ request('category_type') == 'franchisee' ? 'selected' : '' }}>Franchisee</option>
                                    <option value="healthcare_worker" {{ request('category_type') == 'healthcare_worker' ? 'selected' : '' }}>Any Healthcare Worker</option>
                                    <option value="school" {{ request('category_type') == 'school' ? 'selected' : '' }}>School</option>
                                    <option value="press_reporter" {{ request('category_type') == 'press_reporter' ? 'selected' : '' }}>Press Reporter</option>
                                    <option value="market_president" {{ request('category_type') == 'market_president' ? 'selected' : '' }}>Market President</option>
                                    <option value="others" {{ request('category_type') == 'others' ? 'selected' : '' }}>Others</option>
                                </select>
                                <!--Date Filter-->
                                <input type="date"
                                    name="created_date"
                                    class="form-control"
                                    value="{{ request('created_date') }}"
                                    onchange="this.form.submit()"
                                >
                            </form>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <div id="basic-datatables_wrapper" class="dataTables_wrapper container-fluid dt-bootstrap4">
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
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="basic-datatables" rowspan="1"
                                                            colspan="1"
                                                            style="width: 184.234px;">Category Type
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="basic-datatables" rowspan="1"
                                                            colspan="1"
                                                            style="width: 184.234px;">Category Details
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="basic-datatables" rowspan="1"
                                                            colspan="1"
                                                            style="width: 156.312px;">Created Date
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="basic-datatables" rowspan="1"
                                                            colspan="1"
                                                            aria-label="Salary: activate to sort column ascending"
                                                            style="width: 156.312px;">Status
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
                                                    <!--Get clients-->
                                                    @forelse ($all_clients as $client)
                                                    <tr role="row">
                                                        <td class="sorting_1">{{ $count++ }}.</td>
                                                        <td>
                                                            @if($client->category_type == 'lab_technician')
                                                                Lab Technician
                                                            @elseif($client->category_type == 'asha_worker')
                                                                Asha Worker
                                                            @elseif($client->category_type == 'healthcare_worker')
                                                                Healthcare Worker
                                                            @elseif($client->category_type == 'school')
                                                                School
                                                            @elseif($client->category_type == 'press_reporter')
                                                                Press Reporter
                                                            @elseif($client->category_type == 'market_president')
                                                                Market President
                                                            @else
                                                                {{ ucfirst(str_replace('_', ' ', $client->category_type)) }}
                                                            @endif
                                                        </td>
                                                        <td>
                                                            @php 
                                                                $details = json_decode($client->details, true); 
                                                            @endphp
                                                            <!--check if json details exists or not-->
                                                            @if(!empty($details))
                                                                @foreach($details as $key => $value)
                                                                    {{ ucfirst(str_replace('_', ' ', $key)) }}: {{ $value ?: 'N/A' }}<br>
                                                                @endforeach
                                                            @else
                                                                N/A
                                                            @endif
                                                        </td>
                                                        <td>{{ \Carbon\Carbon::parse($client->created_at)->format('d M, Y') }}</td>
                                                        <td>
                                                            <span class="status-badge 
                                                                {{ $client->status == 'Pending' ? 'status-pending' : '' }}
                                                                {{ $client->status == 'Reject' ? 'status-suspend' : '' }}
                                                                {{ $client->status == 'Approved' ? 'status-approved' : '' }}">
                                                                {{ ucfirst($client->status) }}
                                                            </span>
                                                        </td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                @if($client->status != 'Approved')
                                                                    <a href="{{ route('mr.clients.edit', $client->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                @else
                                                                    <a href="{{ route('mr.clients.edit', $client->id) }}" class="icon-button  view-btn custom-tooltip" data-tooltip="View">
                                                                        <i class="fa fa-eye"></i>
                                                                    </a>
                                                                @endif
                                                                <form action="{{ route('mr.clients.destroy', $client->id) }}" method="POST" style="display:inline;">
                                                                    @csrf
                                                                    @method('DELETE')
                                                                    <a href="#" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete" onclick="event.preventDefault(); this.closest('form').submit();">
                                                                        <i class="fa fa-trash"></i>
                                                                    </a>
                                                                </form>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @empty
                                                    <tr>
                                                        <td colspan="8" class="text-center">No clients found.</td>
                                                    </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                            {{ $all_clients->appends(request()->query())->links('pagination::bootstrap-5') }}     
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