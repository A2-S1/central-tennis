<?php

namespace App\Http\Controllers;

use App\Models\PersonalRanking;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonalRankingsController extends Controller
{
    public function index()
    {
        $items = PersonalRanking::where('user_id', Auth::id())
            ->orderByDesc('date')
            ->orderBy('position')
            ->paginate(15);
        return view('personal_rankings.index', compact('items'));
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
        ]);
        $data['user_id'] = Auth::id();
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
        ]);
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
