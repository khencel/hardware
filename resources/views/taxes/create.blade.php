@extends('homepage')

@section('header', 'Add Tax')

@section('content')
<div class="container mt-5">
    <a href="{{ route('taxes.index') }}" class="btn btn-info mb-3">
        <i class="bx bx-arrow-back"></i> Back
    </a>

    <div class="card shadow-sm p-4">
        <form action="{{ route('taxes.store') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-md-6">
                    {{-- Tax Name --}}
                    <div class="mb-3">
                        <label for="name" class="form-label">Tax Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                            id="name" name="name" value="{{ old('name') }}">
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Percentage --}}
                    <div class="mb-3">
                        <label for="percentage" class="form-label">Percentage (%)</label>
                        <input type="number" step="0.01" class="form-control @error('percentage') is-invalid @enderror"
                            id="percentage" name="percentage" value="{{ old('percentage') }}">
                        @error('percentage')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="col-md-6">
                    {{-- Description --}}
                    <div class="mb-3">
                        <label for="description" class="form-label">Description (Optional)</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                            id="description" name="description">{{ old('description') }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Active Toggle --}}
                    <div class="form-check mt-4">
                        <input class="form-check-input" type="checkbox" name="is_active" id="is_active"
                            {{ old('is_active', true) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            Active
                        </label>
                    </div>
                </div>
            </div>

            <button type="submit" class="btn btn-primary w-100 mt-4">
                <i class="bx bx-save"></i> Save Tax
            </button>
        </form>
    </div>
</div>
@endsection
