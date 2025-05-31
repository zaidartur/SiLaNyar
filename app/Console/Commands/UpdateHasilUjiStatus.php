<?php

namespace App\Console\Commands;

use App\Models\HasilUji;
use Illuminate\Console\Command;

class UpdateHasilUjiStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-hasil-uji-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Status Hasil Uji Berdasarkan Waktu';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Cek dan Update Status Hasil Uji');

        $hasiluji = HasilUji::whereIn('status', 'proses_review')->get();

        foreach ($hasiluji as $item) {
            if($item->status === 'proses_review' && $item->proses_review_at && now()->diffInDays($item->proses_review_at) >= 2)
            {
                $item->update([
                    'status' => 'proses_peresmian'
                ]);

                $this->info('Status Hasil Uji #{$item->id} Berubah Ke Proses Peresmian');
            }
        }

        return Command::SUCCESS;
    }
}
