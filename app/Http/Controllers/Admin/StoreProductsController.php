<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;

class StoreProductsController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $items = Product::query()
            ->when($q !== '', function($qb) use ($q){
                $qb->where('name','like',"%$q%");
            })
            ->latest()->paginate(20)->withQueryString();
        return view('admin.store.products.index', compact('items','q'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.store.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_digital' => 'sometimes|boolean',
            'stock' => 'nullable|integer|min:0',
            'affiliate_url' => 'nullable|url|max:2000',
            'is_active' => 'sometimes|boolean',
            'categories' => 'array',
            'categories.*' => 'integer|exists:categories,id',
            'images.*' => 'image|max:4096',
        ]);
        $product = Product::create([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: Str::slug($data['name']).'-'.Str::random(6),
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'is_digital' => $request->boolean('is_digital'),
            'stock' => $data['stock'] ?? 0,
            'affiliate_url' => $data['affiliate_url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);
        if (!empty($data['categories'])) {
            $product->categories()->sync($data['categories']);
        }
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $idx => $file) {
                $path = $file->store('products','public');
                $product->images()->create([
                    'path' => $path,
                    'is_primary' => $idx === 0,
                ]);
            }
        }
        return redirect()->route('admin.store.products.index')->with('status','Produto criado.');
    }

    public function edit(Product $product)
    {
        $categories = Category::orderBy('name')->get();
        $selected = $product->categories()->pluck('id')->toArray();
        return view('admin.store.products.edit', compact('product','categories','selected'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'is_digital' => 'sometimes|boolean',
            'stock' => 'nullable|integer|min:0',
            'affiliate_url' => 'nullable|url|max:2000',
            'is_active' => 'sometimes|boolean',
            'categories' => 'array',
            'categories.*' => 'integer|exists:categories,id',
            'images.*' => 'image|max:4096',
        ]);
        $product->update([
            'name' => $data['name'],
            'slug' => $data['slug'] ?: $product->slug,
            'description' => $data['description'] ?? null,
            'price' => $data['price'],
            'is_digital' => $request->boolean('is_digital'),
            'stock' => $data['stock'] ?? 0,
            'affiliate_url' => $data['affiliate_url'] ?? null,
            'is_active' => $request->boolean('is_active', true),
        ]);
        $product->categories()->sync($data['categories'] ?? []);
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $path = $file->store('products','public');
                $product->images()->create(['path'=>$path]);
            }
        }
        if ($request->filled('primary_image_id')) {
            $product->images()->update(['is_primary'=>false]);
            $product->images()->where('id', (int)$request->input('primary_image_id'))->update(['is_primary'=>true]);
        }
        return redirect()->route('admin.store.products.edit', $product)->with('status','Produto atualizado.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return back()->with('status','Produto excluído.');
    }
}
