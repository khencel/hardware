@extends('homepage')

@section('header')
    My Profile
@endsection

@section('content')
    <style>
        .is-invalid {
            border: 1px solid red;
        }
    </style>
    <div class="container mt-5">
        <h2>Update Profile</h2>
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <form id="updateProfileForm" action="{{ route('users.profile') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') 
            <div class="row">
                <!-- First Name -->
                <div class="col-md-6 mb-3">
                    <label for="firstname" class="form-label">First Name</label>
                    <input type="text" class="form-control @error('firstname') is-invalid @enderror" id="firstname"
                        name="firstname" value="{{ old('firstname', auth()->user()->firstname) }}">
                        @error('firstname')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                </div>

                <!-- Last Name -->
                <div class="col-md-6 mb-3">
                    <label for="lastname" class="form-label">Last Name</label>
                    <input type="text" class="form-control @error('lastname') is-invalid @enderror" id="lastname"
                        name="lastname" value="{{ old('lastname', auth()->user()->lastname) }}">
                    @error('lastname')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Username -->
                <div class="col-md-6 mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control @error('username') is-invalid @enderror" id="username"
                        name="username" value="{{ old('username', auth()->user()->username) }}">
                    @error('username')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email"
                        name="email" value="{{ old('email', auth()->user()->email) }}">
                    @error('email')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="row">
                <!-- Active Status -->
                
                {{-- <div class="col-md-6 mb-3">
                    <label for="is_active" class="form-label">Active</label>
                    <select class="form-select @error('is_active') is-invalid @enderror" id="is_active" name="is_active">
                        <option value="1" {{ old('is_active', auth()->user()->is_active) == 1 ? 'selected' : '' }} d>
                            Active</option>
                        <option value="0" {{ old('is_active', auth()->user()->is_active) == 0 ? 'selected' : '' }}>
                            Inactive</option>
                    </select>
                    @error('is_active')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div> --}}

                <!-- Roles -->
                {{-- <div class="col-md-6 mb-3">
                    <label for="roles" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select form-control">
                        <option value="">Select Role</option>
                        @foreach ($roles as $key => $role)
                            <option value="{{ $key }}" @if (old('role', auth()->user()->roles()->pluck('role_id')->toArray()[0]) == $key) selected @endif>
                                {{ $role }}</option>
                        @endforeach
                    </select>
                </div> --}}
            </div>

            {{-- <div class="row">
                <!-- Password -->
                <div class="col-md-6 mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password"
                        name="password">
                    @error('password')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="col-md-6 mb-3">
                    <label for="password_confirmation" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                        id="password_confirmation" name="password_confirmation">
                    @error('password_confirmation')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div> --}}

            <div class="row">
                <!-- Void Password -->
                @php
                    $roles = auth()->user()->roles()->pluck('role_id')->toArray();
                @endphp
                @if (in_array(8, $roles) || in_array(12, $roles))
                    <div class="col-md-6 mb-3">
                        <label for="void_password" class="form-label">Void Password</label>
                        <input type="password" class="form-control @error('void_password') is-invalid @enderror"
                            id="void_password" name="void_password">
                        @error('void_password')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                @endif
            </div>

            <!-- Profile Image (Moved to Bottom) -->
            <div class="mb-3">
                <label for="image" class="form-label">Profile Image</label>
                <input type="file" class="form-control @error('image') is-invalid @enderror" id="image"
                    name="image" accept="image/*">
                @error('image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror

                <!-- Image Preview -->
                <div class="mt-3">
                    <img id="profilePreview"
                        src="{{ old('image', auth()->user()->image ? asset('storage/' . auth()->user()->image) : 'https://media.licdn.com/dms/image/v2/C5603AQF1WA6mvPPN7g/profile-displayphoto-shrink_400_400/profile-displayphoto-shrink_400_400/0/1655827862331?e=2147483647&v=beta&t=A0HGyBn7tNazpYnwQoiEMf4K_-fa9AZXAOLuQ-wXg0A') }}"
                        alt="Profile Image" class="img-thumbnail d-block mx-auto" width="150">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-primary">Update Profile</button>
            </div>
        </form>

    </div>
@endsection
@section('js')
    <script src="{{ asset('js/helper/app_helper.js') }}"></script>
    <script>
        document.getElementById('image').addEventListener('change', function(event) {
            let reader = new FileReader();
            reader.onload = function() {
                document.getElementById('profilePreview').src = reader.result;
            };
            reader.readAsDataURL(event.target.files[0]);
        });
    </script>
@endsection
