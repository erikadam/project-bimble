<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\SoalController;
use App\Http\Controllers\PaketTryoutController;
use App\Http\Controllers\UjianController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;
use App\Models\PaketTryout;
use Carbon\Carbon;
use App\Http\Controllers\WebsiteSettingController;
use App\Http\Controllers\TestimonialController;
use App\Http\Controllers\CompanyGoalController;
use App\Http\Controllers\UlanganController;
use App\Http\Controllers\Controller;
// Halaman utama publik
Route::get('/check-time', [Controller::class, 'checkServerTime']);
Route::get('/', [SiswaController::class, 'showAksesUlangan'])->name('welcome');
Route::post('/ulangan/start', [SiswaController::class, 'startUlangan'])->name('ulangan.start');
Route::prefix('ulangan-siswa')->name('siswa.ulangan.')->group(function () {
    Route::get('/pilih/{jenjang}', [SiswaController::class, 'pilihUlangan'])->name('pilih');
    Route::get('/mulai/{ulangan}', [SiswaController::class, 'mulaiSesiUlangan'])->name('mulai');
    Route::post('/start/{ulangan}', [SiswaController::class, 'startSesiUlangan'])->name('start');
    Route::get('/soal/{ulangan}', [SiswaController::class, 'showSoalUlangan'])->name('show_soal');
    Route::post('/simpan/{ulangan}', [SiswaController::class, 'simpanJawabanUlangan'])->name('simpan');
    Route::get('/hasil/{ulanganSession}', [SiswaController::class, 'hasilUlangan'])->name('hasil');
    Route::get('/review', [SiswaController::class, 'reviewUlangan'])->name('review');
    Route::get('/review/{ulanganSession}', [SiswaController::class, 'reviewUlangan'])->name('review');
});
// Route dasbor yang dibuat oleh Breeze
Route::get('/dashboard', function () {

    $upcomingEvents = PaketTryout::whereIn('tipe_paket', ['event', 'pacu'])
        ->where('status', 'published')
        ->where('waktu_mulai', '>', now())
        ->orderBy('waktu_mulai', 'asc')
        ->take(3)
        ->get();


    $upcomingEvents->each(function ($event) {
        $now = Carbon::now();
        $waktuMulai = Carbon::parse($event->waktu_mulai);
        $event->waktu_mulai_timestamp = $waktuMulai->timestamp;
        $event->server_now_timestamp = $now->timestamp;
    });

    return view('dashboard', ['upcomingEvents' => $upcomingEvents]);
})->middleware(['auth', 'verified'])->name('dashboard');

