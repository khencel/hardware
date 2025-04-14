@extends('homepage')

@section('header')
    Payment Method Management
@endsection

@section('content')
    <div class="container mt-5">

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <a href="{{ route('payment-methods.create') }}" class="btn btn-primary mb-3">
            <i class='bx bx-plus'></i> Add Payment Method
        </a>

        <table class="table table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($paymentMethods as $method)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $method->name }}</td>
                        <td>
                            <span class="badge bg-{{ $method->status == 'active' ? 'success' : 'danger' }}">
                                {{ $method->status == 'active' ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('payment-methods.edit', $method->id) }}" class="btn btn-warning btn-sm">
                                <i class='bx bx-edit'></i> Edit
                            </a>
                            <form action="{{ route('payment-methods.destroy', $method->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm"><i class='bx bx-trash'></i>
                                    Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" class="text-center"><i>No data found...</i></td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $paymentMethods->links() }}
        </div>
    </div>
@endsection
