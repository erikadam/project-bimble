<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login Guru - Portal Ujian Online</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,600,700,700i&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/swiper.css') }}" rel="stylesheet">
    <link href="{{ asset('css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-styles.css') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
    <style>
    /* CSS khusus untuk memusatkan konten dan menambah background */
    body, html {
        height: 100%;
    }
    body {
        /* Menambahkan gambar latar belakang */
        background: linear-gradient(to bottom right, rgba(18, 18, 18, 0.9), rgba(18, 18, 18, 0.9)), url('{{ asset("images/header-background.jpg") }}') center center no-repeat;
        background-size: cover;
    }
    .auth-container {
        display: flex;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        padding-top: 40px;
        padding-bottom: 40px;
    }
    .auth-card {
        max-width: 500px;
        width: 100%;
    }
</style>
</head>
<body data-spy="scroll" data-target=".fixed-top">
    <div class="spinner-wrapper">
        <div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
    </div>

    <div class="auth-container">
<div class="auth-card">
    @yield('content')
</div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/swiper.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
