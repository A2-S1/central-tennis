@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Carrinho</h1>
    <a href="{{ route('store.index') }}" class="btn btn-outline-secondary">Continuar comprando</a>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  @if($items->isEmpty())
    <div class="alert alert-light">Seu carrinho está vazio.</div>
  @else
    <div class="table-responsive">
      <table class="table align-middle">
        <thead>
          <tr>
            <th>Produto</th>
            <th class="text-center" style="width:140px">Qtd</th>
            <th class="text-end" style="width:160px">Preço</th>
            <th class="text-end" style="width:160px">Subtotal</th>
            <th style="width:120px"></th>
          </tr>
        </thead>
        <tbody>
          @foreach($items as $i)
          <tr>
            <td>
              <div class="fw-semibold">{{ $i->product->name }}</div>
              @if($i->variant)<div class="text-muted small">{{ $i->variant }}</div>@endif
            </td>
            <td class="text-center">
              <form method="POST" action="{{ route('cart.update') }}" class="d-inline-flex gap-2 align-items-center justify-content-center">
                @csrf
                <input type="hidden" name="id" value="{{ $i->id }}">
                <input type="number" min="1" name="quantity" value="{{ $i->quantity }}" class="form-control form-control-sm" style="width:72px">
                <button class="btn btn-sm btn-outline-primary">Atualizar</button>
              </form>
            </td>
            <td class="text-end">R$ {{ number_format($i->product->price,2,',','.') }}</td>
            <td class="text-end">R$ {{ number_format($i->product->price * $i->quantity,2,',','.') }}</td>
            <td class="text-end">
              <form method="POST" action="{{ route('cart.remove') }}" onsubmit="return confirm('Remover item?')" class="d-inline">
                @csrf
                <input type="hidden" name="id" value="{{ $i->id }}">
                <button class="btn btn-sm btn-outline-danger">Remover</button>
              </form>
            </td>
          </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-end">
      <div class="card" style="min-width:320px">
        <div class="card-body">
          <div class="d-flex justify-content-between"><span>Subtotal</span><strong>R$ {{ number_format($subtotal,2,',','.') }}</strong></div>
          <div class="text-muted small">O frete será calculado no checkout.</div>
          <a href="{{ route('checkout.show') }}" class="btn btn-success w-100 mt-2">Ir para o checkout</a>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
