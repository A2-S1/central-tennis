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
            $today = now()->toDateString();
            $url = 'https://www.atptour.com/en/rankings/singles';

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept-Language' => 'en-US,en;q=0.9',
            ])->get($url);

            if (!$response->ok()) {
                throw new \RuntimeException('Falha HTTP ao buscar ATP: status '.$response->status());
            }

            $html = $response->body();

            // Estratégia A: procurar JSON embutido (algumas páginas usam Apollo/React com estado serializado)
            $parsed = $this->tryParseJsonEmbedded($html);
            if (empty($parsed)) {
                // Estratégia B: parsing de tabela HTML (fallback simples)
                $parsed = $this->tryParseHtmlTable($html);
            }

            if (empty($parsed)) {
                throw new \RuntimeException('Não foi possível extrair rankings da página ATP.');
            }

            // Limitar ao Top 50 para evitar carga
            $top = array_slice($parsed, 0, 50);

            DB::transaction(function () use ($today, $top) {
                DB::table('atp_rankings')->where('as_of_date', $today)->delete();
                $now = now();
                $rows = [];
                foreach ($top as $row) {
                    $rows[] = [
                        'rank' => (int)($row['rank'] ?? 0),
                        'player_name' => (string)($row['player_name'] ?? ''),
                        'country' => (string)($row['country'] ?? ''),
                        'points' => (int)preg_replace('/[^0-9]/','', (string)($row['points'] ?? 0)),
                        'tournaments_played' => (int)($row['tournaments_played'] ?? 0),
                        'as_of_date' => $today,
                        'source' => 'scrape:atptour.com',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ];
                }
                if (!empty($rows)) {
                    DB::table('atp_rankings')->insert($rows);
                }
            });

            $this->info('Rankings ATP atualizados (Top '.count($top).').');
            return Command::SUCCESS;
        } catch (\Throwable $e) {
            Log::error('Erro ao buscar ATP: '.$e->getMessage());
            $this->error('Falha ao atualizar ATP: '.$e->getMessage());
            return Command::FAILURE;
        }
    }

    /**
     * Tenta extrair dados a partir de JSON embutido no HTML (quando presente).
     * Retorna array de itens com chaves: rank, player_name, country, points, tournaments_played
     */
    private function tryParseJsonEmbedded(string $html): array
    {
        try {
            // Procurar grandes blobs de JSON comuns (Apollo/Next data)
            if (preg_match('/\{\"data\"\s*:\s*\{.*\}\}/sU', $html, $m)) {
                $json = $m[0];
                $obj = json_decode($json, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    // Heurística: buscar arrays com campos conhecidos
                    $flat = new \RecursiveIteratorIterator(new \RecursiveArrayIterator($obj), \RecursiveIteratorIterator::SELF_FIRST);
                    $items = [];
                    foreach ($flat as $k=>$v) {
                        if (is_array($v)) {
                            // procurar estrutura que contenha rank e name/points
                            if (isset($v['rank']) && isset($v['playerName'])) {
                                $items[] = [
                                    'rank' => $v['rank'],
                                    'player_name' => $v['playerName'],
                                    'country' => $v['countryCode'] ?? ($v['country'] ?? ''),
                                    'points' => $v['points'] ?? 0,
                                    'tournaments_played' => $v['tournamentsPlayed'] ?? 0,
                                ];
                            }
                        }
                    }
                    if (!empty($items)) return $items;
                }
            }
        } catch (\Throwable $e) {
            Log::warning('tryParseJsonEmbedded falhou: '.$e->getMessage());
        }
        return [];
    }

    /**
     * Fallback simples: parseia TR/TD do HTML buscando colunas padrão.
     */
    private function tryParseHtmlTable(string $html): array
    {
        try {
            libxml_use_internal_errors(true);
            $dom = new \DOMDocument();
            $dom->loadHTML($html);
            $xpath = new \DOMXPath($dom);
            // Seleciona linhas da tabela principal de rankings
            $rows = $xpath->query('//table//tr');
            $items = [];
            foreach ($rows as $tr) {
                $tds = $tr->getElementsByTagName('td');
                if ($tds->length < 5) continue;
                $rank = trim($tds->item(0)->textContent);
                $player = trim($tds->item(1)->textContent);
                $points = trim($tds->item(3)->textContent);
                $tours = trim($tds->item(4)->textContent);
                if (!is_numeric(preg_replace('/[^0-9]/','', $rank))) continue;
                $items[] = [
                    'rank' => (int)preg_replace('/[^0-9]/','', $rank),
                    'player_name' => preg_replace('/\s+/', ' ', $player),
                    'country' => '', // país pode ser extraído por flag/atributo alt em melhorias futuras
                    'points' => (int)preg_replace('/[^0-9]/','', $points),
                    'tournaments_played' => (int)preg_replace('/[^0-9]/','', $tours),
                ];
            }
            return $items;
        } catch (\Throwable $e) {
            Log::warning('tryParseHtmlTable falhou: '.$e->getMessage());
            return [];
        }
    }
}
