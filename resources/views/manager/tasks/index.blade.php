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
                                <h4 class="card-title">All MRs</h4>
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
                                                                style="width: 242.688px;">S No.
                                                            </th>
                                                            <th class="sorting_asc" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1" aria-sort="ascending"
                                                                style="width: 242.688px;">Title
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Description
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Assigned To
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
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
                                                        @forelse ($tasks as $task)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $task->title }}</td>
                                                            <td>{{ $task->description }}</td>
                                                            <td>{{ $task->mr->name }}</td>
                                                            <td>{{ $task->status }}</td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    <a href="{{ route('manager.tasks.edit', $task->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                        <i class="fa fa-edit"></i>
                                                                    </a>
                                                                    <form action="{{ route('manager.tasks.destroy', $task->id) }}" method="POST" style="display:inline;">
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
                                                            <td colspan="10" class="text-center">No record found
                                                            </td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $tasks->links('pagination::bootstrap-5') }}
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