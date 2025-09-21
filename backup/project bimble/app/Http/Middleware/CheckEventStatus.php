<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\PaketTryout;
use Carbon\Carbon;

class CheckEventStatus
{
    public function handle(Request $request, Closure $next): Response
    {
        $paketTryout = $request->route('paketTryout');

        if (!$paketTryout instanceof PaketTryout || $paketTryout->tipe_paket !== 'event') {
            return $next($request);
        }

        $now = now();
        $waktuMulai = Carbon::parse($paketTryout->waktu_mulai);

        // Cek jika siswa mencoba masuk SEBELUM event dimulai
        if ($now->isBefore($waktuMulai)) {
            // Arahkan kembali dengan pesan error, cegah masuk lebih jauh
            return redirect()->route('siswa.pilih_event', ['jenjang' => $request->route('jenjang')])
                             ->with('error', 'Event "' . $paketTryout->nama_paket . '" belum dimulai.');
        }

        // Tidak perlu cek waktu selesai di sini, karena logika di controller akan menangani perpindahan paksa.
        // Middleware ini hanya fokus sebagai gerbang masuk awal.

        return $next($request);
    }
}
