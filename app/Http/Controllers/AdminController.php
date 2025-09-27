<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\TennisCourt;
use App\Models\LocalTournament;
use App\Models\News;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth','admin']);
    }

    public function index()
    {
        return view('admin.dashboard', [
            'usersCount' => User::count(),
            'courtsCount' => TennisCourt::count(),
            'tournamentsCount' => LocalTournament::count(),
            'newsCount' => News::count(),
            'classifiedsPending' => \App\Models\Listing::where('status','pending')->count(),
            'classifiedsApproved' => \App\Models\Listing::where('status','approved')->count(),
        ]);
    }

    public function users(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $users = User::query()
            ->when($q !== '', function($qb) use ($q){
                $qb->where(function($w) use ($q){
                    $w->where('name','like',"%$q%")
                      ->orWhere('email','like',"%$q%");
                });
            })
            ->latest()->paginate(20)->withQueryString();
        return view('admin.users', compact('users','q'));
    }

    public function deleteUser($id)
    {
        $user = User::find($id);
        if (!$user) {
            return back()->with('status', 'Usuário não encontrado.');
        }
        $user->delete();
        \App\Models\AdminLog::log('delete_user', $user->id, 'user', ['by'=>auth()->id()]);
        return back()->with('status', 'Usuário removido.');
    }

    public function setUserAdmin(Request $request, User $user)
    {
        $makeAdmin = $request->boolean('is_admin');
        if (!$makeAdmin) {
            // Evitar remover o último admin do sistema
            $adminCount = User::where('is_admin', true)->count();
            if ($adminCount <= 1 && $user->is_admin) {
                return back()->with('status', 'Não é possível remover o último administrador.');
            }
        }
        $user->is_admin = $makeAdmin;
        $user->save();
        \App\Models\AdminLog::log($makeAdmin? 'promote_admin':'demote_admin', $user->id, 'user', ['by'=>auth()->id()]);
        return back()->with('status', 'Permissão de administrador atualizada.');
    }

    public function courts()
    {
        $courts = TennisCourt::latest()->paginate(20);
        return view('admin.courts', compact('courts'));
    }

    public function deleteCourt(TennisCourt $court)
    {
        $court->delete();
        \App\Models\AdminLog::log('delete_court', $court->id, 'court', ['by'=>auth()->id()]);
        return back()->with('status', 'Quadra removida.');
    }

    public function tournaments()
    {
        $tournaments = LocalTournament::latest()->paginate(20);
        return view('admin.tournaments', compact('tournaments'));
    }

    public function deleteTournament(LocalTournament $local_tournament)
    {
        $local_tournament->delete();
        \App\Models\AdminLog::log('delete_local_tournament', $local_tournament->id, 'local_tournament', ['by'=>auth()->id()]);
        return back()->with('status', 'Torneio removido.');
    }

    public function news()
    {
        $items = News::latest()->paginate(20);
        return view('admin.news', compact('items'));
    }

    public function deleteNews(News $news)
    {
        $news->delete();
        \App\Models\AdminLog::log('delete_news', $news->id, 'news', ['by'=>auth()->id()]);
        return back()->with('status', 'Notícia removida.');
    }
}
