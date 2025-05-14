@extends('homepage')

@section('header')
    Add Item
@endsection

@section('content')
    <div class="container mt-5">
        <h2>Add Item</h2>

        <a href="{{ route('foods.index') }}" class="btn btn-secondary mb-3">Back</a>

        @if ($errors->any())
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        icon: 'error',
                        title: 'Oops...',
                        html: `{!! implode('<br>', $errors->all()) !!}`,
                        confirmButtonColor: '#d33'
                    });
                });
            </script>
        @endif

        <form action="{{ route('foods.store') }}" method="POST">
            @csrf

            {{--  <!-- Name -->  --}}
            <div class="mb-3">
                <label for="food_name" class="form-label">Item Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="food_name" name="name"
                    value="{{ old('name') }}">
                @error('name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Category -->  --}}
            <div class="mb-3">
                <label for="food_category" class="form-label">Category</label>
                <select class="form-select @error('category_id') is-invalid @enderror" id="food_category"
                    name="category_id">
                    <option value="">Select Category</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Price -->  --}}
            <div class="mb-3">
                <label for="food_price" class="form-label">Price</label>
                <input type="number" class="form-control @error('price') is-invalid @enderror" id="food_price"
                    name="price" value="{{ old('price') }}" step="0.01" oninput="updateMarginPercentage()">
                @error('price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Wholesale Price -->  --}}
            <div class="mb-3">
                <label for="food_wholesale_price" class="form-label">Wholesale Price</label>
                <input type="number" class="form-control @error('wholesale_price') is-invalid @enderror" id="food_wholesale_price"
                    name="wholesale_price" value="{{ old('wholesale_price') }}" step="0.01">
                @error('wholesale_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Retail Price -->  --}}
            <div class="mb-3">
                <label for="food_retail_price" class="form-label">Retail Price</label>
                <input type="number" class="form-control @error('retail_price') is-invalid @enderror" id="food_retail_price"
                    name="retail_price" value="{{ old('retail_price') }}" step="0.01">
                @error('retail_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Cost Price -->  --}}
            <div class="mb-3">
                <label for="food_cost_price" class="form-label">Cost Price</label>
                <input type="number" class="form-control @error('cost_price') is-invalid @enderror" id="food_cost_price"
                    name="cost_price" value="{{ old('cost_price') }}" step="0.01" oninput="updateMarginPercentage()">
                @error('cost_price')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Margin Percentage -->  --}}
            <div class="mb-3">
                <label for="food_margin_percentage" class="form-label">Margin Percentage</label>
                <input type="number" class="form-control @error('margin_percentage') is-invalid @enderror" id="food_margin_percentage"
                    name="margin_percentage" value="{{ old('margin_percentage') }}" step="0.01" oninput="updateSellingPrice()">
                @error('margin_percentage')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Quantity -->  --}}
            <div class="mb-3">
                <label for="food_quantity" class="form-label">Quantity</label>
                <input type="number" class="form-control @error('quantity') is-invalid @enderror" id="food_quantity"
                    name="quantity" value="{{ old('quantity') }}" step="1" min="0">
                @error('quantity')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Barcode -->  --}}
            <div class="mb-3">
                <label for="food_barcode" class="form-label">Barcode</label>
                <div class="input-group">
                    <input type="text" class="form-control @error('barcode') is-invalid @enderror" id="food_barcode"
                        name="barcode" value="{{ old('barcode', rand(1000000000000, 9999999999999)) }}" maxlength="13" placeholder="Enter barcode">
                    <button class="btn btn-outline-secondary" type="button" id="generate-barcode">Generate</button>
                </div>
                @error('barcode')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--  <!-- Availability -->  --}}
            <div class="mb-3">
                <label for="food_availability" class="form-label">Availability</label>
                <select class="form-select @error('is_available') is-invalid @enderror" id="food_availability"
                    name="is_available">
                    <option value="1" {{ old('is_available') == '1' ? 'selected' : '' }}>Available</option>
                    <option value="0" {{ old('is_available') == '0' ? 'selected' : '' }}>Unavailable</option>
                </select>
                @error('is_available')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Save</button>
        </form>
    </div>

    <script>
        // Function to calculate the margin percentage based on cost price and selling price
        function updateMarginPercentage() {
            const costPrice = parseFloat(document.getElementById('food_cost_price').value);
            const sellingPrice = parseFloat(document.getElementById('food_price').value);
            if (costPrice && sellingPrice && sellingPrice > costPrice) {
                const marginPercentage = ((sellingPrice - costPrice) / sellingPrice) * 100;
                document.getElementById('food_margin_percentage').value = marginPercentage.toFixed(2);
            }
        }

        // Function to calculate and update the selling price based on the margin percentage and cost price
        function updateSellingPrice() {
            const marginPercentage = parseFloat(document.getElementById('food_margin_percentage').value);
            const costPrice = parseFloat(document.getElementById('food_cost_price').value);
            if (marginPercentage && costPrice && marginPercentage > 0) {
                const sellingPrice = costPrice / (1 - marginPercentage / 100);
                document.getElementById('food_price').value = sellingPrice.toFixed(2);
            }
        }
    </script>
@endsection
