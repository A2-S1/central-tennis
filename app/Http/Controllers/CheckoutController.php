<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;

class CheckoutController extends Controller
{
    public function show(Request $request)
    {
        $sid = $request->session()->getId();
        $items = CartItem::with('product')
            ->where('session_id',$sid)
            ->when(Auth::check(), fn($q)=>$q->orWhere('user_id', Auth::id()))
            ->get();
        $subtotal = $items->reduce(fn($c,$i)=>$c + ($i->product->price * $i->quantity), 0);
        $shipping = $subtotal > 0 ? 25.00 : 0.00; // frete simples fixo por enquanto
        $total = $subtotal + $shipping;
        return view('store.checkout', compact('items','subtotal','shipping','total'));
    }

    public function place(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'address' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string|max:2',
            'zip' => 'required|string|max:12',
        ]);

        $sid = $request->session()->getId();
        $items = CartItem::with('product')
            ->where('session_id',$sid)
            ->when(Auth::check(), fn($q)=>$q->orWhere('user_id', Auth::id()))
            ->get();
        if ($items->isEmpty()) return redirect()->route('cart.index')->with('status','Seu carrinho está vazio.');

        $subtotal = $items->reduce(fn($c,$i)=>$c + ($i->product->price * $i->quantity), 0);
        $shipping = $subtotal > 0 ? 25.00 : 0.00; // frete simples por enquanto
        $total = $subtotal + $shipping;

        $order = null;
        DB::transaction(function () use ($items, $subtotal, $shipping, $total, $data, &$order) {
            $order = Order::create([
                'user_id' => Auth::id(),
                'status' => 'received',
                'subtotal' => $subtotal,
                'shipping_total' => $shipping,
                'discount_total' => 0,
                'total' => $total,
                'payment_provider' => 'mercadopago',
                'shipping_address' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'address' => $data['address'],
                    'city' => $data['city'],
                    'state' => $data['state'],
                    'zip' => $data['zip'],
                ],
                'billing_address' => null,
            ]);
            foreach ($items as $ci) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $ci->product_id,
                    'variant' => $ci->variant,
                    'quantity' => $ci->quantity,
                    'unit_price' => $ci->product->price,
                    'line_total' => $ci->product->price * $ci->quantity,
                ]);
            }
            // Limpar carrinho
            CartItem::whereIn('id', $items->pluck('id'))->delete();
        });

        // TODO: integrar com Mercado Pago (criar preferência) e redirecionar
        return redirect()->route('store.index')->with('status', 'Pedido recebido! Em breve você será redirecionado para o pagamento. Pedido #' . $order->id);
    }
}
