<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class FetchWtaRankings extends Command
{
    protected $signature = 'centraltennis:fetch-wta';
    protected $description = 'Busca rankings WTA e armazena em wta_rankings';

    public function handle(): int
    {
        $this->info('Buscando rankings WTA...');
        try {
            // Placeholder similar ao ATP, ajuste para a fonte real (API/scraping com consentimento)
            $today = now()->toDateString();

            DB::transaction(function () use ($today) {
                DB::table('wta_rankings')->where('as_of_date', $today)->delete();
                DB::table('wta_rankings')->insert([
                    [
                        'rank' => 1,
                        'player_name' => 'Jogadora WTA 1',
                        'country' => 'BRA',
                        'points' => 9000,
                        'tournaments_played' => 18,
                        'as_of_date' => $today,
                        'source' => 'placeholder',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ],
                ]);
            });

            $this->info('Rankings WTA atualizados.');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('Erro ao buscar WTA: '.$e->getMessage());
            $this->error('Falha ao atualizar WTA: '.$e->getMessage());
            return Command::FAILURE;
        }
    }
}
