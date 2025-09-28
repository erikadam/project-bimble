{{-- project-bimbel/resources/views/welcome.blade.php --}}

@extends('layouts.landing')

@section('custom-styles')
<style>
.header {
    padding-top: 8rem !important;
    padding-bottom: 0 !important; /* hapus padding lama yang bikin jarak bawah */
    margin-bottom: 0 !important;
    position: relative;
    background-color: #1a1a1a;
    text-align: center;
}

/* pastikan outer-container tidak absolute */
.header .outer-container {
    position: relative !important;
    bottom: auto !important;
    margin: 0 auto !important;
    width: 100% !important;
}

/* SLIDER FIX DIMENSI */
.header .slider-container {
    width: 1110px !important;  /* fix lebar desktop */
    height: 740px !important;  /* fix tinggi sesuai proporsi contoh */
    margin: 0 auto;            /* center */
    overflow: hidden;
    border-radius: 0.75rem;
    position: relative;
}

/* isi gambar */
.header .slider-container img,
.header .swiper-container img {
    width: 100% !important;
    height: 100% !important;
    object-fit: cover;
    display: block;
}

/* RESPONSIVE */
@media (max-width: 767px) {
        .header {
            padding-top: 4rem;
            padding-bottom: 15rem; /* Mengurangi padding vertikal di header */
        }

        .header .text-container {
            padding-bottom: 3rem; /* Mengurangi jarak antara teks atas dan slider */
        }

        .header .h2-heading {
            font-size: 1.75rem; /* Sedikit perkecil judul "Our Documentation" */
            margin-bottom: 2rem; /* Mengurangi jarak judul ke slider */
        }

        .header .swiper-slide {
            height: 220px; /* Tinggi slider dibuat lebih pendek agar proporsional di mobile */
        }
    }
</style>
@endsection


@section('content')

