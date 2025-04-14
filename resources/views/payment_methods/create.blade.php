@extends('homepage')

@section('header')
    Add Payment Method
@endsection

@section('content')
    <div class="container mt-4">
        <a href="{{ route('payment-methods.index') }}" class="btn btn-secondary mb-3">Back</a>

        <form action="{{ route('payment-methods.store') }}" method="POST">
            @csrf

            <!-- Name Field -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name"
                    value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Type Field -->
            {{-- <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <input type="text" class="form-control @error('type') is-invalid @enderror" id="type" name="type"
                    value="{{ old('type') }}">
                @error('type')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> --}}

            <!-- Status Field -->
            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-select @error('status') is-invalid @enderror" id="status" name="status">
                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Submit Button -->
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
@endsection
