<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All abouTzu</title>
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="{{ asset('js/main.js') }}" defer></script>
</head>
<body>
    <header>
        <h1>All abouTzu</h1>
        <nav>
            <a href="{{ route('home') }}">Home</a>
            <a href="{{ route('profile') }}">Profile</a>
            <a href="{{ route('songs') }}">Songs</a>
            <a href="{{ route('movie') }}">Movie</a>
            <a href="{{ route('goods') }}">Goods</a>
            <a href="{{ route('photo') }}">Photo</a>
        </nav>
    </header>

    <main>
        @yield('content')
    </main>

    <footer>
        <p>&copy; 2024 All abouTzu</p>
    </footer>
</body>
</html>