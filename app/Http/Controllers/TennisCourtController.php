<?php

namespace App\Http\Controllers;

use App\Models\TennisCourt;
use App\Models\CourtImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class TennisCourtController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth')->except(['index', 'show']);
    }

    public function index(Request $request)
    {
        $query = TennisCourt::query()->with('images')->where('is_active', true);

        if ($request->filled('city')) {
            $query->where('city', 'like', '%'.$request->get('city').'%');
        }
        if ($request->filled('court_type')) {
            $query->where('court_type', $request->get('court_type'));
        }
        if ($request->filled('access_type')) {
            $query->where('access_type', $request->get('access_type'));
        }

        $courts = $query->latest()->paginate(12);

        return view('courts.index', compact('courts'));
    }

    public function create()
    {
        return view('courts.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:10',
            'court_type' => 'required|in:saibro,rapida,grama,outro',
            'access_type' => 'required|in:publica,paga,condominio',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'video_url' => 'nullable|url',
            'images.*' => 'nullable|image|max:4096',
        ]);

        $data['user_id'] = Auth::id();

        $court = TennisCourt::create($data);

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $path = $file->store('courts', 'public');
                // Optional image processing (resize)
                try {
                    $fullPath = Storage::disk('public')->path($path);
                    $img = Image::make($fullPath)->orientate();
                    $img->resize(1600, null, function ($constraint) { $constraint->aspectRatio(); $constraint->upsize(); });
                    $img->save($fullPath, 85);
                } catch (\Throwable $e) {
                    // ignore processing errors, keep original
                }
                CourtImage::create([
                    'tennis_court_id' => $court->id,
                    'path' => $path,
                    'is_primary' => $idx === 0,
                ]);
            }
        }

        return redirect()->route('courts.show', $court)->with('status', 'Quadra cadastrada com sucesso!');
    }

    public function show(TennisCourt $court)
    {
        $court->load(['images', 'reviews.user']);
        return view('courts.show', compact('court'));
    }

    public function edit(TennisCourt $court)
    {
        $this->authorize('update', $court);
        return view('courts.edit', compact('court'));
    }

    public function update(Request $request, TennisCourt $court)
    {
        $this->authorize('update', $court);
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'nullable|string|max:10',
            'court_type' => 'required|in:saibro,rapida,grama,outro',
            'access_type' => 'required|in:publica,paga,condominio',
            'description' => 'nullable|string',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
            'video_url' => 'nullable|url',
        ]);

        $court->update($data);

        return redirect()->route('courts.show', $court)->with('status', 'Quadra atualizada com sucesso!');
    }

    public function destroy(TennisCourt $court)
    {
        $this->authorize('delete', $court);
        $court->delete();
        return redirect()->route('courts.index')->with('status', 'Quadra removida.');
    }
}
