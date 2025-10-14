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
                            <h4 class="card-title">All Clients</h4>
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
                                                            aria-label="Salary: activate to sort column ascending"
                                                            style="width: 156.312px;">Status
                                                        </th>
                                                        <th class="sorting" tabindex="0"
                                                            aria-controls="basic-datatables" rowspan="1"
                                                            colspan="1" style="width: 366.578px;">Approval
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
                                                            @else
                                                                {{ $client->category_type }}
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
                                                        <td>
                                                            <span class="status-badge 
                                                                {{ $client->status == 'Pending' ? 'status-pending' : '' }}
                                                                {{ $client->status == 'Reject' ? 'status-suspend' : '' }}
                                                                {{ $client->status == 'Approved' ? 'status-approved' : '' }}">
                                                                {{ ucfirst($client->status) }}
                                                            </span>
                                                        </td>
                                                        <td style="display: flex; justify-content: center; align-items: center; gap: 8px; border: none; height: 97px;">
                                                            @if ($client->status == 'Pending')
                                                                <form method="POST"
                                                                    action="{{ route('manager.clients.approve', $client->id) }}">
                                                                    @csrf
                                                                    <button
                                                                        class="btn btn-success btn-sm">Approve
                                                                    </button>
                                                                </form>
                                                                <form method="POST"
                                                                    action="{{ route('manager.clients.reject', $client->id) }}">
                                                                    @csrf
                                                                    <button
                                                                        class="btn btn-danger btn-sm">Reject
                                                                    </button>
                                                                </form>
                                                            @elseif($client->status == 'Approved')
                                                                <form method="POST"
                                                                    action="{{ route('manager.clients.reject', $client->id) }}">
                                                                    @csrf
                                                                    <button
                                                                        class="btn btn-danger btn-sm">Reject
                                                                    </button>
                                                                </form>
                                                            @elseif($client->status == 'Reject')
                                                                <form method="POST"
                                                                    action="{{ route('manager.clients.approve', $client->id) }}">
                                                                    @csrf
                                                                    <button
                                                                        class="btn btn-success btn-sm">Approve
                                                                    </button>
                                                                </form>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <a href="{{ route('manager.clients.edit', $client->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                    <i class="fa fa-edit"></i>
                                                                </a>
                                                                <form action="{{ route('manager.clients.destroy', $client->id) }}" method="POST" style="display:inline;">
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