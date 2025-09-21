{{-- resources/views/ulangan/daftar.blade.php --}}
@extends('layouts.landing') {{-- Menggunakan layout yang sama dengan landing page --}}

@section('content')
<div class="ex-basic-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="breadcrumbs">
                    <a href="{{ route('welcome') }}">Home</a><i class="fa fa-angle-double-right"></i><span>Daftar Ulangan</span>
                </div>
                <h1 class="h1-large">Daftar Ulangan Tersedia</h1>
                <p class="p-large">Selamat datang, **{{ session('siswa_data.nama') }}**! Berikut adalah daftar ulangan untuk jenjang **{{ session('siswa_data.jenjang') }}** yang bisa kamu kerjakan.</p>
            </div>
        </div>
    </div>
</div>

<div class="ex-cards-1">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">

                @if($ulangans->isEmpty())
                    <div class="p-6 bg-white rounded-lg shadow-lg text-center">
                        <h3 class="text-xl font-semibold text-gray-700">Belum Ada Ulangan</h3>
                        <p class="text-gray-500 mt-2">Saat ini belum ada ulangan yang tersedia untuk jenjang Anda. Silakan cek kembali nanti.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach ($ulangans as $ulangan)
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">{{ $ulangan->nama_ulangan }}</h5>
                                    <h6 class="mb-2 text-muted">{{ $ulangan->mataPelajaran->nama_mapel }}</h6>
                                    <p class="card-text">{{ $ulangan->deskripsi ?: 'Tidak ada deskripsi.' }}</p>
                                    <p class="card-text"><small class="text-muted">Jumlah Soal: {{ $ulangan->soal->count() }}</small></p>
                                    {{-- Tombol ini akan kita fungsikan di langkah selanjutnya --}}
                                    <a href="{{ route('ulangan.mulai', $ulangan) }}" class="btn-solid-reg">MULAI KERJAKAN</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif

            </div>
        </div>
    </div>
</div>
@endsection
