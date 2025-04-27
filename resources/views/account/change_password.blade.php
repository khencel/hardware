@extends('homepage')

@section('header')
    Change Password
@endsection

@section('content')
<div class="container">
    <h2>Change Password</h2>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">  
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <script>
            setTimeout(function () {
                fetch("{{ route('auth.logout') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}"
                    },
                }).then(() => {
                    window.location.href = "{{ route('login') }}"; // redirect after logout
                });
            }, 3000);
        </script>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('password.update') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="current_password" class="form-label">Current Password</label>
            <div class="input-group">
                <input type="password" name="current_password" id="current_password" class="form-control" required>
                <button type="button" class="input-group-text toggle-password" data-target="current_password">
                    <i class="bx bx-show" id="icon-current_password"></i>
                </button>
            </div>
            @error('current_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="new_password" class="form-label">New Password</label>
            <div class="input-group">
                <input type="password" name="new_password" id="new_password" class="form-control" required>
                <button type="button" class="input-group-text toggle-password" data-target="new_password">
                    <i class="bx bx-show" id="icon-new_password"></i>
                </button>
            </div>
            @error('new_password')
                <span class="text-danger">{{ $message }}</span>
            @enderror
        </div>

        <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Confirm New Password</label>
            <div class="input-group">
                <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                <button type="button" class="input-group-text toggle-password" data-target="new_password_confirmation">
                    <i class="bx bx-show" id="icon-new_password_confirmation"></i>
                </button>
            </div>
            @error('new_password_confirmation')
            <span class="text-danger">{{ $message }}</span>
        @enderror
        </div>

        <button type="submit" class="btn btn-primary">Change Password</button>
    </form>
</div>

@section('scripts')
<script>
    $(".toggle-password").click(function() {
        let input = $("#" + $(this).data("target"));
        let icon = $(this).find("i");

        if (input.attr("type") === "password") {
            input.attr("type", "text");
            icon.removeClass("bx-show").addClass("bx-hide");
        } else {
            input.attr("type", "password");
            icon.removeClass("bx-hide").addClass("bx-show");
        }
    });
</script>
@endsection

@endsection
