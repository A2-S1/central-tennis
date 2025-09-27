<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class FetchAtpRankings extends Command
{
    protected $signature = 'centraltennis:fetch-atp';
    protected $description = 'Busca rankings ATP e armazena em atp_rankings';

    public function handle(): int
    {
        $this->info('Buscando rankings ATP...');
        try {
            // OBS: Fontes oficiais exigem parsing específico / API com chave.
            // Este é um placeholder que você pode ajustar para sua fonte.
            // Exemplo com uma API pública hipotética:
            // $resp = Http::get('https://api.exemplo.com/atp/rankings');
            // $data = $resp->json();

            // Por enquanto, apenas evita falhas e mantém estrutura:
            $today = now()->toDateString();

            DB::transaction(function () use ($today) {
                // Exemplo: limpar dados do dia e inserir (idempotente por unique rank+date)
                DB::table('atp_rankings')->where('as_of_date', $today)->delete();
                // Inserção fake para teste
                DB::table('atp_rankings')->insert([
                    [
                        'rank' => 1,
                        'player_name' => 'Jogador ATP 1',
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

            $this->info('Rankings ATP atualizados.');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('Erro ao buscar ATP: '.$e->getMessage());
            $this->error('Falha ao atualizar ATP: '.$e->getMessage());
            return Command::FAILURE;
        }
    }
}
