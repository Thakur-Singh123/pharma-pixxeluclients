@extends('manager.layouts.master')

@section('content')
<div class="container">
    <div class="page-inner">
        <div class="row">
            <div class="col-md-12">

                @if (session('success'))
                    <div class="alert alert-success">{{ session('success') }}</div>
                @endif

                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Add Client Category</h4>
                    </div>

                    <div class="card-body">
                        <form action="{{ route('manager.client.category.store') }}" method="POST">
                            @csrf

                            <!-- Category -->
                            <div class="form-group">
                                <label>Category</label>
                                <input type="text" name="name" class="form-control {{ $errors->has('name') ? 'is-invalid' : '' }}"
                                       placeholder="Enter Category" value="{{ old('name') }}" required>
                                @error('name')
                                    <span class="invalid-feedback d-block">{{ $message }}</span>
                                @enderror
                            </div>

                            <!-- Status -->
                            <div class="form-group">
                                <label>Status</label>
                                <select name="status" class="form-control" required>
                                    <option value="">Select Status</option>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                </select>
                            </div>

                            <hr>
                            <h5>Category Fields</h5>

                            <!-- Fields Wrapper -->
                            <div id="fields-wrapper">

                                <!-- Default Field -->
                                <div class="row field-row mb-2 align-items-start">
                                    <div class="col-md-2">
                                        <input type="text"
                                               name="fields[0][label]"
                                               class="form-control"
                                               placeholder="Field Label" required>
                                    </div>

                                    <div class="col-md-2">
                                        <input type="text"
                                               name="fields[0][name]"
                                               class="form-control field-name"
                                               placeholder="Field Name" required>
                                    </div>

                                    <div class="col-md-2">
                                        <select name="fields[0][type]"
                                                class="form-control field-type" required>
                                            <option value="">Field Type</option>
                                            <option value="input">Input</option>
                                            <option value="textarea">Textarea</option>
                                            <option value="dropdown">Dropdown</option>
                                        </select>
                                    </div>

                                    <!-- Input Type (for input only) -->
                                    <div class="col-md-2 input-type-wrapper">
                                        <select name="fields[0][input_type]"
                                                class="form-control">
                                            <option value="">Input Type</option>
                                            <option value="text">Text</option>
                                            <option value="number">Number</option>
                                            <option value="url">URL</option>
                                        </select>
                                    </div>

                                    <!-- Validation Type -->
                                    <div class="col-md-2 validation-type-wrapper">
                                        <select name="fields[0][validation_type]" class="form-control">
                                            <option value="">Validation Type</option>
                                            <option value="name">name</option>
                                            <option value="contact">contact</option>
                                            <option value="address">address</option>
                                            <option value="none">none</option>
                                        </select>
                                    </div>

                                    <div class="col-md-2">
                                        <button type="button"
                                                class="btn btn-danger remove-field">X</button>
                                    </div>

                                    <!-- Dropdown options (shown when type=dropdown) -->
                                    <div class="col-12 mt-2 dropdown-options-wrapper" style="display:none;">
                                        <label class="small text-muted">Dropdown options (add one per line or use +)</label>
                                        <div class="dropdown-options-list">
                                            <div class="input-group input-group-sm mb-1">
                                                <input type="text" name="fields[0][options][]" class="form-control" placeholder="Option 1">
                                                <button type="button" class="btn btn-outline-secondary add-dropdown-option">+</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <button type="button"
                                    class="btn btn-primary mt-2"
                                    id="add-field">+ Add Field</button>

                            <div class="mt-4">
                                <button type="submit"
                                        class="btn btn-success">Submit</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- ===================== SCRIPT ===================== -->
