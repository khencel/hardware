@extends('homepage')

@section('header', 'Add Item Category')

@section('content')
    <div class="container mt-5">
        <h2>Add Item Category</h2>
        <a href="{{ route('food-categories.index') }}" class="btn btn-secondary mb-3">Back</a>

        <form action="{{ route('food-categories.store') }}" method="POST">
            @csrf

            <div class="mb-3">
                <label for="category_name" class="form-label">Category Name</label>
                <input type="text" class="form-control" id="category_name" name="name" value="{{ old('name') }}">
                @error('name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="is_available" class="form-label">Availability</label>
                <select class="form-select" id="is_available" name="is_available">
                    <option value="1" selected>Available</option>
                    <option value="0">Unavailable</option>
                </select>
                @error('is_available')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>
@endsection
