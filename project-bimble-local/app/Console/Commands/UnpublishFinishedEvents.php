<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PaketTryout;
use Carbon\Carbon;

class UnpublishFinishedEvents extends Command
{
    /**
     * Nama dan signature dari command.
     */
    protected $signature = 'app:unpublish-finished-events';

    /**
     * Deskripsi dari command.
     */
    protected $description = 'Secara otomatis mengubah status Tryout Event yang sudah selesai menjadi "draft"';

    /**
     * Jalankan logika command.
     */
    public function handle()
    {
        $this->info('Memeriksa Tryout Event yang sudah selesai...');

        $events = PaketTryout::where('tipe_paket', 'event')
                             ->where('status', 'published')
                             ->whereNotNull('waktu_mulai')
                             ->get();

        $unpublishedCount = 0;

        foreach ($events as $event) {
            $waktuMulai = Carbon::parse($event->waktu_mulai);
            $waktuSelesai = $waktuMulai->copy()->addMinutes($event->durasi_menit);

            if (Carbon::now()->isAfter($waktuSelesai)) {
                $event->status = 'draft';
                $event->save();
                $this->info("Paket '{$event->nama_paket}' (ID: {$event->id}) telah diarsipkan.");
                $unpublishedCount++;
            }
        }

        if ($unpublishedCount > 0) {
            $this->info("Selesai. {$unpublishedCount} event berhasil diarsipkan.");
        } else {
            $this->info('Tidak ada event yang perlu diarsipkan saat ini.');
        }

        return 0;
    }
}
