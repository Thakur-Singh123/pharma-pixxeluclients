@extends('mr.layouts.master')

@section('content')

<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        @if($client_detail->status != 'Approved')
                            <h4 class="card-title">Edit Client</h4>
                        @else
                            <h4 class="card-title">Client Detail</h4>
                        @endif
                    </div>

                    <div class="card-body">
                        <form action="{{ route('mr.clients.update', $client->id) }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- CATEGORY -->
                            <div class="row">
                                <div class="col-md-6 col-lg-4">
                                    <div class="form-group">
                                        <label>Category</label>

                                        <select name="category_type"
                                                id="category_select"
                                                class="form-control"
                                                required>

                                            <option value="">Select Category</option>

                                            @foreach ($client_categories as $category)
                                                <option value="{{ $category->name }}"
                                                        data-id="{{ $category->id }}"
                                                        {{ $client->category_type == $category->name ? 'selected' : '' }}>
                                                    {{ ucfirst($category->name) }}
                                                </option>
                                            @endforeach

                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- DYNAMIC FIELDS -->
                            @foreach ($client_categories as $category)

                                <div class="row category-fields"
                                     data-category-id="{{ $category->id }}"
                                     style="display:none;">

                                    @foreach ($category->fields as $field)

                                        {{-- INPUT --}}
                                        @if ($field->type === 'input')
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ $field->label }}</label>

                                                    <input
                                                        type="{{ $field->input_type ?? 'text' }}"
                                                        name="{{ $field->name }}"
                                                        value="{{ old($field->name, $client->details[$field->name] ?? '') }}"
                                                        class="form-control
                                                        {{ $field->validation_type === 'name' ? 'name-input' : '' }}
                                                        {{ $field->validation_type === 'contact' ? 'contact-input' : '' }}
                                                        {{ $field->validation_type === 'address' ? 'address-input' : '' }}">
                                                </div>
                                            </div>
                                        @endif

                                        {{-- TEXTAREA --}}
                                        @if ($field->type === 'textarea')
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>{{ $field->label }}</label>

                                                    <textarea
                                                        name="{{ $field->name }}"
                                                        rows="3"
                                                        class="form-control
                                                        {{ $field->validation_type === 'name' ? 'name-input' : '' }}
                                                        {{ $field->validation_type === 'contact' ? 'contact-input' : '' }}
                                                        {{ $field->validation_type === 'address' ? 'address-input' : '' }}">{{ old($field->name, $client->details[$field->name] ?? '') }}</textarea>
                                                </div>
                                            </div>
                                        @endif

                                    @endforeach

                                </div>

                            @endforeach

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

{{-- ================= JS ================= --}}
<script>
document.addEventListener("DOMContentLoaded", function() {

    const categorySelect = document.getElementById('category_select');

    function toggleFields() {

        // Hide all blocks
        document.querySelectorAll('.category-fields').forEach(div => {
            div.style.display = 'none';

            div.querySelectorAll('input, textarea, select').forEach(el => {
                el.removeAttribute('required');
                el.setAttribute('disabled', true);
            });
        });

        const selectedOption = categorySelect.options[categorySelect.selectedIndex];
        const categoryId = selectedOption?.getAttribute('data-id');

        if (categoryId) {

            const activeDiv = document.querySelector(
                `.category-fields[data-category-id="${categoryId}"]`
            );

            if (activeDiv) {
                activeDiv.style.display = 'flex';

                activeDiv.querySelectorAll('input, textarea, select').forEach(el => {
                    el.removeAttribute('disabled');
                    el.setAttribute('required', true);
                });
            }
        }
    }

    categorySelect.addEventListener('change', toggleFields);

    // 🔥 Important: page load pe run karo
    toggleFields();


    /* NAME VALIDATION */
    document.addEventListener('input', function(e){
        if(e.target.classList.contains('name-input')){
            e.target.value = e.target.value.replace(/[^a-zA-Z\s]/g,'');
        }
    });

    /* CONTACT VALIDATION */
    document.addEventListener('input', function(e){
        if(e.target.classList.contains('contact-input')){
            e.target.value = e.target.value.replace(/\D/g,'').slice(0,10);
        }
    });

    /* ADDRESS VALIDATION */
    document.addEventListener('input', function(e){
        if(e.target.classList.contains('address-input')){
            e.target.value = e.target.value.replace(/[^a-zA-Z0-9\s]/g,'');
        }
    });

});
</script>

@endsection
