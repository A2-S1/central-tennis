<?php

namespace App\Http\Controllers;

use App\Models\TennisMatch;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;

class MatchesController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $userId = Auth::id();
        $incoming = TennisMatch::with(['challenger', 'opponent'])
            ->where('opponent_id', $userId)
            ->whereIn('status', ['pending', 'accepted'])
            ->latest()->paginate(5, ['*'], 'incoming');
        $outgoing = TennisMatch::with(['challenger', 'opponent'])
            ->where('challenger_id', $userId)
            ->whereIn('status', ['pending', 'accepted'])
            ->latest()->paginate(5, ['*'], 'outgoing');
        $recent = TennisMatch::with(['challenger', 'opponent'])
            ->where(function($q) use ($userId){
                $q->where('challenger_id', $userId)->orWhere('opponent_id', $userId);
            })
            ->where('status', 'completed')
            ->latest()->paginate(5, ['*'], 'recent');
        return view('matches.index', compact('incoming','outgoing','recent'));
    }

    public function invite(Request $request)
    {
        $to = null;
        if ($slug = $request->get('to')) {
            $to = User::where('slug', $slug)->first();
        }
        return view('matches.invite', compact('to'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'opponent' => 'required|string', // slug ou email
            'scheduled_at' => 'nullable|date',
            'location' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
        ]);
        $opponent = User::where('slug', $data['opponent'])
            ->orWhere('email', $data['opponent'])
            ->firstOrFail();
        if ($opponent->id === Auth::id()) {
            return back()->withErrors(['opponent' => 'Você não pode convidar a si mesmo.']);
        }
        // Checagem de conflito de horário/local (janela de 1 hora)
        if (!empty($data['scheduled_at'])) {
            $start = date('Y-m-d H:i:s', strtotime($data['scheduled_at']) - 60*60);
            $end   = date('Y-m-d H:i:s', strtotime($data['scheduled_at']) + 60*60);
            $u = Auth::id();
            $loc = $data['location'] ?? null;
            // Conflito para o usuário atual
            $existsSelf = TennisMatch::whereBetween('scheduled_at', [$start, $end])
                ->whereIn('status', ['pending','accepted'])
                ->where(function($q) use ($u){ $q->where('challenger_id',$u)->orWhere('opponent_id',$u); })
                ->exists();
            if ($existsSelf) {
                return back()->withErrors(['scheduled_at' => 'Conflito de horário com outro jogo seu.'])->withInput();
            }
            // Conflito para o oponente
            $opId = $opponent->id;
            $existsOpp = TennisMatch::whereBetween('scheduled_at', [$start, $end])
                ->whereIn('status', ['pending','accepted'])
                ->where(function($q) use ($opId){ $q->where('challenger_id',$opId)->orWhere('opponent_id',$opId); })
                ->exists();
            if ($existsOpp) {
                return back()->withErrors(['scheduled_at' => 'O oponente já tem um jogo próximo desse horário.'])->withInput();
            }
            // Mesmo local e horário aproximado
            if ($loc) {
                $sameLoc = TennisMatch::whereBetween('scheduled_at', [$start, $end])
                    ->whereIn('status', ['pending','accepted'])
                    ->where('location', $loc)
                    ->exists();
                if ($sameLoc) {
                    return back()->withErrors(['location' => 'Já existe jogo marcado nesse local nesse horário.'])->withInput();
                }
            }
        }
        $match = TennisMatch::create([
            'challenger_id' => Auth::id(),
            'opponent_id' => $opponent->id,
            'scheduled_at' => $data['scheduled_at'] ?? null,
            'location' => $data['location'] ?? null,
            'notes' => $data['notes'] ?? null,
            'status' => 'pending',
        ]);
        // Notificar por e-mail (simples)
        try {
            Mail::send('emails.matches.invite_received', ['opponent' => $opponent, 'challenger' => Auth::user(), 'datetime' => $data['scheduled_at'] ?? null, 'location' => $data['location'] ?? null], function($m) use ($opponent){
                $m->to($opponent->email)->subject('Novo convite para amistoso');
            });
        } catch (\Throwable $e) {}
        return redirect()->route('matches.index')->with('status', 'Convite enviado!');
    }

    public function accept(TennisMatch $match)
    {
        $this->authorizeView($match);
        if ($match->opponent_id !== Auth::id()) abort(403);
        if ($match->status !== 'pending') return back();
        // Antes de aceitar, checar conflito para o oponente (quem aceita)
        if (!empty($match->scheduled_at)) {
            $start = $match->scheduled_at->copy()->subHour()->toDateTimeString();
            $end   = $match->scheduled_at->copy()->addHour()->toDateTimeString();
            $u = Auth::id();
            $loc = $match->location;
            $exists = TennisMatch::whereBetween('scheduled_at', [$start, $end])
                ->whereIn('status', ['pending','accepted'])
                ->where('id', '!=', $match->id)
                ->where(function($q) use ($u){ $q->where('challenger_id',$u)->orWhere('opponent_id',$u); })
                ->exists();
            if ($exists) {
                return back()->withErrors(['scheduled_at' => 'Conflito de horário com outro jogo seu.']);
            }
            if ($loc) {
                $sameLoc = TennisMatch::whereBetween('scheduled_at', [$start, $end])
                    ->whereIn('status', ['pending','accepted'])
                    ->where('id', '!=', $match->id)
                    ->where('location', $loc)->exists();
                if ($sameLoc) {
                    return back()->withErrors(['scheduled_at' => 'Há outro jogo marcado nesse local e horário.']);
                }
            }
        }
        $match->update(['status' => 'accepted']);
        // Notificar challenger
        try {
            Mail::send('emails.matches.accepted', ['match' => $match], function($m) use ($match){
                $m->to($match->challenger->email)->subject('Convite aceito');
            });
        } catch (\Throwable $e) {}
        return back()->with('status', 'Convite aceito!');
    }

    public function reject(TennisMatch $match)
    {
        $this->authorizeView($match);
        if ($match->opponent_id !== Auth::id()) abort(403);
        if (!in_array($match->status, ['pending','accepted'])) return back();
        $match->update(['status' => 'rejected']);
        // Notificar challenger
        try {
            Mail::send('emails.matches.rejected', ['match' => $match], function($m) use ($match){
                $m->to($match->challenger->email)->subject('Convite recusado');
            });
        } catch (\Throwable $e) {}
        return back()->with('status', 'Convite recusado.');
    }

    public function cancel(TennisMatch $match)
    {
        $this->authorizeView($match);
        if (!in_array(Auth::id(), [$match->challenger_id, $match->opponent_id])) abort(403);
        if (!in_array($match->status, ['pending','accepted'])) return back();
        $match->update(['status' => 'cancelled']);
        // Notificar a outra parte
        try {
            $email = Auth::id() === $match->challenger_id ? $match->opponent->email : $match->challenger->email;
            Mail::send('emails.matches.cancelled', ['match' => $match], function($m) use ($email){
                $m->to($email)->subject('Jogo cancelado');
            });
        } catch (\Throwable $e) {}
        return back()->with('status', 'Jogo cancelado.');
    }

    public function resultForm(TennisMatch $match)
    {
        $this->authorizeView($match);
        if (!in_array($match->status, ['accepted','completed'])) abort(403);
        return view('matches.result', compact('match'));
    }

    public function resultSubmit(Request $request, TennisMatch $match)
    {
        $this->authorizeView($match);
        $data = $request->validate([
            'set1_challenger' => 'nullable|integer|min:0|max:7',
            'set1_opponent' => 'nullable|integer|min:0|max:7',
            'set2_challenger' => 'nullable|integer|min:0|max:7',
            'set2_opponent' => 'nullable|integer|min:0|max:7',
            'set3_challenger' => 'nullable|integer|min:0|max:7',
            'set3_opponent' => 'nullable|integer|min:0|max:7',
            'notes' => 'nullable|string',
        ]);
        // calcular sets vencidos
        $pairs = [
            ['set1_challenger','set1_opponent'],
            ['set2_challenger','set2_opponent'],
            ['set3_challenger','set3_opponent'],
        ];
        $cSets = 0; $oSets = 0;
        foreach ($pairs as $p) {
            $c = $data[$p[0]] ?? null; $o = $data[$p[1]] ?? null;
            if ($c === null || $o === null) continue;
            if ($c === $o) continue; // empates ignorados
            if ($c > $o) $cSets++; else $oSets++;
        }
        $update = array_merge($data, [
            'challenger_sets' => $cSets,
            'opponent_sets' => $oSets,
            'notes' => $data['notes'] ?? $match->notes,
            'status' => 'completed',
        ]);
        $match->update($update);
        return redirect()->route('matches.index')->with('status', 'Resultado registrado!');
    }

    public function history(Request $request)
    {
        $userId = Auth::id();
        $q = TennisMatch::with(['challenger','opponent'])
            ->where(function($w) use ($userId){
                $w->where('challenger_id', $userId)->orWhere('opponent_id', $userId);
            });
        if ($request->filled('status')) {
            $q->where('status', $request->get('status'));
        }
        $matches = $q->latest()->paginate(15)->withQueryString();
        return view('matches.history', compact('matches'));
    }

    private function authorizeView(TennisMatch $match): void
    {
        if (!in_array(Auth::id(), [$match->challenger_id, $match->opponent_id])) {
            abort(403);
        }
    }
}
