<!DOCTYPE html>
<html>

<head>
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
</head>

<body>
    <h1>Login</h1>

    @if (session('status'))
        <p>{{ session('status') }}</p>
    @endif

    @if ($errors->any())
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    @endif

    <form id="login-form" method="POST">
        @csrf
        <div>
            <label>Email:</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" required>
        </div>
        <div>
            <label>Password:</label>
            <input type="password" name="password" id="password" required>
        </div>
        <div>
            <button type="submit">Login</button>
        </div>
    </form>

    <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
</body>
<script>
    // Handle the form submit
    $('#login-form').submit(function(event) {
        event.preventDefault();

        $.ajax({
            url: '/api/login', // Your Laravel login endpoint
            method: 'POST',
            data: {
                email: $('#email').val(),
                password: $('#password').val(),
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                // Store token in localStorage
                localStorage.setItem('token', response.token);
                alert('Login successful and token stored!');
                // $(location).attr('href', 'http://localhost:8000/dashboard');

                // Redirect to dashboard

            },
            error: function(xhr, status, error) {
                alert('Login failed: ' + xhr.responseText);
            }
        });
    });
</script>

</html>
