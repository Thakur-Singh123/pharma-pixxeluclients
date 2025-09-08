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
                            <div class="card-header">
                                <h4 class="card-title">All Problems & Challenges</h4>
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
                                                                style="width: 242.688px;">Title
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 366.578px;">Visit Area 
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 187.688px;">Description
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
                                                        <!--Get problems-->
                                                        @forelse ($all_problems as $problem)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $problem->title }}</td>
                                                            <td>
                                                                {{ $problem->visit_details->area_name ?? 'N/A' }},
                                                                {{ $problem->visit_details->district ?? 'N/A' }},
                                                                {{ $problem->visit_details->state ?? 'N/A' }},
                                                                {{ $problem->visit_details->area_code ?? 'N/A' }}
                                                            </td>
                                                            <td>{{ $problem->description }}</td>
                                                            <td>
                                                                <div class="form-button-action"> 
                                                                    <a href="{{ route('mr.problems.edit', $problem->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit"><i class="fa fa-edit"></i></a>
                                                                   <form action="{{ route('mr.problems.destroy', $problem->id) }}" method="POST" style="display:inline;">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete">
                                                                            <i class="fa fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center">No problems found.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $all_problems->links('pagination::bootstrap-5') }}
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