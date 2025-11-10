@extends('purchase_manager.layouts.master')
@section('content')
<div class="container">
  <div class="page-inner">
    <div class="card">
      <div class="card-header">
        <h4 class="card-title">Edit Purchase Order #{{ $order->id }}</h4>
      </div>

      <div class="card-body">
        @if ($errors->any())
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
        @endif

        <form action="{{ route('purchase-manager.purchase-orders.update', $order->id) }}" method="POST" id="poForm">
          @csrf
          @method('PUT')

          <div class="row">
            <!-- Vendor -->
            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                <label for="vendor_id">Vendor</label>
                <select name="vendor_id" id="vendor_id" class="form-control" required>
                  <option value="" disabled>Select Vendor</option>
                  @foreach ($vendors as $v)
                    <option value="{{ $v->id }}"
                      {{ old('vendor_id', $order->vendor_id) == $v->id ? 'selected' : '' }}>
                      {{ $v->name }} {{ $v->email ? ' - '.$v->email : '' }}
                    </option>
                  @endforeach
                </select>
              </div>
            </div>

            <!-- Order Date -->
            <div class="col-md-6 col-lg-4">
              <div class="form-group">
                <label for="order_date">Order Date</label>
                <input type="date" id="order_date" name="order_date"
                       class="form-control"
                       value="{{ old('order_date', \Carbon\Carbon::parse($order->order_date)->format('Y-m-d')) }}"
                       required>
              </div>
            </div>

            <!-- Notes -->
            <div class="col-md-12">
              <div class="form-group">
                <label for="notes">Notes (optional)</label>
                <textarea id="notes" name="notes" rows="2" class="form-control"
                          placeholder="Any comments...">{{ old('notes', $order->notes) }}</textarea>
              </div>
            </div>
          </div>

          <hr>

          <!-- Items Table -->
          <div class="table-responsive">
            <table class="table table-bordered align-middle" id="itemsTable">
              <thead class="thead-light">
                <tr>
                  <th style="min-width: 200px;">Product</th>
                  <th style="min-width: 120px;">Type</th>
                  <th style="min-width: 100px;">Qty</th>
                  <th style="min-width: 120px;">Price</th>
                  <th style="min-width: 160px;">Discount</th>
                  <th style="min-width: 140px;">Line Total</th>
                  <th style="width: 60px;">
                    <button type="button" class="btn btn-sm btn-success" id="addRow">
                      + Add
                    </button>
                  </th>
                </tr>
              </thead>
              <tbody id="itemsBody">
                @php
                  $oldItems = collect(old('items', []));
                  $rows = $oldItems->isNotEmpty() ? $oldItems : $order->items->map(function($i){
                      return [
                        'product_name'   => $i->product_name,
                        'type'           => $i->type,
                        'quantity'       => $i->quantity,
                        'price'          => $i->price,
                        'discount_type'  => $i->discount_type ?? 'flat',
                        'discount_value' => $i->discount_value ?? 0,
                      ];
                  });
                  if($rows->isEmpty()){
                    $rows = collect([[
                      'product_name'=>null,'type'=>null,'quantity'=>1,'price'=>0,'discount_type'=>'flat','discount_value'=>0
                    ]]);
                  }
                @endphp

                @foreach($rows as $idx => $it)
                <tr>
                  <td>
                    <input type="text" name="items[{{ $idx }}][product_name]" class="form-control"
                           value="{{ $it['product_name'] }}" placeholder="Product name" required>
                  </td>
                  <td>
                    <input type="text" name="items[{{ $idx }}][type]" class="form-control"
                           value="{{ $it['type'] }}" placeholder="Type (e.g., pack, unit)">
                  </td>
                  <td>
                    <input type="number" step="1" min="1" name="items[{{ $idx }}][quantity]" class="form-control qty"
                           value="{{ $it['quantity'] ?? 1 }}" required>
                  </td>
                  <td>
                    <input type="number" step="0.01" min="0" name="items[{{ $idx }}][price]" class="form-control price"
                           value="{{ $it['price'] ?? 0 }}" required>
                  </td>
                  <td>
                    <div class="d-flex gap-2">
                      <select name="items[{{ $idx }}][discount_type]" class="form-control discount-type" style="max-width: 90px;">
                        <option value="flat" {{ ($it['discount_type'] ?? 'flat') == 'flat' ? 'selected' : '' }}>₹</option>
                        <option value="percent" {{ ($it['discount_type'] ?? '') == 'percent' ? 'selected' : '' }}>%</option>
                      </select>
                      <input type="number" step="0.01" min="0" name="items[{{ $idx }}][discount_value]"
                             class="form-control discount-val" value="{{ $it['discount_value'] ?? 0 }}">
                    </div>
                  </td>
                  <td>
                    <input type="text" class="form-control line-total" value="0.00" readonly>
                  </td>
                  <td class="text-center">
                    <button type="button" class="btn btn-sm btn-danger removeRow">&times;</button>
                  </td>
                </tr>
                @endforeach
              </tbody>
              <tfoot>
                <tr>
                  <th colspan="5" class="text-end">Subtotal</th>
                  <th><input type="text" id="subtotal" class="form-control" value="0.00" readonly></th>
                  <th></th>
                </tr>
                <tr>
                  <th colspan="5" class="text-end">Grand Total</th>
                  <th><input type="text" id="grand_total" class="form-control" value="0.00" readonly></th>
                  <th></th>
                </tr>
              </tfoot>
            </table>
          </div>

          <div class="card-action">
            <button type="submit" class="btn btn-success">Update</button>
            <a href="{{ route('purchase-manager.purchase-orders.index') }}" class="btn btn-danger">Cancel</a>
          </div>

        </form>
      </div>
    </div>
  </div>
