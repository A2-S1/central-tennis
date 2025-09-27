<?php

namespace App\Http\Controllers;

use App\Models\LocalTournament;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class LocalTournamentsController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index','show']);
    }

    public function index(Request $request)
    {
        $q = LocalTournament::query();
        if ($request->filled('city')) $q->where('city', 'like', '%'.$request->get('city').'%');
        if ($request->filled('from')) $q->where('start_date', '>=', $request->get('from'));
        if ($request->filled('to')) $q->where('start_date', '<=', $request->get('to'));
        $tournaments = $q->latest('start_date')->paginate(12)->withQueryString();
        return view('local_tournaments.index', compact('tournaments'));
    }

    public function create()
    {
        return view('local_tournaments.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:20',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'bracket' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:8192',
            'registration_fee' => 'nullable|numeric|min:0',
            'registration_is_free' => 'sometimes|boolean',
            'ticket_price' => 'nullable|numeric|min:0',
            'ticket_is_free' => 'sometimes|boolean',
        ]);
        $regFree = $request->boolean('registration_is_free');
        $ticketFree = $request->boolean('ticket_is_free');
        $payload = [
            'user_id' => Auth::id(),
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'city' => $data['city'],
            'state' => $data['state'] ?? null,
            'venue' => $data['venue'] ?? null,
            'description' => $data['description'] ?? null,
            'registration_fee' => $regFree ? null : ($data['registration_fee'] ?? null),
            'registration_is_free' => $regFree,
            'ticket_price' => $ticketFree ? null : ($data['ticket_price'] ?? null),
            'ticket_is_free' => $ticketFree,
        ];

        if ($request->hasFile('photo')) {
            $payload['photo_path'] = $request->file('photo')->store('tournaments/photos', 'public');
        }
        if ($request->hasFile('bracket')) {
            $payload['bracket_path'] = $request->file('bracket')->store('tournaments/brackets', 'public');
        }

        $t = LocalTournament::create($payload);
        return redirect()->route('local_tournaments.show', $t)->with('status', 'Torneio criado!');
    }

    public function show(LocalTournament $local_tournament)
    {
        return view('local_tournaments.show', ['t' => $local_tournament]);
    }

    public function edit(LocalTournament $local_tournament)
    {
        $this->authorizeOwner($local_tournament);
        return view('local_tournaments.edit', ['t' => $local_tournament]);
    }

    public function update(Request $request, LocalTournament $local_tournament)
    {
        $this->authorizeOwner($local_tournament);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:20',
            'venue' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'photo' => 'nullable|image|max:4096',
            'bracket' => 'nullable|file|mimes:pdf,jpg,jpeg,png,webp|max:8192',
            'registration_fee' => 'nullable|numeric|min:0',
            'registration_is_free' => 'sometimes|boolean',
            'ticket_price' => 'nullable|numeric|min:0',
            'ticket_is_free' => 'sometimes|boolean',
        ]);
        $regFree = $request->boolean('registration_is_free');
        $ticketFree = $request->boolean('ticket_is_free');
        $payload = [
            'name' => $data['name'],
            'start_date' => $data['start_date'],
            'end_date' => $data['end_date'] ?? null,
            'city' => $data['city'],
            'state' => $data['state'] ?? null,
            'venue' => $data['venue'] ?? null,
            'description' => $data['description'] ?? null,
            'registration_fee' => $regFree ? null : ($data['registration_fee'] ?? null),
            'registration_is_free' => $regFree,
            'ticket_price' => $ticketFree ? null : ($data['ticket_price'] ?? null),
            'ticket_is_free' => $ticketFree,
        ];

        if ($request->hasFile('photo')) {
            $payload['photo_path'] = $request->file('photo')->store('tournaments/photos', 'public');
        }
        if ($request->hasFile('bracket')) {
            $payload['bracket_path'] = $request->file('bracket')->store('tournaments/brackets', 'public');
        }

        $local_tournament->update($payload);
        return redirect()->route('local_tournaments.show', $local_tournament)->with('status', 'Torneio atualizado!');
    }

    public function destroy(LocalTournament $local_tournament)
    {
        $this->authorizeOwner($local_tournament);
        $local_tournament->delete();
        return redirect()->route('local_tournaments.index')->with('status', 'Torneio removido.');
    }

    private function authorizeOwner(LocalTournament $t): void
    {
        if ($t->user_id !== Auth::id()) abort(403);
    }
}
