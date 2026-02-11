@extends('mr.layouts.master')
@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
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
                            @if($sale->status != 'Approved')
<<<<<<< HEAD
                                <div class="card-title">Edit Sale Entry</div>
=======
                                <div class="card-title">Edit Sale</div>
>>>>>>> cdf493cfb721166bb1b48d273116d06f942ebc14
                            @else
                                <div class="card-title">Sale Detail</div>
                            @endif
                        </div>
                        <div class="card-body">
                            <form method="POST" action="{{ route('mr.sales.update', $sale->id) }}" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                <!--Customer Info -->
                                <div class="row">
                                    <h4>Customer Details</h4>
                                    <div class="col-md-4 mb-4">
                                        <label>Company Name<span class="text-danger">*</span></label>
                                        <input type="text" name="name" class="form-control" value="{{ old('name', $sale->name) }}" placeholder="Enter company name" required>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label>Email</label>
                                        <input type="email" name="email" class="form-control" value="{{ old('email', $sale->email) }}" placeholder="Enter email address" required>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label>Designation</label>
                                        <input type="text" name="designation" class="form-control" value="{{ old('designation', $sale->designation) }}" placeholder="Enter designation" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Phone</label>
                                        <input type="number" name="phone" class="form-control" value="{{ old('phone', $sale->phone) }}" placeholder="Enter phone number" required>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <label>Company Address</label>
                                        <input type="text" name="address" class="form-control" value="{{ old('address', $sale->address) }}" placeholder="Enter company address" required>
                                    </div>
                                </div>
                                <!--Doctor & Prescription-->
                                <div class="row">
                                    <h4>Doctor / Prescription Info</h4>
                                    <div class="col-md-4">
                                        <label>Doctor Name</label>
                                        <input type="text" name="doctor_name" class="form-control" value="{{ old('doctor_name', $sale->doctor_name) }}" placeholder="Enter doctor name" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label>Upload New Prescription (optional)</label>
                                        <input type="file" name="prescription_file" class="form-control">
                                        @if ($sale->prescription_file)
                                            <small class="d-block mt-1">Current: <a href="{{ asset('public/prescriptions/' . $sale->prescription_file) }}" target="_blank">View</a></small>
                                        @endif
                                    </div>
                                </div>
                                <!--Prodcuts List-->
                                <div class="row">
                                    <h4>Products</h4>
                                    <table class="table table-bordered" id="medicineTable">
                                        <thead class="table-light">
                                            <th>Salt Name</th>
                                            <th>Brand Name</th>
                                            <!--<th>Type</th>-->
                                            <th>Company</th>
                                            <th>MRP (Base)</th>
                                            <th>Net Rate (After GST)</th>
                                            <th>Margin</th>
                                            @if($sale->status != 'Approved')
                                            <th>
                                                <button type="button" onclick="addRow()"
                                                class="btn btn-sm btn-primary">+</button>
                                            </th>
                                            @endif
                                        </thead>
                                        <tbody>
                                            @php
                                            $items = $sale->items;
                                            $salt_name = old('salt_name', $items->pluck('salt_name')->toArray());
                                            $rows = count($salt_name) > 0 ? count($salt_name) : 1;
                                            @endphp
                                            @for ($i = 0; $i < $rows; $i++)
                                            <tr>
                                                <td>
                                                    <input name="salt_name[]" class="form-control"
                                                    value="{{ old("salt_name.$i", $items[$i]->salt_name ?? '') }}">
                                                </td>
                                                <td>
                                                    <input name="brand_name[]" class="form-control"
                                                    value="{{ old("brand_name.$i", $items[$i]->brand_name ?? '') }}">
                                                </td>
                                                <td>
                                                    <input name="company[]" class="form-control"
                                                    value="{{ old("company.$i", $items[$i]->company ?? '') }}">
                                                </td>
                                                <td>
                                                    <input name="base_price[]" class="form-control base"
                                                    value="{{ old("base_price.$i", $items[$i]->base_price ?? '') }}"
                                                    oninput="calcTotal()">
                                                </td>
                                                <td>
                                                    <input type="number" name="sale_price[]" class="form-control rate"
                                                    value="{{ old("sale_price.$i", $items[$i]->sale_price ?? '') }}"
                                                    oninput="calcTotal()">
                                                </td>
                                                <td>
                                                    <input type="number" name="margin[]" class="form-control margin"
                                                    value="{{ old("margin.$i", $items[$i]->margin ?? '') }}" readonly>
                                                </td>
                                                @if($sale->status != 'Approved')
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                                                        ×
                                                    </button>
                                                </td>
                                                @endif
                                            </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                </div>
                                <!--Payment Info-->
                                <div class="row">
                                    <h4>Payment Summary</h4>
                                    <div class="col-md-4">
                                        <label>Total Amount</label>
                                        <input type="number" name="total_amount" id="totalAmount" class="form-control" value="{{ old('total_amount', $sale->total_amount ?? '') }}" readonly>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label>Payment Mode</label>
                                        <select name="payment_mode" class="form-control">
                                            <option value="" disabled selected>Select</option>
                                            @php $pm = old('payment_mode', $sale->payment_mode); @endphp
                                            <option value="Cash" {{ $pm == 'Cash' ? 'selected' : '' }}>Cash</option>
                                            <option value="UPI" {{ $pm == 'UPI' ? 'selected' : '' }}>UPI</option>
                                            <option value="Card" {{ $pm == 'Card' ? 'selected' : '' }}>Card</option>
                                            <option value="Other" {{ $pm == 'Other' ? 'selected' : '' }}>Other</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="card-action">
                                    @if($sale->status != 'Approved')
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
            @if($sale->status != 'Approved')
            <td>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">
                ×
                </button>
            </td>
            @endif
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