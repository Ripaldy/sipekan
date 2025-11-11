<?php

namespace App\Console\Commands;

use App\Models\Pengukuran;
use Illuminate\Console\Command;

class RecalculateStatusGizi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'pengukuran:recalculate-status-gizi';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Recalculate all pengukuran status_gizi using WHO standards';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Recalculating status gizi for all pengukuran...');
        
        $pengukurans = Pengukuran::whereNotNull('berat_badan')
            ->whereNotNull('tinggi_badan')
            ->get();
            
        $count = 0;
        foreach ($pengukurans as $pengukuran) {
            $newStatusGizi = Pengukuran::calculateStatusGizi(
                $pengukuran->berat_badan,
                $pengukuran->tinggi_badan,
                $pengukuran->umur_saat_ukur
            );
            
            if ($pengukuran->status_gizi !== $newStatusGizi) {
                $pengukuran->status_gizi = $newStatusGizi;
                $pengukuran->saveQuietly(); // Save without triggering events
                $count++;
            }
        }
        
        $this->info("Successfully recalculated {$count} out of {$pengukurans->count()} pengukuran records.");
        
        return 0;
    }
}
