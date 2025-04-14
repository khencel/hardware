<!DOCTYPE html>
<!-- Coding By CodingNepal - www.codingnepalweb.com -->
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hardware</title>
    <link rel="stylesheet" href="{{ asset('css/login.css') }}">
    <link rel="stylesheet" href="{{ asset('css/bootstrap.css') }}">
    <script src="{{ asset('js/bootstrap.js') }}"></script>
    <style>
        .error-message {
            color: red;
            font-size: 0.9em;
            margin-top: 5px;
        }
    </style>
</head>

<body>
    <div class="wrapper">
        <div class="row justify-content-center">
            <div class="col-8">
                {{-- <img class="img-fluid" src="pantukan_logo.png" alt=""> --}}
            </div>
        </div>
        <form action="{{ route('auth.login') }}" method="POST">
            <h2>Login</h2>
            @csrf

            <!-- Username Field -->
            <div class="input-field">
                <input type="text" name="username" value="{{ old('username') }}" required>
                <label>Enter your username</label>
                
            </div>

            <!-- Password Field -->
            <div class="input-field">
                <input type="password" name="password" required>
                <label>Enter your password</label>
            </div>

            @error('username')
                    <div class="error-message">{{ $message }}</div>
            @enderror
            @error('password')
                <div class="error-message">{{ $message }}</div>
            @enderror

            <!-- Remember Me and Forgot Password Section -->
            <div class="forget">
                <label for="remember">
                    <input type="checkbox" id="remember" name="remember">
                    <p>Remember me</p>
                </label>
                <a href="#">Forgot password?</a>
            </div>

            <!-- Submit Button -->
            <button type="submit">Log In</button>
        </form>

    </div>
</body>

</html>
