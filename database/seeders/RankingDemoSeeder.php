<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\RankingGroup;
use App\Models\RankingTable;
use App\Models\RankingEntry;
use Illuminate\Support\Str;

class RankingDemoSeeder extends Seeder
{
    public function run(): void
    {
        $group = RankingGroup::create([
            'title' => 'Ranking Geral Interclubes 2025',
            'slug' => Str::slug('Ranking Geral Interclubes 2025'),
            'period_start' => '2025-01-01',
            'period_end' => '2025-12-31',
            'category' => 'Adulto',
            'gender' => 'Misto',
            'badge_url' => null,
            'is_public' => true,
        ]);

        $table = RankingTable::create([
            'ranking_group_id' => $group->id,
            'name' => 'Ranking Masculino Interclubes 2025',
            'notes' => 'Demo',
            'is_public' => true,
        ]);

        $players = [
            ['pos'=>1,'name'=>'Jogador A','club'=>'Clube A','uf'=>'SP','pts'=>1200],
            ['pos'=>2,'name'=>'Jogador B','club'=>'Clube B','uf'=>'RJ','pts'=>1150],
            ['pos'=>3,'name'=>'Jogador C','club'=>'Clube C','uf'=>'MG','pts'=>1100],
        ];
        foreach ($players as $p) {
            RankingEntry::create([
                'ranking_table_id' => $table->id,
                'position' => $p['pos'],
                'player_name' => $p['name'],
                'club' => $p['club'],
                'state' => $p['uf'],
                'points' => $p['pts'],
                'matches' => rand(5,20),
                'obs' => null,
            ]);
        }
    }
}
