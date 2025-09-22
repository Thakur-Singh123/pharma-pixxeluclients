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
                        <h4 class="card-title">All TA/DA Claims</h4>
                        <form method="GET" action="{{ route('mr.tada.index') }}">
                           <select name="status" class="form-control" onchange="this.form.submit()">
                              <option value="">Filter by Status</option>
                              <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pending</option>
                              <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>Approved</option>
                              <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>Rejected</option>
                           </select>
                        </form>
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
                                                style="width: 242.688px;">Travel Date
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 366.578px;">Place Visited
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 187.688px;">Distance (Km)
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 84.5px;">TA (amount)</th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 184.234px;">DA (amount)
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1"
                                                aria-label="Salary: activate to sort column ascending"
                                                style="width: 156.312px;">Total (amount)
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 156.312px;">Mode Travel
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 156.312px;">Outstation Stay
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 156.312px;">Attachment
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 156.312px;">Status
                                             </th>
                                             <th class="sorting" tabindex="0"
                                                aria-controls="basic-datatables" rowspan="1"
                                                colspan="1" style="width: 156.312px;">Action
                                             </th>
                                          </tr>
                                       </thead>
                                       <tbody>
                                          @php $count = 1 @endphp
                                          @forelse ($tada_records as $tada_record)
                                          <tr role="row">
                                             <td class="sorting_1">{{ $count++ }}.</td>
                                             <td>{{ \Carbon\Carbon::parse($tada_record->travel_date)->format('d M, Y') }}</td>
                                             <td>{{ $tada_record->place_visited }}</td>
                                             <td>{{ $tada_record->distance_km }}</td>
                                             <td>{{ $tada_record->ta_amount }}</td>
                                             <td>{{ $tada_record->da_amount }}</td>
                                             <td>{{ $tada_record->total_amount }}</td>
                                             <td>{{ $tada_record->mode_of_travel }}</td>
                                             <td>{{ $tada_record->outstation_stay }}</td>
                                             <td>
                                                <!--Check if image exists or not-->
                                                @if($tada_record->attachment)
                                                <a href="{{ asset('public/uploads/ta_da/' . $tada_record->attachment) }}" target="_blank">View</a>
                                                @else
                                                -
                                                @endif
                                             </td>
                                             <td>
                                                <span class="status-badge 
                                                   {{ $tada_record->status == 'pending' ? 'status-pending' : '' }}
                                                   {{ $tada_record->status == 'rejected' ? 'status-suspend' : '' }}
                                                   {{ $tada_record->status == 'approved' ? 'status-approved' : '' }}">
                                                      {{ ucfirst($tada_record->status) }}
                                                </span>
                                             </td>
                                             <td>
                                                <div class="form-button-action">
                                                   <a href="{{ route('mr.tada.edit', $tada_record->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                   <i class="fa fa-edit"></i>
                                                   </a>
                                                   <form action="{{ route('mr.tada.destroy', $tada_record->id) }}" method="POST" style="display:inline;">
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
                                             <td colspan="10" class="text-center">No record found</td>
                                          </tr>
                                          @endforelse
                                       </tbody>
                                    </table>
                                    {{ $tada_records->links('pagination::bootstrap-5') }}
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