<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class StoreCategoriesController extends Controller
{
    public function index()
    {
        $items = Category::orderBy('name')->paginate(20);
        return view('admin.store.categories.index', compact('items'));
    }

    public function create()
    {
        return view('admin.store.categories.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);
        $slug = $data['slug'] ?: Str::slug($data['name']);
        Category::create(['name'=>$data['name'],'slug'=>$slug]);
        return redirect()->route('admin.store.categories.index')->with('status','Categoria criada.');
    }

    public function edit(Category $category)
    {
        return view('admin.store.categories.edit', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
        ]);
        $category->update(['name'=>$data['name'],'slug'=>$data['slug'] ?: Str::slug($data['name'])]);
        return redirect()->route('admin.store.categories.index')->with('status','Categoria atualizada.');
    }

    public function destroy(Category $category)
    {
        $category->delete();
        return back()->with('status','Categoria excluída.');
    }
}
