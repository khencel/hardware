@extends('homepage')

@section('header', 'Edit Discount')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('discounts.index') }}" class="btn btn-info">
            <i class="bx bx-arrow-back"></i> Back
        </a>

        <form action="{{ route('discounts.update', $discount->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="title_name" class="form-label">Discount Title</label>
                <input type="text" class="form-control" id="title_name" name="title_name"
                    value="{{ old('title_name', $discount->title_name) }}">
                @error('title_name')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="discount" class="form-label">Discount Amount</label>
                <input type="number" step="0.01" class="form-control" id="discount" name="discount"
                    value="{{ old('discount', $discount->discount) }}">
                @error('discount')
                    <div class="text-danger">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-check mb-3">
                <input class="form-check-input" type="checkbox" id="is_percentage" name="is_percentage" value="1"
                    {{ old('is_percentage', $discount->is_percentage) ? 'checked' : '' }}>
                <label class="form-check-label" for="is_percentage">
                    Is Percentage?
                </label>
            </div>

            <button type="submit" class="btn btn-success">Update Discount</button>
        </form>
    </div>
@endsection
