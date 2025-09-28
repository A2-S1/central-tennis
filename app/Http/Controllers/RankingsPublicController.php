<?php

namespace App\Http\Controllers;

use App\Models\RankingGroup;
use App\Models\RankingTable;
use App\Models\RankingEntry;
use Illuminate\Http\Request;

class RankingsPublicController extends Controller
{
    public function index(Request $request)
    {
        $q = RankingGroup::query()->where('is_public', true);
        $category = $request->get('category');
        $gender = $request->get('gender');
        $term = trim((string)$request->get('q'));

        if ($category) $q->where('category', $category);
        if ($gender) $q->where('gender', $gender);
        if ($term !== '') $q->where('title', 'like', "%$term%");

        $groups = $q->orderByDesc('period_end')->orderBy('title')->paginate(12)->withQueryString();
        return view('rankings.hub', [
            'groups' => $groups,
            'filters' => compact('category','gender','term'),
        ]);
    }

    public function groupShow(RankingGroup $group)
    {
        abort_unless($group->is_public, 404);
        $tables = $group->tables()->where('is_public', true)->orderBy('name')->get();
        return view('rankings.group', compact('group','tables'));
    }

    public function tableShow(RankingTable $table, Request $request)
    {
        abort_unless($table->is_public && $table->group && $table->group->is_public, 404);
        $entries = $table->entries()->orderBy('position')->paginate(100);
        return view('rankings.table', compact('table','entries'));
    }

    public function tableExport(RankingTable $table)
    {
        abort_unless($table->is_public && $table->group && $table->group->is_public, 404);
        $rows = $table->entries()->orderBy('position')->get(['position','player_name','club','state','points','matches','obs']);

        $filename = 'ranking_'.$table->id.'_'.now()->format('Ymd_His').'.csv';
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($rows) {
            $out = fopen('php://output', 'w');
            fwrite($out, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($out, ['Posição','Jogador','Clube','UF','Pontos','Jogos','Obs'], ';');
            foreach ($rows as $r) {
                fputcsv($out, [
                    $r->position,
                    $r->player_name,
                    $r->club,
                    $r->state,
                    $r->points,
                    $r->matches,
                    $r->obs,
                ], ';');
            }
            fclose($out);
        };
        return response()->stream($callback, 200, $headers);
    }
}
