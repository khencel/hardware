@extends('homepage')

@section('header', 'Edit Setting')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('option.index') }}" class="btn btn-info mb-3">
            <i class="bx bx-arrow-back"></i> Back
        </a>
        <div class="card shadow-sm p-4">
            <form action="{{ route('option.update', $option->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                   value="{{ old('name', $option->name) }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="type" class="form-label">Type</label>
                            <input type="text" class="form-control" id="type" name="type"
                                   value="{{ old('type', $option->type) }}">
                            @error('type')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="value" class="form-label">Value</label>
                            <textarea class="form-control" id="value" name="value" rows="3">{{ old('value', $option->value) }}</textarea>
                            @error('value')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description', $option->description) }}</textarea>
                            @error('description')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="form-check mt-2">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   {{ old('is_active', $option->is_active) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_active">Is Active</label>
                        </div>
                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Update Setting</button>
            </form>
        </div>
    </div>
@endsection
