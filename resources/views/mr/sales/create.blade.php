@extends('mr.layouts.master')
@section('content')
    <div class="container">
        <div class="page-inner">
            <div class="row">
                <div class="row">
                    <div class="col-md-12">

                        <!--Success Message -->
                        @if (session('success'))
                            <div class="alert alert-success">{{ session('success') }}</div>
                        @endif

                        <!--Error Summary (Optional) -->
                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <strong>There were some errors with your input:</strong>
                            </div>
                        @endif
                        <div class="card">
                            <div class="card-header">
                                <div class="card-title">New Sale Entry</div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('mr.sales.store') }}" enctype="multipart/form-data">
                                    @csrf

                                    <!-- Customer Info -->
                                    <div class="row">
                                        <h4>Customer Details</h4>

                                        <div class="col-md-4">
                                            <label>Company Name<span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name') }}">
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email') }}">
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Designation</label>
                                            <input type="text" name="designation" class="form-control"
                                                value="{{ old('designation') }}">
                                            @error('designation')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone') }}">
                                            @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Company Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ old('address') }}">
                                            @error('address')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!-- Doctor & Prescription -->

                                    <div class="row">
                                        <h4>Doctor / Prescription Info</h4>
                                        <div class="col-md-4">
                                            <label>Doctor Name</label>
                                            <input type="text" name="doctor_name" class="form-control"
                                                value="{{ old('doctor_name') }}">
                                            @error('doctor_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Upload Prescription (Required)</label>
                                            <input type="file" name="prescription_file" class="form-control">
                                            @error('prescription_file')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>

                                    <!--  Medicine List -->
                                    <div class="row">
                                    <h4>Medicines</h4>
                                    <table class="table table-bordered" id="medicineTable">
                                        <thead class="table-light">
                                            <tr>
                                                <th>Medicine Name</th>
                                                <th>Base Price</th>
                                                <th>Sale Price</th>
                                                <th>Quantity</th>
                                                <th>Total</th>
                                                <th>
                                                    <button type="button" onclick="addRow()"
                                                        class="btn btn-sm btn-primary">+</button>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @php $oldCount = count(old('medicine_name', [1])) @endphp
                                            @for ($i = 0; $i < $oldCount; $i++)
                                                <tr>
                                                    <td>
                                                        <input name="medicine_name[]" class="form-control"
                                                            value="{{ old('medicine_name')[$i] ?? '' }}">
                                                        @error("medicine_name.$i")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" name="base_price[]" class="form-control"
                                                            value="{{ old('base_price')[$i] ?? '' }}">
                                                        @error("base_price.$i")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" name="sale_price[]" class="form-control rate"
                                                            value="{{ old('sale_price')[$i] ?? '' }}"
                                                            oninput="calcTotal()">
                                                        @error("sale_price.$i")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" name="quantity[]" class="form-control qty"
                                                            value="{{ old('quantity')[$i] ?? '' }}" oninput="calcTotal()">
                                                        @error("quantity.$i")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </td>
                                                    <td>
                                                        <input type="number" name="line_total[]"
                                                            class="form-control line-total"
                                                            value="{{ old('line_total')[$i] ?? '' }}" readonly>
                                                        @error("line_total.$i")
                                                            <small class="text-danger">{{ $message }}</small>
                                                        @enderror
                                                    </td>
                                                    <td><button type="button" class="btn btn-sm btn-danger"
                                                            onclick="removeRow(this)">×</button></td>
                                                </tr>
                                            @endfor
                                        </tbody>
                                    </table>
                                    </div>

                                    <!-- Payment Info -->

                                    <div class="row">
                                        <h4>Payment Summary</h4>
                                        <div class="col-md-4">
                                            <label>Total Amount</label>
                                            <input type="number" name="total_amount" id="totalAmount" class="form-control"
                                                value="{{ old('total_amount') }}" readonly>
                                            @error('total_amount')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label>Discount</label>
                                            <input type="number" name="discount" id="discount" class="form-control"
                                                value="{{ old('discount', 0) }}" oninput="calcNet()">
                                            @error('discount')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label>Net Amount</label>
                                            <input type="number" name="net_amount" id="netAmount" class="form-control"
                                                value="{{ old('net_amount') }}" readonly>
                                            @error('net_amount')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4">
                                            <label>Payment Mode</label>
                                            <select name="payment_mode" class="form-control">
                                                <option value="">-- Select --</option>
                                                <option value="Cash"
                                                    {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>
                                                    Cash</option>
                                                <option value="UPI"
                                                    {{ old('payment_mode') == 'UPI' ? 'selected' : '' }}>
                                                    UPI</option>
                                                <option value="Card"
                                                    {{ old('payment_mode') == 'Card' ? 'selected' : '' }}>
                                                    Card</option>
                                                <option value="Other"
                                                    {{ old('payment_mode') == 'Other' ? 'selected' : '' }}>
                                                    Other</option>
                                            </select>
                                            @error('payment_mode')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <button class="btn btn-success w-100">Submit</button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <!-- JS for row & total calculation -->
    <script>
        function addRow() {
            let table = document.querySelector('#medicineTable tbody');
            let row = document.createElement('tr');
            row.innerHTML = `
        <td><input name="medicine_name[]" class="form-control"></td>
        <td><input type="number" name="base_price[]" class="form-control"></td>
        <td><input type="number" name="sale_price[]" class="form-control rate" oninput="calcTotal()"></td>
        <td><input type="number" name="quantity[]" class="form-control qty" oninput="calcTotal()" ></td>
        <td><input type="number" name="line_total[]" class="form-control line-total" readonly></td>
        <td><button type="button" class="btn btn-sm btn-danger" onclick="removeRow(this)">×</button></td>
    `;
            table.appendChild(row);
            calcTotal();
        }

        function removeRow(btn) {
            let row = btn.closest('tr');
            row.remove();
            calcTotal();
        }

        function calcTotal() {
            let total = 0;
            document.querySelectorAll('#medicineTable tbody tr').forEach(row => {
                let qty = parseFloat(row.querySelector('.qty')?.value) || 0;
                let rate = parseFloat(row.querySelector('.rate')?.value) || 0;
                let lineTotal = qty * rate;
                row.querySelector('.line-total').value = lineTotal.toFixed(2);
                total += lineTotal;
            });
            document.getElementById('totalAmount').value = total.toFixed(2);
            calcNet();
        }

        function calcNet() {
            let total = parseFloat(document.getElementById('totalAmount').value) || 0;
            let discount = parseFloat(document.getElementById('discount').value) || 0;
            let net = total - discount;
            document.getElementById('netAmount').value = net.toFixed(2);
        }
    </script>
@endsection
