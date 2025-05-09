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
                    </form
                </div>
            </div>
        </div>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Capital Cost</th>
                    <th>Selling Price</th>
                    <th>Margin %</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($foods as $key => $food)
                    <tr>
                        <td>{{ $key + 1 + (($foods->currentPage() - 1) * $foods->perPage()) }}</td>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->category->name }}</td>
                        <td>₱{{ number_format($food->cost_price, 2) }}</td>
                        <td>₱{{ number_format($food->price, 2) }}</td>
                        <td>{{ $food->margin_percentage}} %</td>
                        <td>
                            <span class="badge bg-{{ $food->is_available ? 'success' : 'danger' }}">
                                {{ $food->is_available ? 'Available' : 'Unavailable' }}
                            </span>
                        </td>
                        <td>
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
                        <td colspan="6" class="text-center"><i>No data found...</i></td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $foods->links() }}
        </div>
    </div>
@endsection
