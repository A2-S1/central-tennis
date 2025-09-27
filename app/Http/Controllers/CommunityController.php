<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index(Request $request)
    {
        $query = User::query()->where('public_profile', true);

        if ($request->filled('city')) {
            $query->where('city', 'like', '%'.$request->get('city').'%');
        }
        if ($request->filled('tennis_level')) {
            $query->where('tennis_level', $request->get('tennis_level'));
        }
        if ($request->filled('usual_playing_location')) {
            $query->where('usual_playing_location', 'like', '%'.$request->get('usual_playing_location').'%');
        }

        // Ordenação
        $sort = $request->get('sort', 'name');
        switch ($sort) {
            case 'city':
                $query->orderBy('city')->orderBy('name');
                break;
            case 'level':
                $query->orderBy('tennis_level')->orderBy('name');
                break;
            case 'recent':
                $query->latest();
                break;
            default:
                $query->orderBy('name');
        }

        $players = $query->paginate(12)->withQueryString();

        return view('community.index', compact('players'));
    }
}
