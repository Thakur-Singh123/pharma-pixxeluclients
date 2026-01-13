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
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Client</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('manager.clients.update', $client_detail->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!--Category type-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="category_type">Category</label>
                                        <select name="category_type" id="category_type" class="form-control">
                                            <option value="" disabled selected>Select Category</option>
                                            <option value="doctor" @if(old('category_type', $client_detail->category_type) == 'doctor') selected @endif>Doctor</option>
                                            <option value="nurse" @if(old('category_type', $client_detail->category_type) == 'nurse') selected @endif>Nurse</option>
                                            <option value="lab_technician" @if(old('category_type', $client_detail->category_type) == 'lab_technician') selected @endif>Lab Technician</option>
                                            <option value="chemist" @if(old('category_type', $client_detail->category_type) == 'chemist') selected @endif>Chemist</option>
                                            <option value="asha_worker" @if(old('category_type', $client_detail->category_type) == 'asha_worker') selected @endif>Asha Worker</option>
                                            <option value="sarpanch" @if(old('category_type', $client_detail->category_type) == 'sarpanch') selected @endif>Sarpanch</option>
                                            <option value="mc" @if(old('category_type', $client_detail->category_type) == 'mc') selected @endif>MC</option>
                                            <option value="franchisee" @if(old('category_type', $client_detail->category_type) == 'franchisee') selected @endif>Franchisee</option>
                                            <option value="healthcare_worker" @if(old('category_type', $client_detail->category_type) == 'healthcare_worker') selected @endif>Any Healthcare Worker</option>
                                            <option value="others" @if(old('category_type', $client_detail->category_type) == 'others') selected @endif>Others</option>
                                        </select>
                                        @error('category_type')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!--Dynamic fields-->
                            <div class="row mt-3" id="category-fields">
                                <!--Doctor Details-->
                                <div class="col-md-12 extra doctor-heading" style="display:none;">
                                    <h5 class="details-heading">*Doctor Details</h5>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Doctor Name</label>
                                        <input type="text" name="doctor_name" class="form-control" value="{{ old('doctor_name', $client_detail->details->doctor_name ?? '') }}" placeholder="Enter doctor name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Hospital Name</label>
                                        <input type="text" name="hospital_name" class="form-control" value="{{ old('hospital_name', $client_detail->details->hospital_name ?? '') }}" placeholder="Enter hospital name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Hospital Type</label>
                                        <select name="hospital_type" class="form-control" required>
                                            <option value="" disabled selected>Select</option>
                                            <option value="government" @if( old('hospital_type', $client_detail->details->hospital_type ?? '') == 'government') selected @endif>Government</option>
                                            <option value="private" @if( old('hospital_type',  $client_detail->details->hospital_type ?? '') == 'private') selected @endif>Private</option>
                                            <option value="clinic" @if( old('hospital_type',  $client_detail->details->hospital_type ?? '') == 'clinic') selected @endif>Clinic</option>
                                            <option value="other" @if( old('hospital_type',  $client_detail->details->hospital_type ?? '') == 'other') selected @endif>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Specialist</label>
                                        <input type="text" name="specialist" class="form-control" value="{{ old('specialist', $client_detail->details->specialist ?? '') }}" placeholder="Enter MBBS / MD / MS / Diploma" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="hospital_contact" class="form-control" value="{{ old('hospital_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="hospital_address" class="form-control" value="{{ old('hospital_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="hospital_particulars" class="form-control" value="{{ old('hospital_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="hospital_remarks" class="form-control" value="{{ old('hospital_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Nurse Details-->
                                <div class="col-md-12 extra nurse-heading" style="display:none;">
                                    <h5 class="details-heading">*Nurse Details</h5>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Nurse Name</label>
                                        <input type="text" name="nurse_name" class="form-control" value="{{ old('nurse_name', $client_detail->details->nurse_name ?? '') }}" placeholder="Enter nurse name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="nurse_contact" class="form-control" value="{{ old('nurse_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="nurse_address" class="form-control" value="{{ old('nurse_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="nurse_particulars" class="form-control" value="{{ old('nurse_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="nurse_remarks" class="form-control" value="{{ old('nurse_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Lab Technician Details-->
                                <div class="col-md-12 extra lab_technician-heading" style="display:none;">
                                    <h5 class="details-heading">*Lab Technician Details</h5>
                                </div>
                                <div class="col-md-4 extra lab_technician" style="display:none;">
                                    <div class="form-group">
                                        <label>Lab Name</label>
                                        <input type="text" name="lab_name" class="form-control" value="{{ old('lab_name', $client_detail->details->lab_name ?? '') }}" placeholder="Enter lab name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra lab_technician" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="lab_contact" class="form-control" value="{{ old('lab_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra lab_technician" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="lab_address" class="form-control" value="{{ old('lab_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra lab_technician" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="lab_particulars" class="form-control" value="{{ old('lab_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra lab_technician" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="lab_remarks" class="form-control" value="{{ old('lab_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Chemist Details--> 
                                <div class="col-md-12 extra chemist-heading" style="display:none;">
                                    <h5 class="details-heading">*Chemist Details</h5>
                                </div>
                                <div class="col-md-4 extra chemist" style="display:none;">
                                    <div class="form-group">
                                        <label>Chemist Name</label>
                                        <input type="text" name="chemist_name" class="form-control" value="{{ old('chemist_name', $client_detail->details->chemist_name ?? '') }}" placeholder="Enter chemist name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra chemist" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="chemist_contact" class="form-control" value="{{ old('chemist_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra chemist" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="chemist_address" class="form-control" value="{{ old('chemist_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra chemist" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="chemist_particulars" class="form-control" value="{{ old('chemist_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra chemist" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="chemist_remarks" class="form-control" value="{{ old('chemist_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Asha Worker Details-->
                                <div class="col-md-12 extra asha_worker-heading" style="display:none;">
                                    <h5 class="details-heading">*Asha Worker Details</h5>
                                </div>
                                <div class="col-md-4 extra asha_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Asha Worker Name</label>
                                        <input type="text" name="asha_name" class="form-control" value="{{ old('asha_name', $client_detail->details->asha_name ?? '') }}" placeholder="Enter name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra asha_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="asha_contact" class="form-control" value="{{ old('asha_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra asha_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="asha_address" class="form-control" value="{{ old('asha_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra asha_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="asha_particulars" class="form-control" value="{{ old('asha_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra asha_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="asha_remarks" class="form-control" value="{{ old('asha_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Sarpanch Details-->
                                <div class="col-md-12 extra sarpanch-heading" style="display:none;">
                                    <h5 class="details-heading">*Sarpanch Details</h5>
                                </div>
                                <div class="col-md-4 extra sarpanch" style="display:none;">
                                    <div class="form-group">
                                        <label>Sarpanch Name</label>
                                        <input type="text" name="sarpanch_name" class="form-control" value="{{ old('sarpanch_name', $client_detail->details->sarpanch_name ?? '') }}" placeholder="Enter sarpanch name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra sarpanch" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="sarpanch_contact" class="form-control" value="{{ old('sarpanch_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra sarpanch" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="sarpanch_address" class="form-control" value="{{ old('sarpanch_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra sarpanch" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="sarpanch_particulars" class="form-control" value="{{ old('sarpanch_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra sarpanch" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="sarpanch_remarks" class="form-control" value="{{ old('sarpanch_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Ward / MC Details-->
                                <div class="col-md-12 extra mc-heading" style="display:none;">
                                    <h5 class="details-heading">*Ward / MC Details</h5>
                                </div>
                                <div class="col-md-4 extra mc" style="display:none;">
                                    <div class="form-group">
                                        <label>Ward / MC</label>
                                        <input type="text" name="mc_ward" class="form-control" value="{{ old('mc_ward', $client_detail->details->mc_ward ?? '') }}" placeholder="Enter ward / mc name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra mc" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="mc_contact" class="form-control" value="{{ old('mc_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra mc" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="mc_address" class="form-control" value="{{ old('mc_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra mc" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="mc_particulars" class="form-control" value="{{ old('mc_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra mc" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="mc_remarks" class="form-control" value="{{ old('mc_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Franchisee Details-->
                                <div class="col-md-12 extra franchisee-heading" style="display:none;">
                                    <h5 class="details-heading">*Franchisee Details</h5>
                                </div>
                                <div class="col-md-4 extra franchisee" style="display:none;">
                                    <div class="form-group">
                                        <label>Franchisee Name</label>
                                        <input type="text" name="franchisee_name" class="form-control" value="{{ old('franchisee_name', $client_detail->details->franchisee_name ?? '') }}" placeholder="Enter franchisee name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra franchisee" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="franchisee_contact" class="form-control" value="{{ old('franchisee_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra franchisee" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="franchisee_address" class="form-control" value="{{ old('franchisee_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra franchisee" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="franchisee_particulars" class="form-control" value="{{ old('franchisee_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div> 
                                <div class="col-md-4 extra franchisee" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="franchisee_remarks" class="form-control" value="{{ old('franchisee_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Healthcare Details-->
                                <div class="col-md-12 extra healthcare_worker-heading" style="display:none;">
                                    <h5 class="details-heading">*Healthcare Details</h5>
                                </div>
                                <div class="col-md-4 extra healthcare_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Healthcare Worker Name</label>
                                        <input type="text" name="health_worker_name" class="form-control" value="{{ old('docthealth_worker_nameor_name', $client_detail->details->health_worker_name ?? '') }}" placeholder="Enter name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra healthcare_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="health_contact" class="form-control" value="{{ old('health_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra healthcare_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="health_address" class="form-control" value="{{ old('health_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra healthcare_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="health_particulars" class="form-control" value="{{ old('health_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra healthcare_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="health_remarks" class="form-control" value="{{ old('health_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                                <!--Other Details-->
                                <div class="col-md-12 extra others-heading" style="display:none;">
                                    <h5 class="details-heading">*Other Details</h5>
                                </div>
                                <div class="col-md-4 extra others" style="display:none;">
                                    <div class="form-group">
                                        <label>Name</label>
                                        <input type="text" name="others_name" class="form-control" value="{{ old('others_name', $client_detail->details->name ?? '') }}" placeholder="Enter name" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra others" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="others_contact" class="form-control" value="{{ old('others_contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra others" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="others_address" class="form-control" value="{{ old('others_address', $client_detail->details->address ?? '') }}" placeholder="Enter address" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra others" style="display:none;">
                                    <div class="form-group">
                                        <label>Particulars</label>
                                        <input type="text" name="others_particulars" class="form-control" value="{{ old('others_particulars', $client_detail->details->particulars ?? '') }}" placeholder="Enter particulars" required>
                                    </div>
                                </div>
                                <div class="col-md-4 extra others" style="display:none;">
                                    <div class="form-group">
                                        <label>Remarks</label>
                                        <input type="text" name="others_remarks" class="form-control" value="{{ old('others_remarks', $client_detail->details->remarks ?? '') }}" placeholder="Enter remarks" required>
                                    </div>
                                </div>
                            </div>
                            <div class="card-action mt-3">
                                @if($client_detail->status != 'Approved')
                                    <button type="submit" class="btn btn-success">Update</button>
                                    <button type="reset" class="btn btn-danger">Cancel</button>
                                @endif
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--Js-->
<script>
document.addEventListener("DOMContentLoaded", function() {
    const categorySelect = document.getElementById("category_type");
    const allExtra = document.querySelectorAll(".extra");
    function toggleFields() {
        allExtra.forEach(el => {
            el.style.display = "none";
            const inputs = el.querySelectorAll("input, select, textarea");
            inputs.forEach(input => {
                input.removeAttribute("required");
                if (categorySelect.value && !el.classList.contains(categorySelect.value)) {
                    input.value = "";
                }
            });
        });
        if (categorySelect.value) {
            const fields = document.querySelectorAll("." + categorySelect.value);
            fields.forEach(el => {
                el.style.display = "block";
                const inputs = el.querySelectorAll("input, select, textarea");
                inputs.forEach(input => input.setAttribute("required", true));
            });
            const heading = document.querySelector("." + categorySelect.value + "-heading");
            if (heading) heading.style.display = "block";
        }
    }
    categorySelect.addEventListener("change", toggleFields);
    toggleFields();
});
</script>
@endsection