<?php

namespace App\Http\Controllers;

use App\Models\Listing;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ClassifiedsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $cat = (int)$request->get('category_id');
        $listings = Listing::with(['images','user'])
            ->where('status','approved')
            ->when($q !== '', function($qb) use ($q){
                $qb->where(function($w) use ($q){
                    $w->where('title','like',"%$q%")
                      ->orWhere('description','like',"%$q%");
                });
            })
            ->when($cat > 0, function($qb) use ($cat){
                $qb->whereHas('categories', fn($c)=>$c->where('categories.id',$cat));
            })
            ->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        return view('classifieds.index', compact('listings','categories','q','cat'));
    }

    public function show(Listing $listing)
    {
        abort_unless($listing->status === 'approved' || (Auth::check() && $listing->user_id === Auth::id()), 404);
        $listing->load(['images','user','categories']);
        return view('classifieds.show', compact('listing'));
    }

    public function create()
    {
        $this->middleware(['auth']);
        if (!Auth::check()) return redirect()->route('login');
        $categories = Category::orderBy('name')->get();
        return view('classifieds.create', compact('categories'));
    }

    public function store(Request $request)
    {
        if (!Auth::check()) abort(403);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'condition' => 'nullable|string|max:50',
            'phone' => ['nullable','string','max:30','regex:/^[0-9()+\s-]{8,20}$/'],
            'whatsapp' => ['nullable','string','max:30','regex:/^[0-9()+\s-]{8,20}$/'],
            'categories' => 'array',
            'categories.*' => 'integer|exists:categories,id',
            'images' => 'nullable|array|max:5',
            'images.*' => 'image|max:4096',
        ]);
        $listing = Listing::create([
            'user_id' => Auth::id(),
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? null,
            'condition' => $data['condition'] ?? null,
            'phone' => $data['phone'] ?? null,
            'whatsapp' => $data['whatsapp'] ?? null,
            'status' => 'pending',
        ]);
        if (!empty($data['categories'])) {
            $listing->categories()->sync($data['categories']);
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx=>$file) {
                $path = $file->store('classifieds','public');
                $listing->images()->create(['path'=>$path,'is_primary'=>$idx===0]);
            }
        }
        return redirect()->route('classifieds.index')->with('status','Anúncio enviado para aprovação.');
    }

    public function my()
    {
        if (!Auth::check()) abort(403);
        $listings = Listing::with('images')->where('user_id', Auth::id())->latest()->paginate(15);
        return view('classifieds.my', compact('listings'));
    }

    public function markSold(Listing $listing)
    {
        if (!Auth::check() || $listing->user_id !== Auth::id()) abort(403);
        $listing->update(['status' => 'sold']);
        return back()->with('status','Anúncio marcado como vendido.');
    }

    public function deleteOwn(Listing $listing)
    {
        if (!Auth::check() || $listing->user_id !== Auth::id()) abort(403);
        $listing->delete();
        return redirect()->route('classifieds.my')->with('status','Anúncio excluído.');
    }

    public function edit(Listing $listing)
    {
        if (!Auth::check() || $listing->user_id !== Auth::id()) abort(403);
        $categories = Category::orderBy('name')->get();
        $listing->load('images','categories');
        return view('classifieds.edit', compact('listing','categories'));
    }

    public function update(Request $request, Listing $listing)
    {
        if (!Auth::check() || $listing->user_id !== Auth::id()) abort(403);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'nullable|numeric|min:0',
            'condition' => 'nullable|string|max:50',
            'phone' => ['nullable','string','max:30','regex:/^[0-9()+\s-]{8,20}$/'],
            'whatsapp' => ['nullable','string','max:30','regex:/^[0-9()+\s-]{8,20}$/'],
            'categories' => 'array',
            'categories.*' => 'integer|exists:categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|max:4096',
        ]);
        $listing->update([
            'title' => $data['title'],
            'description' => $data['description'] ?? null,
            'price' => $data['price'] ?? null,
            'condition' => $data['condition'] ?? null,
            'phone' => $data['phone'] ?? null,
            'whatsapp' => $data['whatsapp'] ?? null,
        ]);
        if (!empty($data['categories'])) {
            $listing->categories()->sync($data['categories']);
        }
        // Enforce max 5 images
        $current = $listing->images()->count();
        $maxToAdd = max(0, 5 - $current);
        if ($request->hasFile('images') && $maxToAdd > 0) {
            $files = array_slice($request->file('images'), 0, $maxToAdd);
            foreach ($files as $idx=>$file) {
                $path = $file->store('classifieds','public');
                $listing->images()->create(['path'=>$path,'is_primary'=>($current===0 && $idx===0)]);
            }
        }
        return redirect()->route('classifieds.edit',$listing)->with('status','Anúncio atualizado.');
    }

    public function deleteImage(Listing $listing, $imageId)
    {
        if (!Auth::check() || $listing->user_id !== Auth::id()) abort(403);
        $img = $listing->images()->where('id',$imageId)->firstOrFail();
        // evitar ficar sem capa: se excluir a capa, defina outra como capa
        $wasPrimary = $img->is_primary;
        $img->delete();
        if ($wasPrimary) {
            $next = $listing->images()->first();
            if ($next) { $next->is_primary = true; $next->save(); }
        }
        return back()->with('status','Imagem removida.');
    }
}
