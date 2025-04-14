@extends('homepage')

@section('header', 'Item Category')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('food-categories.create') }}" class="btn btn-primary mb-3">
            <i class="bx bx-plus"></i> Add Category
        </a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Availability</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($categories as $category)
                    <tr>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->is_available ? 'Available' : 'Unavailable' }}</td>
                        <td>
                            <a href="{{ route('food-categories.edit', $category->id) }}" class="btn btn-warning btn-sm"> <i
                                    class="bx bx-pencil"></i> Edit</a>
                            <form action="{{ route('food-categories.destroy', $category->id) }}" method="POST"
                                style="display:inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"
                                    onclick="return confirm('Are you sure you want to delete this category?');">
                                    <i class="bx bx-trash"></i> Delete
                                </button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="text-center"><i>No data found...</i></td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $categories->links() }}
    </div>
@endsection
