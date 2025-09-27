<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CartItem;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
    private function sessionId(Request $request): string
    {
        return $request->session()->getId();
    }

    public function index(Request $request)
    {
        $sid = $this->sessionId($request);
        $items = CartItem::with('product')
            ->where('session_id', $sid)
            ->when(Auth::check(), fn($q) => $q->orWhere('user_id', Auth::id()))
            ->get();
        $subtotal = $items->reduce(fn($c, $i) => $c + ($i->product->price * $i->quantity), 0);
        return view('store.cart', compact('items','subtotal'));
    }

    public function add(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
            'variant' => 'nullable|string|max:255',
        ]);
        $qty = $data['quantity'] ?? 1;
        $product = Product::findOrFail($data['product_id']);
        if (!$product->is_active) return back()->with('status','Produto indisponível.');
        if (!$product->is_digital && $product->stock !== null && $product->stock < $qty) {
            return back()->with('status','Estoque insuficiente.');
        }
        $sid = $this->sessionId($request);
        $item = CartItem::firstOrNew([
            'session_id' => $sid,
            'user_id' => Auth::id(),
            'product_id' => $product->id,
            'variant' => $data['variant'] ?? null,
        ]);
        $item->quantity = ($item->exists ? $item->quantity : 0) + $qty;
        $item->save();
        return redirect()->route('cart.index')->with('status','Produto adicionado ao carrinho.');
    }

    public function update(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:cart_items,id',
            'quantity' => 'required|integer|min:1',
        ]);
        $item = CartItem::with('product')->findOrFail($data['id']);
        if ($item->product && !$item->product->is_digital && $item->product->stock < $data['quantity']) {
            return back()->with('status','Estoque insuficiente.');
        }
        $item->quantity = $data['quantity'];
        $item->save();
        return back()->with('status','Quantidade atualizada.');
    }

    public function remove(Request $request)
    {
        $data = $request->validate([
            'id' => 'required|integer|exists:cart_items,id',
        ]);
        CartItem::where('id',$data['id'])->delete();
        return back()->with('status','Item removido.');
    }
}
