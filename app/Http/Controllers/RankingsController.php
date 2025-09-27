<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RankingsController extends Controller
{
    public function index(Request $request)
    {
        $tour = $request->get('tour', 'ATP');
        $table = strtolower($tour) === 'wta' ? 'wta_rankings' : 'atp_rankings';

        $latestDate = DB::table($table)->max('as_of_date');
        $rankings = DB::table($table)
            ->when($latestDate, fn($q) => $q->where('as_of_date', $latestDate))
            ->orderBy('rank')
            ->paginate(50);

        return view('rankings.index', [
            'tour' => strtoupper($tour) === 'WTA' ? 'WTA' : 'ATP',
            'latestDate' => $latestDate,
            'rankings' => $rankings,
        ]);
    }
}