</div>

{{-- JS for dynamic rows & totals --}}
<script>
(function(){
  // Start next index after last existing row
  let rowIndex = {{ $rows->count() }};
  const body = document.getElementById('itemsBody');

  function recalcRow(tr) {
    const qty   = parseFloat(tr.querySelector('.qty')?.value || 0);
    const price = parseFloat(tr.querySelector('.price')?.value || 0);
    const dType = tr.querySelector('.discount-type')?.value || 'flat';
    const dVal  = parseFloat(tr.querySelector('.discount-val')?.value || 0);

    const gross = qty * price;
    let discount = dType === 'percent' ? (gross * (dVal / 100)) : dVal;
    if (discount > gross) discount = gross;

    const lineTotal = gross - discount;
    tr.querySelector('.line-total').value = lineTotal.toFixed(2);
  }

  function recalcTotals() {
    let subtotal = 0;
    document.querySelectorAll('#itemsBody .line-total').forEach(inp => {
      subtotal += parseFloat(inp.value || 0);
    });
    document.getElementById('subtotal').value = subtotal.toFixed(2);
    document.getElementById('grand_total').value = subtotal.toFixed(2);
  }

  function bindRowEvents(tr) {
    ['input','change'].forEach(evt=>{
      tr.querySelectorAll('.qty,.price,.discount-type,.discount-val').forEach(inp=>{
        inp.addEventListener(evt, () => { recalcRow(tr); recalcTotals(); });
      });
    });
    tr.querySelector('.removeRow')?.addEventListener('click', ()=>{
      if (document.querySelectorAll('#itemsBody tr').length > 1) {
        tr.remove();
        recalcTotals();
      }
    });
  }

  // Bind existing rows
  document.querySelectorAll('#itemsBody tr').forEach(tr => {
    bindRowEvents(tr);
    recalcRow(tr);
  });
  recalcTotals();

  // Add new row
  document.getElementById('addRow').addEventListener('click', ()=>{
    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td><input type="text" name="items[${rowIndex}][product_name]" class="form-control" placeholder="Product name" required></td>
      <td><input type="text" name="items[${rowIndex}][type]" class="form-control" placeholder="Type (e.g., pack, unit)"></td>
      <td><input type="number" step="1" min="1" name="items[${rowIndex}][quantity]" class="form-control qty" value="1" required></td>
      <td><input type="number" step="0.01" min="0" name="items[${rowIndex}][price]" class="form-control price" value="0" required></td>
      <td>
        <div class="d-flex gap-2">
          <select name="items[${rowIndex}][discount_type]" class="form-control discount-type" style="max-width: 90px;">
            <option value="flat">₹</option>
            <option value="percent">%</option>
          </select>
          <input type="number" step="0.01" min="0" name="items[${rowIndex}][discount_value]" class="form-control discount-val" value="0">
        </div>
      </td>
      <td><input type="text" class="form-control line-total" value="0.00" readonly></td>
      <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow">&times;</button></td>
    `;
    body.appendChild(tr);
    bindRowEvents(tr);
    recalcRow(tr);
    recalcTotals();
    rowIndex++;
  });
})();
</script>
@endsection
