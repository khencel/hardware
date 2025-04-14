@extends('homepage')

@section('header', 'Edit Item Category')

@section('content')
    <div class="container mt-5">
        <h2>Edit Food Category</h2>
        <a href="{{ route('food-categories.index') }}" class="btn btn-secondary mb-3">Back</a>

        <form action="{{ route('food-categories.update', $foodCategory->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="category_name"  name="name"
                    value="{{ old('name', $foodCategory->name) }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="is_available" class="form-label">Availability</label>
                <select class="form-select" id="is_available" name="is_available">
                    <option value="1" {{ $foodCategory->is_available ? 'selected' : '' }}>Available</option>
                    <option value="0" {{ !$foodCategory->is_available ? 'selected' : '' }}>Unavailable</option>
                </select>
                @error('is_available')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Update</button>
        </form>
    </div>
@endsection
