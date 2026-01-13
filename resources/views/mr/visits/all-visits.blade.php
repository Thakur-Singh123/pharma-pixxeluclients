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
                                <h4 class="card-title">All Visits</h4>
                                  <div class="d-flex align-items-center" style="gap: 10px;">
                                    
                                    <input
                                        type="text"
                                        id="visitSearch"
                                        class="custom-search-input"
                                        placeholder="Search"
                                        value="{{ request('search') }}"
                                    >
                                    <form method="GET" action="{{ route('mr.visits') }}" class="m-0 d-flex align-items-center" style="gap: 10px;">
                                        <input type="hidden" name="search" value="{{ request('search') }}">
                                        <input
                                            type="date"
                                            id="visitDateFilter"
                                            name="visit_date"
                                            class="form-control"
                                            value="{{ request('visit_date') }}"
                                            onchange="handleFilterChange(this)"
                                        >
                                        <button
                                            type="submit"
                                            class="btn btn-primary btn-sm"
                                            formaction="{{ route('mr.visits.export') }}"
                                        >Export</button>
                                    </form>
                                </div>
                                {{-- <input type="text" 
                                    id="visitSearch" 
                                    class="custom-search-input"
                                    placeholder="Search"
                                > --}}
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <div id="basic-datatables_wrapper"
                                        class="dataTables_wrapper container-fluid dt-bootstrap4">
                                        <div class="row">
                                            <div class="col-sm-12">
                                                <div id="visitResults">
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
                                                                colspan="1"
                                                                style="width: 366.578px;">Area Block
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 187.688px;">District
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 84.5px;">State</th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Area Pin Code
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Visit Date
                                                            </th>
                                                    
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Visit Type
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Clinic/Hospital Name
                                                            </th>
                                                            <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Mobile No.
                                                            </th>
                                                             <th class="sorting" tabindex="0"
                                                                aria-controls="basic-datatables" rowspan="1"
                                                                colspan="1"
                                                                style="width: 184.234px;">Remarks
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
                                                        <!--Get visits-->
                                                        @forelse ($all_visits as $visit)
                                                        <tr role="row">
                                                            <td class="sorting_1">{{ $count++ }}.</td>
                                                            <td>{{ $visit->area_name }}</td>
                                                            <td>{{ $visit->area_block }}</td>
                                                            <td>{{ $visit->district }}</td>
                                                            <td>{{ $visit->state }}</td>
                                                            <td>{{ $visit->pin_code }}</td>
                                                            <td>{{ \Carbon\Carbon::parse($visit->visit_date)->format('d M, Y') }}</td>
                                                            <td>
                                                                <!--Check if visit type exits or not-->
                                                                @if($visit->visit_type == 'other') Other Visit -
                                                                    ({{ $visit->other_visit ?? 'N/A' }})
                                                                @elseif($visit->visit_type == 'doctor') Doctor - 
                                                                    ({{ $visit->doctor->doctor_name ?? 'N/A' }}-{{ $visit->doctor->specialist ?? 'N/A' }}-{{ $visit->doctor->hospital_name ?? 'N/A' }}-{{ $visit->doctor->hospital_type ?? 'N/A' }})
                                                                @elseif($visit->visit_type == 'religious_places')Religious Places -
                                                                    {{ $visit->religious_place ?? 'N/A' }}
                                                                @elseif($visit->visit_type == 'school')School -
                                                                    ({{ $visit->school_type ?? 'N/A' }})
                                                                @elseif($visit->visit_type == 'bams_rmp_dental')BAMS RMP Dental
                                                                @elseif($visit->visit_type == 'asha_workers')Asha Workers
                                                                @elseif($visit->visit_type == 'health_workers')Health Workers
                                                                @elseif($visit->visit_type == 'anganwadi')Anganwadi / Balvatika
                                                                @elseif($visit->visit_type == 'villages')Villages -
                                                                    ({{ $visit->villages ?? 'N/A' }})
                                                                @elseif($visit->visit_type == 'city')City -
                                                                    ({{ $visit->city ?? 'N/A' }})
                                                                @elseif($visit->visit_type == 'societies')Societies -
                                                                    ({{ $visit->societies ?? 'N/A' }})
                                                                @elseif($visit->visit_type == 'ngo')NGO -
                                                                    ({{ $visit->ngo ?? 'N/A' }})
                                                                @endif
                                                            </td>
                                                            <td>{{ $visit->clinic_hospital_name ?? 'N/A' }}</td>
                                                            <td>{{ $visit->mobile ?? 'N/A' }}</td>
                                                            <td>{{ $visit->comments }}</td>
                                                            <td>
                                                                <span class="status-badge 
                                                                    {{ $visit->status == 'Pending' ? 'status-pending' : '' }}
                                                                    {{ $visit->status == 'Reject' ? 'status-suspend' : '' }}
                                                                    {{ $visit->status == 'Approved' ? 'status-approved' : '' }}">
                                                                    {{ ucfirst($visit->status) }}
                                                                </span>
                                                            </td>
                                                            <td>
                                                                <div class="form-button-action">
                                                                    @if($visit->status != 'Approved')
                                                                        <a href="{{ url('mr/visits/edit', $visit->id) }}" class="icon-button edit-btn custom-tooltip" data-tooltip="Edit">
                                                                            <i class="fa fa-edit"></i>
                                                                        </a>
                                                                    @else
                                                                        <a href="{{ url('mr/visits/edit', $visit->id) }}" class="icon-button  view-btn custom-tooltip" data-tooltip="View">
                                                                            <i class="fa fa-eye"></i>
                                                                        </a>
                                                                    @endif
                                                                        <a href="{{ url('mr/delete-visit', $visit->id) }}" class="icon-button delete-btn custom-tooltip" data-tooltip="Delete"><i class="fa fa-trash"></i></a>
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @empty
                                                        <tr>
                                                            <td colspan="8" class="text-center">No visits found.</td>
                                                        </tr>
                                                        @endforelse
                                                    </tbody>
                                                </table>
                                                {{ $all_visits->appends(request()->query())->links('pagination::bootstrap-5') }}     
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
<script>
function handleFilterChange(select) {
    if (select.value === "") {
        window.location.href = "{{ route('mr.visits') }}";
    } else {
        select.form.submit();
    }
}
</script>
@endsection