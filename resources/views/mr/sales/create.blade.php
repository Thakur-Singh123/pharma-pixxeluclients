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
                @if ($errors->any())
                <div class="alert alert-danger">
                    <strong>
                        There were some errors with your input:
                    </strong>
                </div>
                @endif
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Add New Sale</div>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('mr.sales.store') }}" enctype="multipart/form-data">
                            @csrf
                            <!--Customer Info-->
                            <div class="row">
                                <h4>Customer Details</h4>
                                <div class="col-md-4 mb-4">
                                    <label>Company Name<span class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" value="{{ old('name') }}" placeholder="Enter company name" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label>Email</label>
                                    <input type="email" name="email" class="form-control" value="{{ old('email') }}" placeholder="Enter email address" required>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label>Designation</label>
                                    <input type="text" name="designation" class="form-control" value="{{ old('designation') }}" placeholder="Enter designation" required>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label>Phone</label>
                                    <input type="number" name="phone" class="form-control" value="{{ old('phone') }}" placeholder="Enter phone number" required>
                                </div>
                                <div class="col-md-4 mb-4">
                                    <label>Company Address</label>
                                    <input type="text" name="address" class="form-control" value="{{ old('address') }}" placeholder="Enter company address" required>
                                </div>
                            </div>
                            <!--Doctor Info-->
                            <div class="row mt-3">
                                <h4>Doctor / Prescription Info</h4>
                                <div class="col-md-4">
                                    <label>Doctor Name</label>
                                    <input type="text" name="doctor_name" class="form-control" value="{{ old('doctor_name') }}" placeholder="Enter doctor name" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Upload Prescription (Required)</label>
                                    <input type="file" name="prescription_file" class="form-control" required>
                                </div>
                            </div>
                            <!--Products List-->
                            <div class="row mt-3">
                                <h4>Products</h4>
                                <table class="table table-bordered" id="medicineTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th>Salt Name</th>
                                            <th>Brand Name</th>
                                            <!--<th>Branded/Generic</th>-->
                                            <th>Company</th>
                                            <th>MRP (Base)</th>
                                            <th>Net Rate (After GST)</th>
                                            <th>Margin</th>
                                            <th>
                                                <button type="button" onclick="addRow()" class="btn btn-sm btn-primary">+</button>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td><input name="salt_name[]" class="form-control" required></td>
                                            <td><input name="brand_name[]" class="form-control" required></td>
                                            <!--<td>
                                                <select name="type[]" class="form-control" required>
                                                <option value="Branded">Branded</option>
                                                <option value="Generic">Generic</option>
                                                </select>
                                            </td>-->
                                            <td><input name="company[]" class="form-control" required></td>
                                            <td><input type="number" name="base_price[]" class="form-control base" oninput="calcTotal()" required></td>
                                            <td><input type="number" name="sale_price[]" class="form-control rate" oninput="calcTotal()" required></td>
                                            <td><input type="number" name="margin[]" class="form-control margin" readonly required></td>
                                            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">×</button></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            <!--Payment Info-->
                            <div class="row mt-3">
                                <h4>Payment Summary</h4>
                                <div class="col-md-4">
                                    <label>Total Amount</label>
                                    <input type="number" name="total_amount" id="totalAmount" class="form-control" readonly>
                                </div>
                            </div>
                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <label>Payment Mode</label>
                                    <select name="payment_mode" class="form-control" required>
                                        <option value="" disabled selected>Select</option>
                                        <option value="Cash">Cash</option>
                                        <option value="UPI">UPI</option>
                                        <option value="Card">Card</option>
                                        <option value="Other">Other</option>
                                    </select>
                                </div>
                            </div>
                            <div class="card-action">
                                <button type="submit" class="btn btn-success">Submit</button>
                                <button type="reset" class="btn btn-danger">Cancel</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!--JS-->
<script>
    function addRow() {
        let table = document.querySelector('#medicineTable tbody');
        let row = document.createElement('tr');
        row.innerHTML = `
            <td><input name="salt_name[]" class="form-control"></td>
            <td><input name="brand_name[]" class="form-control"></td>
            <td><input name="company[]" class="form-control"></td>
            <td><input type="number" name="base_price[]" class="form-control base" oninput="calcTotal()"></td>
            <td><input type="number" name="sale_price[]" class="form-control rate" oninput="calcTotal()"></td>
            <td><input type="number" name="margin[]" class="form-control margin" readonly></td>
            <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">×</button></td>
        `;
        table.appendChild(row);
    }
    function removeRow(btn) {
        btn.closest('tr').remove();
        calcTotal();
    }
    function calcTotal() {
        let total = 0;
        document.querySelectorAll('#medicineTable tbody tr').forEach(row => {
            let base = parseFloat(row.querySelector('.base')?.value) || 0;
            let rate = parseFloat(row.querySelector('.rate')?.value) || 0;
    
            let margin = (base - rate).toFixed(2);
            row.querySelector('.margin').value = margin;
    
            total += rate;
        });
        document.getElementById('totalAmount').value = total.toFixed(2);
    }
</script>
@endsection