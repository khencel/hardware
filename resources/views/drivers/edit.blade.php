@extends('homepage')

@section('header', 'Edit Customer')

@section('content')
    <div class="container mt-5">
        <a href="{{ route('drivers.index') }}" class="btn btn-info mb-3">
            <i class="bx bx-arrow-back"></i> Back
        </a>
        <div class="card shadow-sm p-4">
            <form action="{{ route('drivers.update', $driver->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="row">
                    <!-- Left Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Driver Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="{{ old('name', $driver->name) }}">
                            @error('name')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email"
                                value="{{ old('email', $driver->email) }}">
                            @error('email')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="phone" class="form-label">Phone</label>
                            <input type="text" class="form-control" id="phone" name="phone"
                                value="{{ old('phone', $driver->phone) }}">
                            @error('phone')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <!-- Right Column -->
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="address" class="form-label">Address</label>
                            <input type="text" class="form-control" id="address" name="address"
                                value="{{ old('address', $driver->address) }}">
                            @error('address')
                                <div class="text-danger">{{ $message }}</div>
                            @enderror
                        </div>


                    </div>
                </div>
                <button type="submit" class="btn btn-primary w-100 mt-3">Update Driver</button>
            </form>
        </div>
    </div>
@endsection
