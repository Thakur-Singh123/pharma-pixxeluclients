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
                                <div class="card-title">Edit Sale Entry</div>
                            </div>
                            <div class="card-body">
                                <form method="POST" action="{{ route('mr.sales.update', $sale->id) }}" enctype="multipart/form-data">
                                    @csrf
                                    @method('PUT')

                                    <!-- Customer Info -->
                                    <div class="row">
                                        <h4>Customer Details</h4>

                                        <div class="col-md-4">
                                            <label>Name <span class="text-danger">*</span></label>
                                            <input type="text" name="name" class="form-control"
                                                value="{{ old('name', $sale->name) }}">
                                            @error('name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Email</label>
                                            <input type="email" name="email" class="form-control"
                                                value="{{ old('email', $sale->email) }}">
                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Designation</label>
                                            <input type="text" name="designation" class="form-control"
                                                value="{{ old('designation', $sale->designation) }}">
                                            @error('designation')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Phone</label>
                                            <input type="text" name="phone" class="form-control"
                                                value="{{ old('phone', $sale->phone) }}">
                                            @error('phone')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Company Address</label>
                                            <input type="text" name="address" class="form-control"
                                                value="{{ old('address', $sale->address) }}">
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
                                                value="{{ old('doctor_name', $sale->doctor_name) }}">
                                            @error('doctor_name')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="col-md-4">
                                            <label>Upload New Prescription (optional)</label>
                                            <input type="file" name="prescription_file" class="form-control">
                                            @if ($sale->prescription_file)
                                                <small class="d-block mt-1">Current: <a href="{{ asset('public/prescriptions/' . $sale->prescription_file) }}" target="_blank">View</a></small>
                                            @endif
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
                                                @php
                                                    $items = $sale->items;
                                                    $oldMedicines = old('medicine_name', $items->pluck('medicine_name')->toArray());
                                                    $rows = count($oldMedicines) > 0 ? count($oldMedicines) : 1;
                                                @endphp
                                                @for ($i = 0; $i < $rows; $i++)
                                                    <tr>
                                                        <td>
                                                            <input name="medicine_name[]" class="form-control"
                                                                value="{{ old("medicine_name.$i", $items[$i]->medicine_name ?? '') }}">
                                                            @error("medicine_name.$i")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="number" name="base_price[]" class="form-control"
                                                                value="{{ old("base_price.$i", $items[$i]->base_price ?? '') }}">
                                                            @error("base_price.$i")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="number" name="sale_price[]" class="form-control rate"
                                                                value="{{ old("sale_price.$i", $items[$i]->sale_price ?? '') }}"
                                                                oninput="calcTotal()">
                                                            @error("sale_price.$i")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="number" name="quantity[]" class="form-control qty"
                                                                value="{{ old("quantity.$i", $items[$i]->quantity ?? '') }}" oninput="calcTotal()">
                                                            @error("quantity.$i")
                                                                <small class="text-danger">{{ $message }}</small>
                                                            @enderror
                                                        </td>
                                                        <td>
                                                            <input type="number" name="line_total[]"
                                                                class="form-control line-total"
                                                                value="{{ old("line_total.$i", $items[$i]->line_total ?? '') }}" readonly>
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
                                                value="{{ old('total_amount', $sale->total_amount) }}" readonly>
                                            @error('total_amount')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label>Discount</label>
                                            <input type="number" name="discount" id="discount" class="form-control"
                                                value="{{ old('discount', $sale->discount ?? 0) }}" oninput="calcNet()">
                                            @error('discount')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        <div class="col-md-4">
                                            <label>Net Amount</label>
                                            <input type="number" name="net_amount" id="netAmount" class="form-control"
                                                value="{{ old('net_amount', $sale->net_amount) }}" readonly>
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
                                                @php $pm = old('payment_mode', $sale->payment_mode); @endphp
                                                <option value="Cash" {{ $pm == 'Cash' ? 'selected' : '' }}>Cash</option>
                                                <option value="UPI" {{ $pm == 'UPI' ? 'selected' : '' }}>UPI</option>
                                                <option value="Card" {{ $pm == 'Card' ? 'selected' : '' }}>Card</option>
                                                <option value="Other" {{ $pm == 'Other' ? 'selected' : '' }}>Other</option>
                                            </select>
                                            @error('payment_mode')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                    </div>
                                    <button class="btn btn-success w-100">Update</button>
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
                const lineInput = row.querySelector('.line-total');
                if (lineInput) lineInput.value = lineTotal.toFixed(2);
                total += lineTotal;
            });
            const totalEl = document.getElementById('totalAmount');
            if (totalEl) totalEl.value = total.toFixed(2);
            calcNet();
        }

        function calcNet() {
            let total = parseFloat(document.getElementById('totalAmount').value) || 0;
            let discount = parseFloat(document.getElementById('discount').value) || 0;
            let net = total - discount;
            document.getElementById('netAmount').value = net.toFixed(2);
        }

        window.addEventListener('load', () => {
            calcTotal();
        });
    </script>
@endsection
