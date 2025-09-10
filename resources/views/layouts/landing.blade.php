{{-- project-bimbel/resources/views/layouts/landing.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    {{-- ... (kode head lainnya tetap sama) ... --}}
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Corso - Online Training Course</title>
    <link href="https://fonts.googleapis.com/css?family=Montserrat:400,400i,600,700,700i&display=swap" rel="stylesheet">
    <link href="{{ asset('css/bootstrap.css') }}" rel="stylesheet">
    <link href="{{ asset('css/fontawesome-all.css') }}" rel="stylesheet">
    <link href="{{ asset('css/swiper.css') }}" rel="stylesheet">
    <link href="{{ asset('css/magnific-popup.css') }}" rel="stylesheet">
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-styles.css') }}" rel="stylesheet">
    <link href="{{ asset('css/custom-styles.css?v=1.1') }}" rel="stylesheet">
    <link rel="icon" href="{{ asset('images/favicon.png') }}">
</head>
<body data-spy="scroll" data-target=".fixed-top">

    <div class="spinner-wrapper">
        <div class="spinner"><div class="bounce1"></div><div class="bounce2"></div><div class="bounce3"></div></div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
        <div class="container">
            <a class="navbar-brand logo-image" href="{{ url('/') }}"><img src="{{ asset('images/logo.svg') }}" alt="alternative"></a>

            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarsExampleDefault" aria-controls="navbarsExampleDefault" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-awesome fas fa-bars"></span>
                <span class="navbar-toggler-awesome fas fa-times"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarsExampleDefault">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="{{ route('siswa.pilih_jenjang') }}">TRYOUT MURID</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="{{ route('dashboard') }}">HALAMAN GURU</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link page-scroll" href="#contact">KONTAK</a>
                    </li>
                </ul>
                <span class="nav-item">
                    @auth
                        <a class="btn-solid-sm page-scroll" href="{{ url('/dashboard') }}">DASHBOARD</a>
                    @else
                        <a class="btn-solid-sm page-scroll" href="{{ route('login') }}">LOG IN</a>
                    @endauth
                </span>
            </div>
        </div>
    </nav>

    @yield('content')

    {{-- ... (kode footer dan script lainnya tetap sama) ... --}}
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-3">
                    <div class="footer-col first">
                        <h5>PETPID EDUCATION</h5>
                        <p class="p-small">IMBINGAN BELAJAR DALAM BERAKHLAK & BERPRESTASI</p>
                    </div>
                </div> <div class="col-md-3">
                    <div class="footer-col second">
                        <h5>Links</h5>
                        <ul class="list-unstyled li-space-lg p-small">
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body"><a href="#">Terms & Conditions</a></div>
                            </li>
                            <li class="media">
                                <i class="fas fa-square"></i>
                                <div class="media-body"><a href="#">Privacy Policy</a></div>
                            </li>
                        </ul>
                    </div>
                </div> <div class="col-md-3">
                    <div class="footer-col third">
                        <h5>Social Media</h5>
                        <p class="p-small">For news & updates follow us</p>
                        <a href="#your-link"><i class="fab fa-facebook-f"></i></a>
                        <a href="#your-link"><i class="fab fa-twitter"></i></a>
                        <a href="#your-link"><i class="fab fa-instagram"></i></a>
                    </div>
                </div> </div> </div> </div> <div class="copyright">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <p class="p-small">Platform ini dibuat oleh <a href="https://www.linkedin.com/in/erik-adam-15b93816b/">rikchodam</a>  </p>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/jquery.min.js') }}"></script>
    <script src="{{ asset('js/popper.min.js') }}"></script>
    <script src="{{ asset('js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('js/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('js/jquery.countdown.min.js') }}"></script>
    <script src="{{ asset('js/swiper.min.js') }}"></script>
    <script src="{{ asset('js/jquery.magnific-popup.js') }}"></script>
    <script src="{{ asset('js/validator.min.js') }}"></script>
    <script src="{{ asset('js/scripts.js') }}"></script>
</body>
</html>
