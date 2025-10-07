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
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Edit Client</h4>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('mr.clients.update', $client_detail->id) }}" method="POST" autocomplete="off">
                            @csrf
                            @method('PUT')
                            <div class="row">
                                <!--Category type-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="category_type">Category</label>
                                        <select name="category_type" id="category_type" class="form-control">
                                            <option value="">Select Category</option>
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
                                <!--Client name-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="client_name">Client Name</label>
                                        <input type="text" class="form-control" id="client_name" name="client_name"
                                            value="{{ old('client_name', $client_detail->name ?? '') }}" placeholder="Enter client name">
                                        @error('client_name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <!--Client contact-->
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label for="client_contact">Contact Number</label>
                                        <input type="number" class="form-control" id="client_contact" name="client_contact"
                                            value="{{ old('client_contact', $client_detail->contact ?? '') }}" placeholder="Enter contact number">
                                        @error('client_contact')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <!--Dynamic fields-->
                            <div class="row mt-3" id="category-fields">
                                 <!--Doctor Details-->
                                <div class="col-md-12 extra doctor-heading">
                                    <h5 class="details-heading">*Doctor Details</h5>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Doctor Name</label>
                                        <input type="text" name="doctor_name" class="form-control" value="{{ old('doctor_name', $client_detail->details->doctor_name ?? '') }}" placeholder="Enter doctor name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Hospital Name</label>
                                        <input type="text" name="hospital_name" class="form-control" value="{{ old('hospital_name', $client_detail->details->hospital_name ?? '') }}" placeholder="Enter hospital name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Hospital Type</label>
                                        <select name="hospital_type" class="form-control">
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
                                        <input type="text" name="specialist" class="form-control" value="{{ old('specialist', $client_detail->details->specialist ?? '') }}" placeholder="Enter MBBS / MD / MS / Diploma">
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input type="number" name="age" class="form-control" value="{{ old('age', $client_detail->details->age ?? '') }}" placeholder="Enter age">
                                    </div>
                                </div>
                                <div class="col-md-4 extra doctor" style="display:none;">
                                    <div class="form-group">
                                        <label>Experience</label>
                                        <input type="text" name="experience" class="form-control" value="{{ old('experience', $client_detail->details->experience ?? '') }}" placeholder="Enter experience">
                                    </div>
                                </div>
                                <!--Nurse Details-->
                                <div class="col-md-12 extra nurse-heading" style="display:none;">
                                    <h5 class="details-heading">*Nurse Details</h5>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Nurse Name</label>
                                        <input type="text" name="nurse_name" class="form-control" value="{{ old('nurse_name', $client_detail->details->nurse_name ?? '') }}" placeholder="Enter nurse name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Hospital Name</label>
                                        <input type="text" name="n_hospital_name" class="form-control" value="{{ old('n_hospital_name', $client_detail->details->hospital_name ?? '') }}" placeholder="Enter hospital name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Department</label>
                                        <select name="department" class="form-control">
                                            <option value="" disabled selected>Select</option>
                                            <option value="icu" @if( old('department',  $client_detail->details->department ?? '') == 'icu') selected @endif>ICU</option>
                                            <option value="general" @if( old('department',  $client_detail->details->department ?? '') == 'general') selected @endif>General</option>
                                            <option value="pediatrics" @if( old('department',  $client_detail->details->department ?? '') == 'pediatrics') selected @endif>Pediatrics</option>
                                            <option value="others" @if( old('department',  $client_detail->details->department ?? '') == 'others') selected @endif>Others</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Age</label>
                                        <input type="number" name="n_age" class="form-control" value="{{ old('n_age', $client_detail->details->age ?? '') }}" placeholder="Enter age">
                                    </div>
                                </div>
                                <div class="col-md-4 extra nurse" style="display:none;">
                                    <div class="form-group">
                                        <label>Experience</label>
                                        <input type="text" name="n_experience" class="form-control" value="{{ old('n_experience', $client_detail->details->experience ?? '') }}" placeholder="Enter experience">
                                    </div>
                                </div>
                                <!--Lab Technician Details-->
                                <div class="col-md-12 extra lab_technician-heading" style="display:none;">
                                    <h5 class="details-heading">*Lab Technician Details</h5>
                                </div>
                                <div class="col-md-4 extra lab_technician" style="display:none;">
                                    <div class="form-group">
                                        <label>Lab Name</label>
                                        <input type="text" name="lab_name" class="form-control" value="{{ old('lab_name', $client_detail->details->lab_name ?? '') }}" placeholder="Enter lab name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra lab_technician" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="lab_contact" class="form-control" value="{{ old('lab_contact', $client_detail->details->lab_contact ?? '') }}" placeholder="Enter contact number">
                                    </div>
                                </div>
                                <!--Chemist Details--> 
                                <div class="col-md-12 extra chemist-heading" style="display:none;">
                                    <h5 class="details-heading">*Chemist Details</h5>
                                </div>
                                <div class="col-md-4 extra chemist" style="display:none;">
                                    <div class="form-group">
                                        <label>Chemist Name</label>
                                        <input type="text" name="chemist_name" class="form-control" value="{{ old('chemist_name', $client_detail->details->chemist_name ?? '') }}" placeholder="Enter chemist name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra chemist" style="display:none;">
                                    <div class="form-group">
                                        <label>Address</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address', $client_detail->details->address ?? '') }}" placeholder="Enter address">
                                    </div>
                                </div>
                                <!--Asha Worker Details-->
                                <div class="col-md-12 extra asha_worker-heading" style="display:none;">
                                    <h5 class="details-heading">*Asha Worker Details</h5>
                                </div>
                                <div class="col-md-4 extra asha_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Asha Worker Name</label>
                                        <input type="text" name="asha_name" class="form-control" value="{{ old('asha_name', $client_detail->details->asha_name ?? '') }}" placeholder="Enter name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra asha_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Area / Pin Code</label>
                                        <input type="number" name="asha_area" class="form-control" value="{{ old('asha_area', $client_detail->details->asha_area ?? '') }}" placeholder="Enter area / pin code">
                                    </div>
                                </div>
                                <!--Sarpanch Details-->
                                <div class="col-md-12 extra sarpanch-heading" style="display:none;">
                                    <h5 class="details-heading">*Sarpanch Details</h5>
                                </div>
                                <div class="col-md-4 extra sarpanch" style="display:none;">
                                    <div class="form-group">
                                        <label>Sarpanch Name</label>
                                        <input type="text" name="sarpanch_name" class="form-control" value="{{ old('sarpanch_name', $client_detail->details->sarpanch_name ?? '') }}" placeholder="Enter sarpanch name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra sarpanch" style="display:none;">
                                    <div class="form-group">
                                        <label>Contact</label>
                                        <input type="number" name="contact" class="form-control" value="{{ old('contact', $client_detail->details->contact ?? '') }}" placeholder="Enter contact number">
                                    </div>
                                </div>
                                <!--Ward / MC Details-->
                                <div class="col-md-12 extra mc-heading" style="display:none;">
                                    <h5 class="details-heading">*Ward / MC Details</h5>
                                </div>
                                <div class="col-md-4 extra mc" style="display:none;">
                                    <div class="form-group">
                                        <label>Ward / MC</label>
                                        <input type="text" name="mc_ward" class="form-control" value="{{ old('mc_ward', $client_detail->details->mc_ward ?? '') }}" placeholder="Enter ward / mc name">
                                    </div>
                                </div>
                                <div class="col-md-4 extra mc" style="display:none;">
                                    <div class="form-group">
                                        <label>Important Person</label>
                                        <input type="text" name="mc_person" class="form-control" value="{{ old('mc_person', $client_detail->details->mc_person ?? '') }}" placeholder="Enter important person name">
                                    </div>
                                </div>
                                <!--Franchisee Details-->
                                <div class="col-md-12 extra franchisee-heading" style="display:none;">
                                    <h5 class="details-heading">*Franchisee Details</h5>
                                </div>
                                <div class="col-md-4 extra franchisee" style="display:none;">
                                    <div class="form-group">
                                        <label>Franchisee Name</label>
                                        <input type="text" name="franchisee_name" class="form-control" value="{{ old('franchisee_name', $client_detail->details->franchisee_name ?? '') }}" placeholder="Enter franchisee name">
                                    </div>
                                </div>
                                <!--Healthcare Details-->
                                <div class="col-md-12 extra healthcare_worker-heading" style="display:none;">
                                    <h5 class="details-heading">*Healthcare Details</h5>
                                </div>
                                <!--Healthcare Details-->
                                <div class="col-md-4 extra healthcare_worker" style="display:none;">
                                    <div class="form-group">
                                        <label>Healthcare Worker Name</label>
                                        <input type="text" name="health_worker_name" class="form-control" value="{{ old('health_worker_name', $client_detail->details->health_worker_name ?? '') }}" placeholder="Enter name">
                                    </div>
                                </div>
                                <!--Other Details-->
                                <div class="col-md-12 extra others-heading" style="display:none;">
                                    <h5 class="details-heading">*Other Details</h5>
                                </div>
                                <div class="col-md-4 extra others" style="display:none;">
                                    <div class="form-group">
                                        <input type="text" name="others" class="form-control" value="{{ old('others', $client_detail->details->others_details ?? '') }}" placeholder="Enter other details">
                                    </div>
                                </div>
                            </div>
                            <div class="card-action mt-3">
                                <button type="submit" class="btn btn-success">Update</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const categorySelect = document.getElementById("category_type");
        const allExtra = document.querySelectorAll(".extra");
        function toggleFields() {
            allExtra.forEach(el => el.style.display = "none");
            if(categorySelect.value) {
                const fields = document.querySelectorAll("." + categorySelect.value);
                fields.forEach(el => el.style.display = "block");
                const heading = document.querySelector("." + categorySelect.value + "-heading");
                if(heading) heading.style.display = "block";
            }
        }
        categorySelect.addEventListener("change", toggleFields);
        toggleFields();
    });
</script>
@endsection