<script>
document.addEventListener('DOMContentLoaded', function () {

    let fieldIndex = 1;
    const addBtn = document.getElementById('add-field');
    const wrapper = document.getElementById('fields-wrapper');

    /* -------- ADD FIELD -------- */
    addBtn.addEventListener('click', function () {

        let html = `
        <div class="row field-row mb-2 align-items-start">
            <div class="col-md-2">
                <input type="text"
                       name="fields[${fieldIndex}][label]"
                       class="form-control"
                       placeholder="Field Label" required>
            </div>

            <div class="col-md-2">
                <input type="text"
                       name="fields[${fieldIndex}][name]"
                       class="form-control field-name"
                       placeholder="Field Name" required>
            </div>

            <div class="col-md-2">
                <select name="fields[${fieldIndex}][type]"
                        class="form-control field-type" required>
                    <option value="">Field Type</option>
                    <option value="input">Input</option>
                    <option value="textarea">Textarea</option>
                    <option value="dropdown">Dropdown</option>
                </select>
            </div>

            <div class="col-md-2 input-type-wrapper">
                <select name="fields[${fieldIndex}][input_type]"
                        class="form-control">
                    <option value="">Input Type</option>
                    <option value="text">Text</option>
                    <option value="number">Number</option>
                    <option value="url">URL</option>
                </select>
            </div>
            <div class="col-md-2 validation-type-wrapper">
                <select name="fields[${fieldIndex}][validation_type]"
                        class="form-control">
                    <option value="">Validation Type</option>
                    <option value="name">name</option>
                    <option value="contact">contact</option>
                    <option value="address">address</option>
                    <option value="none">none</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="button"
                        class="btn btn-danger remove-field">X</button>
            </div>

            <div class="col-12 mt-2 dropdown-options-wrapper" style="display:none;">
                <label class="small text-muted">Dropdown options (add one per line or use +)</label>
                <div class="dropdown-options-list">
                    <div class="input-group input-group-sm mb-1">
                        <input type="text" name="fields[${fieldIndex}][options][]" class="form-control" placeholder="Option 1">
                        <button type="button" class="btn btn-outline-secondary add-dropdown-option">+</button>
                    </div>
                </div>
            </div>
        </div>`;
        wrapper.insertAdjacentHTML('beforeend', html);
        fieldIndex++;
    });

    /* -------- REMOVE FIELD -------- */
    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-field')) {
            e.target.closest('.field-row').remove();
        }
    });

    /* -------- SHOW / HIDE INPUT TYPE & DROPDOWN OPTIONS -------- */
    document.addEventListener('change', function (e) {
        if (!e.target.classList.contains('field-type')) return;

        const row = e.target.closest('.field-row');
        const inputTypeWrapper = row.querySelector('.input-type-wrapper');
        const dropdownOptionsWrapper = row.querySelector('.dropdown-options-wrapper');

        if (inputTypeWrapper) {
            inputTypeWrapper.style.display = (e.target.value === 'input') ? 'block' : 'none';
            const inputTypeSelect = inputTypeWrapper.querySelector('select');
            if (inputTypeSelect) inputTypeSelect.required = (e.target.value === 'input');
        }
        if (dropdownOptionsWrapper) dropdownOptionsWrapper.style.display = (e.target.value === 'dropdown') ? 'block' : 'none';
    });

    /* -------- ADD DROPDOWN OPTION -------- */
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('add-dropdown-option')) return;

        const list = e.target.closest('.dropdown-options-list');
        const row = e.target.closest('.field-row');
        const fieldName = row.querySelector('.field-type').name; // e.g. fields[0][type]
        const match = fieldName.match(/fields\[(\d+)\]/);
        const idx = match ? match[1] : '0';
        const count = list.querySelectorAll('.input-group').length + 1;

        const div = document.createElement('div');
        div.className = 'input-group input-group-sm mb-1';
        div.innerHTML = `
            <input type="text" name="fields[${idx}][options][]" class="form-control" placeholder="Option ${count}">
            <button type="button" class="btn btn-outline-danger remove-dropdown-option">−</button>
        `;
        list.appendChild(div);
    });

    /* -------- REMOVE DROPDOWN OPTION -------- */
    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('remove-dropdown-option')) return;
        const list = e.target.closest('.dropdown-options-list');
        if (list && list.querySelectorAll('.input-group').length > 1) {
            e.target.closest('.input-group').remove();
        }
    });

    /* -------- FIELD NAME RULE (a-z + _) -------- */
    document.addEventListener('keypress', function (e) {
        if (!e.target.classList.contains('field-name')) return;

        if (e.key === ' ' || e.key === 'Enter') {
            e.preventDefault();
        }
    });

    document.addEventListener('input', function (e) {
        if (!e.target.classList.contains('field-name')) return;

        e.target.value = e.target.value
            .toLowerCase()
            .replace(/[^a-z_]/g, '');
    });

});
</script>
@endsection
