@extends('manager.layouts.master')
@section('content')
<div class="container">
  <div class="page-inner">
    <div class="card">
      <div class="card-header">
          <h4 class="card-title">
              @if($order->status !='approved')
                Edit Purchase Order #{{ $order->id }}
              @else
                Purchase Order Detail #{{ $order->id }}
              @endif
          </h4>
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
        <form action="{{ route('manager.purchase-manager.update', $order->id) }}" method="POST" id="poForm">
            @csrf
            @method('PUT')
            <div class="row">
              <!--Vendor-->
              <div class="col-md-6 col-lg-4">
                <div class="form-group">
                  <label for="vendor_id">Vendor</label>
                  <select name="vendor_id" id="vendor_id" class="form-control" required>
                      <option value="" disabled>Select Vendor</option>
                      @foreach ($vendors as $v)
                      <option value="{{ $v->id }}" {{ old('vendor_id', $order->vendor_id) == $v->id ? 'selected' : '' }}>
                        {{ $v->name }} {{ $v->email ? ' - '.$v->email : '' }}
                      </option>
                      @endforeach
                  </select>
                </div>
              </div>
              <!--Order Date-->
              <div class="col-md-6 col-lg-4">
                <div class="form-group">
                  <label for="order_date">Order Date</label>
                  <input type="date" id="order_date" name="order_date" class="form-control"
                    value="{{ old('order_date', \Carbon\Carbon::parse($order->order_date)->format('Y-m-d')) }}" required>
                </div>
              </div>
              <!--Nature Of Vendor-->
              <div class="col-md-6 col-lg-4">
                <div class="form-group">
                  <label for="nature_of_vendor">Nature Of Vendor</label>
                  <input type="text" id="nature_of_vendor" name="nature_of_vendor" class="form-control"
                    value="{{ old('nature_of_vendor', $order->nature_of_vendor) }}" placeholder="Enter nature of vendor">
                </div>
              </div>
              <!--Notes-->
              <div class="col-md-12">
                <div class="form-group">
                  <label for="notes">Notes (optional)</label>
                  <textarea id="notes" name="notes" rows="2" class="form-control"
                      placeholder="Any comments...">{{ old('notes', $order->notes) }}</textarea>
                </div>
              </div>
            </div>
            <hr>
            <!--Items Table-->
            <div class="table-responsive">
              <table class="table table-bordered align-middle" id="itemsTable">
                <thead class="thead-light">
                  <tr>
                    <th style="min-width: 200px;">Product</th>
                    <th style="min-width: 120px;">Type</th>
                    <th style="min-width: 100px;">Qty</th>
                    <th style="width: 60px;">
                      @if($order->status != 'approved')
                        <button type="button" class="btn btn-sm btn-success" id="addRow">+ Add</button>
                      @endif
                    </th>
                  </tr>
                </thead>
                <tbody id="itemsBody">
                  @php
                  $oldItems = collect(old('items', []));
                  $rows = $oldItems->isNotEmpty() ? $oldItems : $order->items->map(function($i){
                  return [
                  'product_name' => $i->product_name,
                  'type'         => $i->type,
                  'quantity'     => $i->quantity,
                  ];
                  });
                  if($rows->isEmpty()){
                  $rows = collect([[ 'product_name'=>null,'type'=>null,'quantity'=>1 ]]);
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
                        <input type="number" step="1" min="1" name="items[{{ $idx }}][quantity]" class="form-control"
                          value="{{ $it['quantity'] ?? 1 }}" required>
                      </td>
                      <td class="text-center">
                        @if($order->status != 'approved')
                          <button type="button" class="btn btn-sm btn-danger removeRow">&times;</button>
                        @endif
                      </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <div class="card-action">
              @if($order->status != 'approved')
                <button type="submit" class="btn btn-success">Update</button>
                <a href="{{ route('purchase-manager.purchase-orders.index') }}" class="btn btn-danger">Cancel</a>
              @endif
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
{{--JS for add/remove dynamic rows--}}
<script>
  (function(){
    let rowIndex = {{ $rows->count() }};
    const body = document.getElementById('itemsBody');
    function bindRowEvents(tr){
      tr.querySelector('.removeRow')?.addEventListener('click', ()=>{
        if(document.querySelectorAll('#itemsBody tr').length > 1){
          tr.remove();
        }
      });
    }
    document.querySelectorAll('#itemsBody tr').forEach(tr => bindRowEvents(tr));
    //Add new row
    document.getElementById('addRow').addEventListener('click', ()=>{
      const tr = document.createElement('tr');
      tr.innerHTML = `
        <td><input type="text" name="items[${rowIndex}][product_name]" class="form-control" placeholder="Product name" required></td>
        <td><input type="text" name="items[${rowIndex}][type]" class="form-control" placeholder="Type (e.g., pack, unit)"></td>
        <td><input type="number" step="1" min="1" name="items[${rowIndex}][quantity]" class="form-control" value="1" required></td>
        <td class="text-center"><button type="button" class="btn btn-sm btn-danger removeRow">&times;</button></td>
      `;
      body.appendChild(tr);
      bindRowEvents(tr);
      rowIndex++;
    });
  })();
</script>
@endsection