// Grup route yang hanya bisa diakses setelah login
Route::middleware('auth')->group(function () {

        Route::resource('about-us', \App\Http\Controllers\AboutUsController::class)
         ->parameters(['about-us' => 'about_us']);
    Route::post('soal/upload-image', [App\Http\Controllers\SoalController::class, 'uploadImage'])->name('soal.uploadImage');
    Route::resource('company-goals', CompanyGoalController::class);
    Route::resource('testimonials', TestimonialController::class);
    Route::get('/pengaturan-website', [WebsiteSettingController::class, 'index'])->name('pengaturan.index');
    Route::post('/pengaturan-website/slider', [WebsiteSettingController::class, 'storeSliderImage'])->name('pengaturan.slider.store');
    Route::delete('/pengaturan-website/slider/{image}', [WebsiteSettingController::class, 'destroySliderImage'])->name('pengaturan.slider.destroy');
    // Route untuk profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    // Route untuk Manajemen Pengguna
    Route::resource('users', UserController::class);
    Route::get('/laporan-ujian/{paketTryout}/responses', [PaketTryoutController::class, 'showResponses'])->name('paket-tryout.responses');
    Route::get('/laporan-ujian', [PaketTryoutController::class, 'laporanIndex'])->name('paket-tryout.laporan_index');
    Route::get('/paket-tryout/{paketTryout}/export-laporan', [PaketTryoutController::class, 'exportLaporanSiswa'])->name('paket-tryout.export_laporan');
    Route::get('/laporan-ujian/{paketTryout}/analysis', [PaketTryoutController::class, 'showAnalysis'])->name('paket-tryout.analysis');
    // Route untuk memilih jenjang pendidikan sebagai menu awal bank soal
    Route::get('/bank-soal', [MataPelajaranController::class, 'pilihJenjang'])->name('mata-pelajaran.pilih_jenjang');
    // Route untuk memilih mata pelajaran berdasarkan jenjang
    Route::get('/bank-soal/{jenjang}', [SoalController::class, 'pilihMataPelajaran'])->name('soal.pilih_mapel');
    // Route untuk Manajemen Mata Pelajaran (difilter per jenjang)
    Route::resource('mata-pelajaran', MataPelajaranController::class)->except(['create']);
    // Route untuk Manajemen Soal (di dalam Mata Pelajaran)
    Route::resource('mata-pelajaran.soal', SoalController::class)->shallow();
    Route::get('/paket-tryout/{paketTryout}/get-bobot', [PaketTryoutController::class, 'getBobotSoal'])->name('paket-tryout.get_bobot');
    Route::post('/paket-tryout/{paketTryout}/save-bobot', [PaketTryoutController::class, 'saveBobotSoal'])->name('paket-tryout.save_bobot');
    // Rute untuk Manajemen Tryout
    Route::resource('paket-tryout', PaketTryoutController::class);
    Route::get('/paket-tryout/{paketTryout}/edit', [PaketTryoutController::class, 'edit'])->name('paket-tryout.edit'); // Rute edit manual
    Route::patch('/paket-tryout/{paketTryout}', [PaketTryoutController::class, 'update'])->name('paket-tryout.update'); // Rute update manual
    // Rute untuk demo ujian dari halaman detail
    Route::patch('/paket-tryout/{paketTryout}/toggle-status', [PaketTryoutController::class, 'toggleStatus'])->name('paket-tryout.toggle_status');
    Route::get('/paket-tryout/{paketTryout}/review', [PaketTryoutController::class, 'review'])->name('paket-tryout.review');
    Route::get('ulangan/pilih-jenjang', [\App\Http\Controllers\UlanganController::class, 'pilihJenjang'])->name('ulangan.pilihJenjang');
    Route::resource('ulangan', UlanganController::class);
    Route::post('ulangan/{ulangan}/toggle-status', [\App\Http\Controllers\UlanganController::class, 'toggleStatus'])->name('ulangan.toggleStatus');
Route::post('ulangan/{ulangan}/add-soal', [\App\Http\Controllers\UlanganController::class, 'addSoal'])->name('ulangan.addSoal');
Route::post('ulangan/{ulangan}/remove-soal', [\App\Http\Controllers\UlanganController::class, 'removeSoal'])->name('ulangan.removeSoal');
    // Rute untuk menampilkan daftar paket yang bisa didemokan
    Route::get('/demo-ujian', [PaketTryoutController::class, 'demoIndex'])->name('paket-tryout.demo_index');
    // Rute untuk mekanisme ujian demo
    Route::prefix('demo-ujian/{paketTryout}')->name('demo.ujian.')->group(function () {
        Route::get('/mulai', [UjianController::class, 'mulai'])->name('mulai');
        Route::post('/start', [UjianController::class, 'start'])->name('start');
        Route::get('/soal/{mapelId}', [UjianController::class, 'showMapel'])->name('show_mapel');
        Route::post('/jawab-mapel/{mapelId}', [UjianController::class, 'simpanJawabanMapel'])->name('simpan_jawaban');
        Route::get('/hasil', [UjianController::class, 'hasil'])->name('hasil');
        Route::get('/unduh-hasil', [UjianController::class, 'unduhHasil'])->name('unduh_hasil');
    });
});
Route::post('/ulangan-siswa/autosave/{ulangan}', [\App\Http\Controllers\SiswaController::class, 'autoSaveJawabanUlangan'])->name('siswa.ulangan.autosave');
// Grup rute untuk siswa (tanpa autentikasi)
Route::prefix('ujian-siswa')->name('siswa.')->group(function () {

    Route::get('/pilih-pacu/{jenjang?}', [SiswaController::class, 'pilihPacu'])->name('pilih_pacu');
    Route::get('/pilih-jenjang', [SiswaController::class, 'pilihJenjang'])->name('pilih_jenjang');
    Route::get('/akses-ulangan', [SiswaController::class, 'aksesUlangan'])->name('akses_ulangan');
    Route::get('/pilih-ulangan/{jenjang?}', [SiswaController::class, 'pilihUlangan'])->name('pilih_ulangan');
    Route::get('/pilih-event/{jenjang?}', [SiswaController::class, 'pilihEvent'])->name('pilih_event');
    Route::get('/pilih-paket/{jenjang?}', [SiswaController::class, 'pilihPaket'])->name('pilih_paket');
    Route::post('/ulangan-siswa/autosave/{ulangan}', [\App\Http\Controllers\SiswaController::class, 'autoSaveJawabanUlangan'])->name('siswa.ulangan.autosave');
    Route::prefix('{paketTryout}')->name('ujian.')->middleware('check.event.status')->group(function () {
        Route::get('/mulai', [SiswaController::class, 'mulai'])->name('mulai');
        Route::post('/start', [SiswaController::class, 'start'])->name('start');
        Route::get('/pilih-mapel', [SiswaController::class, 'pilihMapel'])->name('pilih_mapel');
        Route::post('/mulai-pengerjaan', [SiswaController::class, 'mulaiPengerjaan'])->name('mulai_pengerjaan');
        Route::get('/soal/{mapelId}', [SiswaController::class, 'showSoal'])->name('show_soal');
        Route::post('/jawab-mapel/{mapelId}', [SiswaController::class, 'simpanJawaban'])->name('simpan_jawaban');
        Route::post('/autosave/{mapelId}', [SiswaController::class, 'autoSaveJawaban'])->name('autosave');
        Route::get('/hasil', [SiswaController::class, 'hasil'])->name('hasil');
        Route::get('/unduh-hasil', [SiswaController::class, 'unduhHasil'])->name('unduh_hasil');
        Route::get('/review', [SiswaController::class, 'review'])->name('review');
        Route::get('/fleksibel/soal/{mapelId}', [SiswaController::class, 'showSoalFleksibel'])->name('fleksibel.show_soal');
        Route::post('/fleksibel/jawab-mapel/{mapelId}', [SiswaController::class, 'simpanJawabanFleksibel'])->name('fleksibel.simpan_jawaban');
        Route::get('/fleksibel/hasil', [SiswaController::class, 'hasilFleksibel'])->name('fleksibel.hasil');
        Route::prefix('pacu')->name('pacu.')->group(function () {
        Route::get('/soal/{mapelId}', [SiswaController::class, 'showSoalPacu'])->name('show_soal');
        Route::post('/simpan/{mapelId}', [SiswaController::class, 'simpanJawabanPacu'])->name('simpan_jawaban');
        Route::post('/autosave/{mapelId}', [SiswaController::class, 'autosaveJawabanPacu'])->name('autosave');
        Route::get('/istirahat', [SiswaController::class, 'showIstirahatPacu'])->name('show_istirahat');
        Route::get('/hasil', [SiswaController::class, 'hasilPacu'])->name('hasil');
    });
    });
});
Route::prefix('ulangan/{ulangan}/laporan')->name('ulangan.laporan.')->group(function () {
    Route::get('/responses', [\App\Http\Controllers\LaporanUlanganController::class, 'responses'])->name('responses');
    Route::get('/analysis', [\App\Http\Controllers\LaporanUlanganController::class, 'analysis'])->name('analysis');
    Route::post('/update-kesulitan', [\App\Http\Controllers\LaporanUlanganController::class, 'updateKesulitan'])->name('update_kesulitan');
});
require __DIR__.'/auth.php';
