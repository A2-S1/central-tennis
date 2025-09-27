<?php

namespace App\Http\Controllers;

use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NewsController extends Controller
{
    public function index()
    {
        $items = News::orderByDesc('is_pinned')->orderByDesc('published_at')->latest()->paginate(10);
        return view('news.index', compact('items'));
    }

    public function show(News $news)
    {
        return view('news.show', compact('news'));
    }

    public function create()
    {
        $this->authorizeAdmin();
        return view('news.create');
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:4096',
            'published_at' => 'nullable|date',
            'external_url' => 'nullable|url|max:2000',
            'is_pinned' => 'sometimes|boolean',
        ]);
        $payload = [
            'author_id' => Auth::id(),
            'title' => $data['title'],
            'body' => $data['body'],
            'published_at' => $data['published_at'] ?? now(),
            'external_url' => $data['external_url'] ?? null,
            'is_pinned' => $request->boolean('is_pinned'),
        ];
        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('news', 'public');
        }
        News::create($payload);
        return redirect()->route('news.index')->with('status','Notícia publicada!');
    }

    public function edit(News $news)
    {
        $this->authorizeOwner($news);
        return view('news.edit', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $this->authorizeOwner($news);
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'image' => 'nullable|image|max:4096',
            'published_at' => 'nullable|date',
            'external_url' => 'nullable|url|max:2000',
            'is_pinned' => 'sometimes|boolean',
        ]);
        $payload = [
            'title' => $data['title'],
            'body' => $data['body'],
            'published_at' => $data['published_at'] ?? $news->published_at,
            'external_url' => $data['external_url'] ?? $news->external_url,
            'is_pinned' => $request->boolean('is_pinned'),
        ];
        if ($request->hasFile('image')) {
            $payload['image_path'] = $request->file('image')->store('news', 'public');
        }
        $news->update($payload);
        return redirect()->route('news.show', $news)->with('status','Notícia atualizada!');
    }

    public function destroy(News $news)
    {
        $this->authorizeOwner($news);
        $news->delete();
        return redirect()->route('news.index')->with('status','Notícia removida.');
    }

    private function authorizeAdmin(): void
    {
        // Regra simples: qualquer usuário autenticado pode publicar. Se quiser, mude para gate/role.
        if (!Auth::check()) abort(403);
    }

    private function authorizeOwner(News $news): void
    {
        if (!Auth::check() || $news->author_id !== Auth::id()) abort(403);
    }
}
