@extends('homepage')

@section('header')
    Item Management
@endsection

@section('content')
    <div class="container mt-5">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <div class="row">
            <div class="col-6">
                <a href="{{ route('foods.create') }}" class="btn btn-primary mb-3">
                    <i class='bx bx-plus'></i> Add Item
                </a>
            </div>
            <div class="col-6">
                <div class="row justify-content-end right">
                    <form method="GET" action="{{ route('foods.index') }}" class="mb-3 d-flex align-items-center gap-2">
                        <div class="col-2">
                            <label for="category" class="form-label mb-0">Filter by:</label>
                        </div>
                        <div class="col-10">
                            <select name="category" id="category" class="form-select w-auto" onchange="this.form.submit()">
                                <option value="">All Categories</option>
                                @foreach ($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($foods as $key => $food)
                    <tr>
                        <td>{{ $key + 1 + (($foods->currentPage() - 1) * $foods->perPage()) }}</td>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->category?->name ?? 'no category selected' }}</td>
                        <td>
                            <span class="badge bg-{{ $food->is_available ? 'success' : 'danger' }}">
                                {{ $food->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td>
                            <a href="#" 
                                class="btn btn-info btn-sm view-btn" 
                                data-bs-toggle="modal" 
                                data-bs-target="#itemModal"
                                data-name="{{ $food->name }}"
                                data-category="{{ $food->category?->name ?? 'No category' }}"
                                data-cost="₱{{ number_format($food->cost_price, 2) }}"
                                data-price="₱{{ number_format($food->price, 2) }}"
                                data-margin="{{ $food->margin_percentage }}%"
                                data-availability="{{ $food->is_available ? 'Available' : 'Unavailable' }}"
                                data-wholesale-price="₱{{ number_format($food->wholesale_price, 2) }}"
                                data-retail-price="₱{{ number_format($food->retail_price, 2) }}">
                                    <i class='bx bx-show'></i> View
                                </a>

                            <a href="{{ route('foods.edit', $food->id) }}" class="btn btn-warning btn-sm">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <form action="{{ route('foods.destroy', $food->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class='bx bx-trash'></i>
                                    Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center"><i>No data found...</i></td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $foods->links() }}
        </div>
    </div>

    <!-- Item Details Modal -->
    <div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Item Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="modal-name"></span></p>
                    <p><strong>Category:</strong> <span id="modal-category"></span></p>
                    <p><strong>Capital Cost:</strong> <span id="modal-cost"></span></p>
                    <p><strong>Selling Price:</strong> <span id="modal-price"></span></p>
                    <p><strong>Wholesale Price:</strong> <span id="modal-wholesale-price"></span></p>
                    <p><strong>Retail Price:</strong> <span id="modal-retail-price"></span></p>
                    <p><strong>Margin %:</strong> <span id="modal-margin"></span></p>
                    <p><strong>Availability:</strong> <span id="modal-availability"></span></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal JS Script -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const viewButtons = document.querySelectorAll('.view-btn');

            viewButtons.forEach(btn => {
                btn.addEventListener('click', function () {
                    document.getElementById('modal-name').textContent = this.dataset.name;
                    document.getElementById('modal-category').textContent = this.dataset.category;
                    document.getElementById('modal-cost').textContent = this.dataset.cost;
                    document.getElementById('modal-price').textContent = this.dataset.price;
                    document.getElementById('modal-margin').textContent = this.dataset.margin;
                    document.getElementById('modal-availability').textContent = this.dataset.availability;
                    document.getElementById('modal-wholesale-price').textContent = this.dataset.wholesalePrice;
                    document.getElementById('modal-retail-price').textContent = this.dataset.retailPrice;

                });
            });
        });
    </script>
@endsection
