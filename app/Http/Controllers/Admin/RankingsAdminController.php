<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\RankingGroup;
use App\Models\RankingTable;
use App\Models\RankingEntry;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class RankingsAdminController extends Controller
{
    public function index()
    {
        $groups = RankingGroup::orderByDesc('period_end')->orderBy('title')->paginate(15);
        return view('admin.rankings.index', compact('groups'));
    }

    public function createGroup()
    {
        return view('admin.rankings.group_form', ['group' => new RankingGroup()]);
    }

    public function storeGroup(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'category' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:20',
            'badge_url' => 'nullable|url',
            'is_public' => 'boolean',
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_public'] = $request->boolean('is_public');
        RankingGroup::create($data);
        return redirect()->route('admin.rankings.index')->with('status','Grupo criado.');
    }

    public function editGroup(RankingGroup $group)
    {
        return view('admin.rankings.group_form', compact('group'));
    }

    public function updateGroup(Request $request, RankingGroup $group)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date|after_or_equal:period_start',
            'category' => 'nullable|string|max:50',
            'gender' => 'nullable|string|max:20',
            'badge_url' => 'nullable|url',
            'is_public' => 'boolean',
        ]);
        $data['slug'] = $data['slug'] ?: Str::slug($data['title']);
        $data['is_public'] = $request->boolean('is_public');
        $group->update($data);
        return redirect()->route('admin.rankings.index')->with('status','Grupo atualizado.');
    }

    public function deleteGroup(RankingGroup $group)
    {
        $group->delete();
        return redirect()->route('admin.rankings.index')->with('status','Grupo excluído.');
    }

    public function createTable(Request $request)
    {
        $groupId = $request->get('group');
        $group = $groupId ? RankingGroup::find($groupId) : null;
        return view('admin.rankings.table_form', ['table' => new RankingTable(), 'group' => $group]);
    }

    public function storeTable(Request $request)
    {
        $data = $request->validate([
            'ranking_group_id' => 'required|exists:ranking_groups,id',
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'is_public' => 'boolean',
        ]);
        $data['is_public'] = $request->boolean('is_public');
        RankingTable::create($data);
        return redirect()->route('admin.rankings.index')->with('status','Tabela criada.');
    }

    public function editTable(RankingTable $table)
    {
        return view('admin.rankings.table_form', compact('table'));
    }

    public function updateTable(Request $request, RankingTable $table)
    {
        $data = $request->validate([
            'ranking_group_id' => 'required|exists:ranking_groups,id',
            'name' => 'required|string|max:255',
            'notes' => 'nullable|string',
            'is_public' => 'boolean',
        ]);
        $data['is_public'] = $request->boolean('is_public');
        $table->update($data);
        return redirect()->route('admin.rankings.index')->with('status','Tabela atualizada.');
    }

    public function deleteTable(RankingTable $table)
    {
        $table->delete();
        return redirect()->route('admin.rankings.index')->with('status','Tabela excluída.');
    }

    public function entries(RankingTable $table)
    {
        $entries = $table->entries()->orderBy('position')->paginate(100);
        return view('admin.rankings.table_entries', compact('table','entries'));
    }

    public function clearEntries(RankingTable $table)
    {
        $table->entries()->delete();
        return redirect()->route('admin.rankings.entries', $table)->with('status','Entradas removidas.');
    }

    public function importEntries(Request $request, RankingTable $table)
    {
        $data = $request->validate([
            'csv' => 'required|file|mimes:csv,txt',
            'delimiter' => 'nullable|string|in:;,',
        ]);
        $delimiter = $data['delimiter'] ?? ';';

        $path = $request->file('csv')->getRealPath();
        $handle = fopen($path, 'r');
        if (!$handle) return back()->withErrors(['csv'=>'Falha ao abrir o arquivo']);

        // Tentar detectar BOM e pular
        $firstBytes = fread($handle, 3);
        if ($firstBytes !== chr(0xEF).chr(0xBB).chr(0xBF)) {
            // não tinha BOM, volta ao início
            fseek($handle, 0);
        }

        DB::transaction(function() use ($handle, $delimiter, $table) {
            // Ler cabeçalho
            $header = fgetcsv($handle, 0, $delimiter);
            $map = $this->mapHeader($header);

            while (($row = fgetcsv($handle, 0, $delimiter)) !== false) {
                $val = fn($key) => isset($map[$key]) && isset($row[$map[$key]]) ? trim($row[$map[$key]]) : null;
                $position = (int)preg_replace('/[^0-9]/','', $val('position'));
                if ($position <= 0) continue;
                RankingEntry::updateOrCreate(
                    ['ranking_table_id'=>$table->id, 'position'=>$position],
                    [
                        'player_name' => (string)$val('player_name'),
                        'club' => (string)$val('club'),
                        'state' => (string)$val('state'),
                        'points' => (int)preg_replace('/[^0-9]/','', (string)$val('points')),
                        'matches' => $val('matches')!==null ? (int)preg_replace('/[^0-9]/','', (string)$val('matches')) : null,
                        'obs' => (string)$val('obs'),
                    ]
                );
            }
        });
        fclose($handle);

        return redirect()->route('admin.rankings.entries', $table)->with('status','Importação concluída.');
    }

    private function mapHeader(?array $header): array
    {
        $map = [];
        if (!$header) return $map;
        $norm = fn($s)=> strtolower(trim(preg_replace('/\s+/', ' ', $s ?? '')));
        $keys = [
            'position' => ['pos','posição','position','#','rank'],
            'player_name' => ['jogador','jogador(a)','player','nome'],
            'club' => ['clube','club'],
            'state' => ['uf','estado','state'],
            'points' => ['pontos','points'],
            'matches' => ['jogos','matches'],
            'obs' => ['obs','observação','notes'],
        ];
        foreach ($header as $i=>$h) {
            $h = $norm($h);
            foreach ($keys as $field=>$aliases) {
                if (in_array($h, $aliases)) { $map[$field] = $i; break; }
            }
        }
        return $map;
    }
}
