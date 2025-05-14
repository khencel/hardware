@extends('homepage')

@section('header', 'Item Category')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('food-categories.create') }}" class="btn btn-primary mb-3">
            <i class="bx bx-plus"></i> Add Category
        </a>

        @if (session('success'))
            <script>
                document.addEventListener("DOMContentLoaded", function () {
                    Swal.fire({
                        toast: true,
                        icon: 'success',
                        title: @json(session('success')),
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000,
                        timerProgressBar: true,
                    });
                });
            </script>
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
                            <form action="{{ route('food-categories.destroy', $category->id) }}" method="POST" class="d-inline delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="btn btn-danger btn-sm delete-btn" data-name="{{ $category->name }}">
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

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function () {
                    const form = this.closest('form');
                    const itemName = this.dataset.name || 'this category';

                    Swal.fire({
                        title: "Are you sure?",
                        text: `You won't be able to revert deleting "${itemName}"!`,
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Yes, delete it!"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>
