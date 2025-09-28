<?php

namespace App\Http\Controllers;

use App\Models\PersonalRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalRankingsController extends Controller
{
    public function index(\Illuminate\Http\Request $request)
    {
        $q = PersonalRanking::where('user_id', Auth::id());
        $cat = trim((string)$request->get('category'));
        $start = $request->get('start_date');
        $end = $request->get('end_date');

        if ($cat !== '') { $q->where('category', 'like', "%$cat%"); }
        if ($start) { $q->whereDate('date', '>=', $start); }
        if ($end) { $q->whereDate('date', '<=', $end); }

        $summaryQuery = clone $q;
        $bestPosition = (clone $summaryQuery)->whereNotNull('position')->min('position');
        $totalPoints = (clone $summaryQuery)->whereNotNull('points')->sum('points');
        $totalCount = (clone $summaryQuery)->count();

        $items = $q->orderByDesc('date')->orderBy('position')->paginate(15)->withQueryString();

        return view('personal_rankings.index', [
            'items' => $items,
            'filters' => [
                'category' => $cat,
                'start_date' => $start,
                'end_date' => $end,
            ],
            'summary' => [
                'bestPosition' => $bestPosition,
                'totalPoints' => $totalPoints,
                'totalCount' => $totalCount,
            ],
        ]);
    }

    public function create()
    {
        return view('personal_rankings.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'position' => 'nullable|integer|min:1|max:10000',
            'points' => 'nullable|integer|min:0|max:1000000',
            'date' => 'nullable|date',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
        ]);
        $data['user_id'] = Auth::id();
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('personal_rankings', 'public');
        }
        PersonalRanking::create($data);
        return redirect()->route('personal_rankings.index')->with('status','Ranking cadastrado.');
    }

    public function edit(PersonalRanking $personal_ranking)
    {
        abort_unless($personal_ranking->user_id === Auth::id(), 403);
        return view('personal_rankings.edit', ['item' => $personal_ranking]);
    }

    public function update(Request $request, PersonalRanking $personal_ranking)
    {
        abort_unless($personal_ranking->user_id === Auth::id(), 403);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'position' => 'nullable|integer|min:1|max:10000',
            'points' => 'nullable|integer|min:0|max:1000000',
            'date' => 'nullable|date',
            'notes' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:jpg,jpeg,png,gif,webp,pdf|max:5120',
        ]);
        if ($request->hasFile('attachment')) {
            $data['attachment_path'] = $request->file('attachment')->store('personal_rankings', 'public');
        }
        $personal_ranking->update($data);
        return redirect()->route('personal_rankings.index')->with('status','Ranking atualizado.');
    }

    public function destroy(PersonalRanking $personal_ranking)
    {
        abort_unless($personal_ranking->user_id === Auth::id(), 403);
        $personal_ranking->delete();
        return redirect()->route('personal_rankings.index')->with('status','Ranking removido.');
    }
}
