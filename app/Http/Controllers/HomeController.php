<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TennisCourt;
use App\Models\User;
use App\Models\LocalTournament;
use App\Models\TennisMatch;
use App\Models\PersonalRanking;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = Auth::user();
        $myCourts = $user ? TennisCourt::where('user_id', $user->id)->count() : 0;
        $communityCount = User::where('public_profile', true)->count();
        $myCourtsList = $user ? TennisCourt::where('user_id', $user->id)->latest()->take(5)->get() : collect();
        $myLocalTournaments = $user ? LocalTournament::where('user_id', $user->id)->count() : 0;
        $myPersonalRankings = $user ? PersonalRanking::where('user_id', $user->id)->count() : 0;

        // Estatísticas simples dos últimos 20 jogos
        $recentPlayed = 0; $recentWins = 0; $recentLosses = 0;
        if ($user) {
            $matches = TennisMatch::where('status', 'completed')
                ->where(function($q) use ($user){
                    $q->where('challenger_id', $user->id)->orWhere('opponent_id', $user->id);
                })
                ->latest()->take(20)->get();
            $recentPlayed = $matches->count();
            foreach ($matches as $m) {
                $isChallenger = $m->challenger_id === $user->id;
                $c = (int)($m->challenger_sets ?? 0);
                $o = (int)($m->opponent_sets ?? 0);
                $won = $isChallenger ? ($c > $o) : ($o > $c);
                if ($won) $recentWins++; else $recentLosses++;
            }
        }

        return view('home', compact('myCourts', 'communityCount', 'myCourtsList', 'myLocalTournaments', 'myPersonalRankings', 'recentPlayed', 'recentWins', 'recentLosses'));
    }
}