<header id="header" class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-container">
                    <h1>Portal Ujian Online & Tryout</h1>
                    <p class="p-large">Uji kemampuan dan pengetahuan Anda dengan ribuan soal berkualitas yang disusun oleh para ahli. Siap hadapi tantangan?</p>

                    <a class="btn-solid-lg" href="{{ route('siswa.pilih_jenjang') }}">MULAI TRYOUT SEKARANG</a>
                </div>
            </div>
        </div>
    </div>
    <div class="outer-container">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <h2 class="h2-heading">Mading</h2>
                </div>
            </div>
        </div>
        <div class="slider-container">
            <div class="swiper-container image-slider-1">
                <div class="swiper-wrapper">
                    @forelse ($sliders as $image)
                        <div class="swiper-slide">
                            {{-- Tambahkan class="slider-image" di sini --}}
                            <img class="img-fluid slider-image" src="{{ asset('storage/' . $image->path) }}" alt="slider image">
                        </div>
                    @empty
                        <div class="swiper-slide">
                            {{-- Tambahkan class="slider-image" di sini --}}
                            <img class="img-fluid slider-image" src="{{ asset('images/header-slide-1.jpg') }}" alt="siswa belajar">
                        </div>
                        <div class="swiper-slide">
                            {{-- Tambahkan class="slider-image" di sini --}}
                            <img class="img-fluid slider-image" src="{{ asset('images/header-slide-2.jpg') }}" alt="ruang kelas">
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</header>
<div id="register" class="form-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="text-container">
                    <h2>Daftar Untuk Memulai Ulangan</h2>
                    <p>Isi formulir di bawah ini untuk memulai ulangan mandiri sesuai dengan jenjang pendidikan Anda. Tidak ada batasan waktu, jadi kerjakan dengan santai!</p>
                    <ul class="list-unstyled li-space-lg">
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body"><strong>Informasi Anda</strong> diperlukan untuk mempersonalisasi hasil ulangan.</div>
                        </li>
                        <li class="media">
                            <i class="fas fa-square"></i><div class="media-body"><strong>Akses mudah</strong> ke mata pelajaran ulangan yang telah disiapkan.</div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="form-container">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    <form id="ulanganForm" method="POST" action="{{ route('ulangan.start') }}">
                        @csrf
                        <div class="form-group">
                            <input type="text" class="form-control-input" id="nama_lengkap" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                            <label class="label-control" for="nama_lengkap">Nama Lengkap</label>
                            @error('nama_lengkap')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control-input" id="kelas" name="kelas" value="{{ old('kelas') }}" required>
                            <label class="label-control" for="kelas">Kelas</label>
                            @error('kelas')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <input type="text" class="form-control-input" id="asal_sekolah" name="asal_sekolah" value="{{ old('asal_sekolah') }}" required>
                            <label class="label-control" for="asal_sekolah">Asal Sekolah</label>
                            @error('asal_sekolah')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <select id="jenjang_pendidikan" name="jenjang_pendidikan" class="form-control-input" required>
                                <option value="" disabled selected>Pilih Jenjang</option>
                                <option value="SD">SD</option>
                                <option value="SMP">SMP</option>
                                <option value="SMA">SMA</option>
                            </select>
                            <label class="label-control" for="jenjang_pendidikan">Jenjang Pendidikan</label>
                            @error('jenjang_pendidikan')
                                <div class="text-danger mt-2">{{ $message }}</div>
                            @enderror
                        </div>
                        <div class="form-group">
                            <button type="submit" class="form-control-submit-button">MULAI ULANGAN</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="basic-2">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h2>Apa Kata Siswa Kami</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-lg-12">
                <div class="slider-container">
                    <div class="swiper-container text-slider">
                        <div class="swiper-wrapper">
                            @forelse ($testimonials as $testimonial)
                                <div class="swiper-slide">
                                    <div class="image-wrapper">
                                        <img class="img-fluid" src="{{ asset('storage/' . $testimonial->image_path) }}" alt="foto testimoni">
                                    </div>
                                    <div class="text-wrapper">
                                        <div class="testimonial-text">{{ $testimonial->message }}</div>
                                        <div class="testimonial-author">{{ $testimonial->name }} - {{ $testimonial->title }}</div>
                                    </div>
                                </div>
                            @empty
                                <div class="swiper-slide">
                                    <div class="image-wrapper">
                                        <img class="img-fluid" src="images/testimonial-1.jpg" alt="foto testimoni default">
                                    </div>
                                    <div class="text-wrapper">
                                        <div class="testimonial-text">Belum ada testimoni. Silakan tambahkan dari halaman guru.</div>
                                        <div class="testimonial-author">Admin</div>
                                    </div>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
{{-- Bagia1 Us" Baru --}}
@if($aboutUs)
<div id="about" class="form-3">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                {{-- Kolom Teks (Sebelah Kiri) --}}
                <div class="text-container">
                    <h2>{{ $aboutUs->title }}</h2>
                    <p>{{ $aboutUs->description }}</p>
                </div>
            </div>
            <div class="col-lg-6">
                {{-- Kolom Gambar (Sebelah Kanan) --}}
                <div class="image-container">
                    <img class="img-fluid" src="{{ asset('storage/' . $aboutUs->image_path) }}" alt="about us" style="border-radius: 0.25rem;">
                </div>
            </div>
        </div>
    </div>
</div>
@endif
<div class="cards-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                {{-- Bagian Visi --}}
                @if(isset($companyGoals['visi']) && $companyGoals['visi']->isNotEmpty())
                <div class="mb-5">
                    <h2 class="h2-heading">Visi</h2>
                    {{-- Mengambil deskripsi dari Visi pertama sebagai satu paragraf --}}
                    <p class="visi-paragraph">{{ $companyGoals['visi']->first()->description }}</p>
                </div>
                @endif

                {{-- Bagian Misi --}}
                @if(isset($companyGoals['misi']) && $companyGoals['misi']->isNotEmpty())
                <div class="mb-5">
                    <h2 class="h2-heading">Misi</h2>
                    <div class="point-list">
                        <ul>
                            {{-- Menggabungkan semua poin Misi menjadi satu daftar --}}
                            @foreach($companyGoals['misi'] as $item)
                                <li>{{ $item->description }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif

                {{-- Bagian Tujuan --}}
                @if(isset($companyGoals['tujuan']) && $companyGoals['tujuan']->isNotEmpty())
                <div>
                    <h2 class="h2-heading">Tujuan</h2>
                    <div class="point-list">
                        <ul>
                            {{-- Menggabungkan semua poin Tujuan menjadi satu daftar --}}
                            @foreach($companyGoals['tujuan'] as $item)
                                <li>{{ $item->description }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
