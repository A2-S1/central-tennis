<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class FetchTournaments extends Command
{
    protected $signature = 'centraltennis:fetch-tournaments';
    protected $description = 'Busca torneios em andamento e futuros e atualiza a tabela tournaments';

    public function handle(): int
    {
        $this->info('Atualizando torneios...');
        try {
            // Placeholder: aqui você deve implementar a integração real (API/scraping, conforme disponibilidade e termos de uso)
            // Estrutura esperada na migration: name, tour (ATP/WTA), level, surface, city, country, start_date, end_date, status, source

            DB::transaction(function () {
                // Exemplo básico: inserir um torneio de teste
                DB::table('tournaments')->updateOrInsert(
                    [
                        'name' => 'Central Open 250',
                        'tour' => 'ATP',
                        'start_date' => now()->addDays(7)->toDateString(),
                    ],
                    [
                        'level' => '250',
                        'surface' => 'hard',
                        'city' => 'São Paulo',
                        'country' => 'BR',
                        'end_date' => now()->addDays(14)->toDateString(),
                        'status' => 'upcoming',
                        'source' => 'placeholder',
                        'updated_at' => now(),
                        'created_at' => now(),
                    ]
                );
            });

            $this->info('Torneios atualizados.');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('Erro ao atualizar torneios: '.$e->getMessage());
            $this->error('Falha: '.$e->getMessage());
            return Command::FAILURE;
        }
    }
}
