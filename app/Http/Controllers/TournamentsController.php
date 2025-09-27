<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TournamentsController extends Controller
{
    public function index(Request $request)
    {
        $query = DB::table('tournaments');

        if ($tour = $request->get('tour')) {
            $query->where('tour', strtoupper($tour) === 'WTA' ? 'WTA' : 'ATP');
        }
        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }
        if ($surface = $request->get('surface')) {
            $query->where('surface', $surface);
        }
        if ($from = $request->get('from')) {
            $query->whereDate('start_date', '>=', $from);
        }
        if ($to = $request->get('to')) {
            $query->whereDate('end_date', '<=', $to);
        }

        $tournaments = $query->orderBy('start_date', 'desc')->paginate(20);

        return view('tournaments.index', compact('tournaments'));
    }
}
