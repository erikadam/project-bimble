@extends('layouts.auth')

@section('content')
    <div class="form-container" style="background-color: #1f1f1f; padding: 2.5rem; border-radius: 0.5rem;">

        <div class="text-center mb-4">
            <a href="/">
                <img src="{{ asset('images/logo.svg') }}" alt="logo" style="width: 150px;">
            </a>
        </div>

        <h2 class="text-center" style="margin-bottom: 2rem;">Masuk Sebagai Guru</h2>

        @if (session('status'))
            <div class="alert alert-success mb-4">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="alert alert-danger mb-4">
                <ul style="margin-bottom: 0; padding-left: 1rem;">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" data-toggle="validator" data-focus="false">
            @csrf

            <div class="form-group">
                <input id="email" type="email" class="form-control-input" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder=" ">
                <label class="label-control" for="email">Mail</label>
            </div>

            <div class="form-group">
                <input id="password" type="password" class="form-control-input" name="password" required autocomplete="current-password" placeholder=" ">
                <label class="label-control" for="password">Password</label>
            </div>

            <div class="form-group d-flex justify-content-between align-items-center">
                <div class="form-check">
                    <input class="form-check-input" type="checkbox" name="remember" id="remember_me">
                    <label class="form-check-label" for="remember_me" style="color: #e5e7eb;">
                        Ingat Saya
                    </label>
                </div>

                @if (Route::has('password.request'))
                    <a href="{{ route('password.request') }}" style="font-size: 0.9rem;">
                        Lupa password?
                    </a>
                @endif
            </div>

            <div class="form-group">
                <button type="submit" class="form-control-submit-button">MASUK</button>
            </div>

            <div class="text-center mt-4">
                <a href="{{ route('welcome') }}">Kembali ke Halaman Utama</a>
            </div>
        </form>
    </div>
@endsection
