@extends('homepage')

@section('header', 'Add Setting') 

@section('content')
    <div class="container mt-5">
        <a href="{{ route('option.index') }}" class="btn btn-info mb-3">
            <i class="bx bx-arrow-back"></i> Back
        </a>
        <div class="card shadow-sm p-4">
            <form action="{{ route('option.store') }}" method="POST">
                @csrf
                <div class="row">
            
                    <div class="mb-3">
                        <label for="name" class="form-label">Name (unique)</label>
                        <input type="text" class="form-control" id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="type" class="form-label">Type</label>
                        <input type="text" class="form-control" id="type" name="type" value="{{ old('type') }}" readonly>
                        @error('type')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="value" class="form-label">Value</label>
                        <textarea class="form-control" id="value" name="value" rows="3">{{ old('value') }}</textarea>
                        @error('value')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-check mt-3 ">
                        <input type="checkbox" class="form-check-input " id="is_active" name="is_active" {{ old('is_active') ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Is Active</label>
                        @error('is_active')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <button type="submit" class="btn btn-primary w-100 mt-3">Save Setting</button>
            </form>
        </div>
    </div>
@endsection

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.getElementById('name');
            const typeInput = document.getElementById('type');

            const toSnakeCase = (text) => {
                return text
                    .toString()
                    .normalize('NFD')                   // normalize accented characters
                    .replace(/[\u0300-\u036f]/g, '')    // remove accents
                    .toLowerCase()
                    .trim()
                    .replace(/[^a-z0-9 ]/g, '')         // remove non-alphanumeric and non-space
                    .replace(/\s+/g, '_');              // replace spaces with underscores
            };

            nameInput.addEventListener('input', () => {
                typeInput.value = toSnakeCase(nameInput.value);
            });
        });
    </script>


