<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string)$request->get('q'));
        $products = Product::query()
            ->where('is_active', true)
            ->when($q !== '', function($qb) use ($q){
                $qb->where('name','like',"%$q%");
            })
            ->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        return view('store.index', compact('products','categories','q'));
    }

    public function category(string $slug)
    {
        $category = Category::where('slug',$slug)->firstOrFail();
        $products = $category->products()->where('is_active', true)->latest()->paginate(12);
        return view('store.category', compact('category','products'));
    }

    public function product(string $slug)
    {
        $product = Product::where('slug',$slug)->where('is_active', true)->firstOrFail();
        return view('store.product', compact('product'));
    }
}
