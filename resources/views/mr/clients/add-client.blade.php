@extends('mr.layouts.master')

<style>
    input[type=number]::-webkit-inner-spin-button,
    input[type=number]::-webkit-outer-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }
    input[type=number] {
        -moz-appearance: textfield;
    }
</style>

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add New Client</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('mr.clients.store') }}" method="POST">
                            @csrf

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
                                                        data-id="{{ $category->id }}">
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
                                                        {{ $field->validation_type === 'address' ? 'address-input' : '' }}">
                                                    </textarea>
                                                </div>
                                            </div>
                                        @endif

                                        {{-- DROPDOWN --}}
                                        @if ($field->type === 'dropdown')
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label>{{ $field->label }}</label>
                                                    <select name="{{ $field->name }}"
                                                            class="form-control
                                                            {{ $field->validation_type === 'name' ? 'name-input' : '' }}
                                                            {{ $field->validation_type === 'contact' ? 'contact-input' : '' }}
                                                            {{ $field->validation_type === 'address' ? 'address-input' : '' }}">
                                                        <option value="">Select {{ $field->label }}</option>
                                                        @foreach ($field->options ?? [] as $opt)
                                                            <option value="{{ $opt }}">{{ $opt }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                        @endif

                                    @endforeach

                                </div>

                            @endforeach

                            <div class="card-action mt-3">
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

<!-- ================= JS ================= -->
<script>
document.addEventListener("DOMContentLoaded", function() {

    const categorySelect = document.getElementById('category_select');

    categorySelect.addEventListener('change', function() {

        // Hide all field blocks
        document.querySelectorAll('.category-fields').forEach(div => {
            div.style.display = 'none';

            div.querySelectorAll('input, textarea, select').forEach(el => {
                el.removeAttribute('required');
                el.setAttribute('disabled', true);   // 🔥 important
            });
        });


        const selectedOption = this.options[this.selectedIndex];
        const categoryId = selectedOption.getAttribute('data-id');

        if (categoryId) {

            const activeDiv = document.querySelector(
                `.category-fields[data-category-id="${categoryId}"]`
            );

            if (activeDiv) {
                activeDiv.style.display = 'flex';

                // Add required only to visible fields
                activeDiv.querySelectorAll('input, textarea, select').forEach(el => {
                    el.setAttribute('required', true);
                      el.removeAttribute('disabled');  
                });
            }
        }
    });

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
