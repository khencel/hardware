@extends('homepage')

@section('header', 'Inventory Management - Edit Item')

@section('content')
    <a href="{{ route('inventories.index') }}" class="btn btn-info">
        <i class="bx bx-arrow-back"></i> Back
    </a>

    <div class="container mt-4">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <div class="card shadow-lg border-0">
                    <div class="card-header bg-warning text-white text-center">
                        <h4 class="mb-0"><i class="bx bx-edit"></i> Edit Inventory Item</h4>
                    </div>
                    <div class="card-body p-4">
                        <p class="text-muted text-center">Update the details of the inventory item.</p>

                        <form action="{{ route('inventories.update', $inventory->id) }}" method="POST"
                            enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="item_name" class="form-label">
                                        <i class="bx bx-tag"></i> Item Name
                                    </label>
                                    <input type="text" class="form-control @error('item_name') is-invalid @enderror"
                                        id="item_name" name="item_name"
                                        value="{{ old('item_name', $inventory->item_name) }}">
                                    @error('item_name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label">
                                        <i class="bx bx-list-ul"></i> Category
                                    </label>
                                    <input type="text" class="form-control @error('category') is-invalid @enderror"
                                        id="category" name="category" value="{{ old('category', $inventory->category) }}">
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-4 mb-3">
                                    <label for="quantity" class="form-label">
                                        <i class="bx bx-hash"></i> Quantity
                                    </label>
                                    <input type="number" class="form-control @error('quantity') is-invalid @enderror"
                                        id="quantity" name="quantity" value="{{ old('quantity', $inventory->quantity) }}">
                                    @error('quantity')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="unit_price" class="form-label">
                                        <i class="bx bx-dollar"></i> Unit Price
                                    </label>
                                    <input type="number" step="0.01"
                                        class="form-control @error('unit_price') is-invalid @enderror" id="unit_price"
                                        name="unit_price" value="{{ old('unit_price', $inventory->unit_price) }}">
                                    @error('unit_price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label for="total_cost" class="form-label">
                                        <i class="bx bx-calculator"></i> Total Cost
                                    </label>
                                    <input type="text" class="form-control bg-light" id="total_cost" readonly>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="supplier" class="form-label">
                                        <i class="bx bx-truck"></i> Supplier
                                    </label>
                                    <input type="text" class="form-control @error('supplier') is-invalid @enderror"
                                        id="supplier" name="supplier" value="{{ old('supplier', $inventory->supplier) }}">
                                    @error('supplier')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="location" class="form-label">
                                        <i class="bx bx-map"></i> Location
                                    </label>
                                    <input type="text" class="form-control @error('location') is-invalid @enderror"
                                        id="location" name="location" value="{{ old('location', $inventory->location) }}">
                                    @error('location')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="status" class="form-label">
                                        <i class="bx bx-clipboard"></i> Status
                                    </label>
                                    <select class="form-control @error('status') is-invalid @enderror" id="status"
                                        name="status">
                                        <option value="in stock"
                                            {{ old('status', $inventory->status) == 'in stock' ? 'selected' : '' }}>In
                                            Stock</option>
                                        <option value="out of stock"
                                            {{ old('status', $inventory->status) == 'out of stock' ? 'selected' : '' }}>Out
                                            of Stock</option>
                                    </select>
                                    @error('status')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label for="image" class="form-label">
                                        <i class="bx bx-image"></i> Upload Image
                                    </label>
                                    <input type="file" class="form-control @error('image') is-invalid @enderror"
                                        id="image" name="image" accept="image/*" onchange="previewImage(event)">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="imagePreview" src="{{ asset('storage/' . $inventory->image) }}"
                                            class="img-fluid rounded shadow" style="max-width: 200px;">
                                    </div>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-warning btn-lg">
                                    <i class="bx bx-save"></i> Update Item
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            updateTotalCost(); 

            document.getElementById('unit_price').addEventListener('input', updateTotalCost);
            document.getElementById('quantity').addEventListener('input', updateTotalCost);
        });

        function updateTotalCost() {
            let quantity = document.getElementById('quantity').value || 0;
            let unitPrice = document.getElementById('unit_price').value || 0;
            document.getElementById('total_cost').value = (quantity * unitPrice).toFixed(2);
        }
    </script>
@endsection
