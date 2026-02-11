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
                                                            style="width: 156.312px;">Action
                                                        </th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @php $count = 1 @endphp
                                                    <!--Get clients-->
                                                    @forelse ($categories as $categorie)
                                                    <tr role="row">
                                                        <td class="sorting_1">{{ $count++ }}.</td>
                                                        <td class="sorting_1">{{ $categorie->name }}</td>
                                                        <td>
                                                            <div class="form-button-action">
                                                                <form action="{{ route('manager.client.category.delete', $categorie->id) }}" method="POST" style="display:inline;">
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
                                            {{ $categories->appends(request()->query())->links('pagination::bootstrap-5') }}     
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