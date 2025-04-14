@extends('homepage')

@section('header')
    Item Management
@endsection

@section('content')
    <div class="container mt-5">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('foods.create') }}" class="btn btn-primary mb-3">
            <i class='bx bx-plus'></i> Add Item
        </a>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($foods as $food)
                    <tr>
                        <td>{{ $food->id }}</td>
                        <td>{{ $food->name }}</td>
                        <td>{{ $food->category->name }}</td>
                        <td>₱{{ number_format($food->price, 2) }}</td>
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
