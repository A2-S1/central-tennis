@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Checkout</h1>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  @if($items->isEmpty())
    <div class="alert alert-light">Seu carrinho está vazio.</div>
  @else
    <div class="row g-4">
      <div class="col-md-7">
        <div class="card">
          <div class="card-header">Dados de entrega</div>
          <div class="card-body">
            <form method="POST" action="{{ route('checkout.place') }}" id="checkoutForm" class="row g-3">
              @csrf
              <div class="col-md-6">
                <label class="form-label">Nome</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', auth()->user()->name ?? '') }}" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', auth()->user()->email ?? '') }}" required>
                @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-6">
                <label class="form-label">Endereço</label>
                <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address') }}" required>
                @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-4">
                <label class="form-label">Cidade</label>
                <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city') }}" required>
                @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-2">
                <label class="form-label">UF</label>
                <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state') }}" maxlength="2" required>
                @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-md-3">
                <label class="form-label">CEP</label>
                <input type="text" name="zip" class="form-control @error('zip') is-invalid @enderror" value="{{ old('zip') }}" required>
                @error('zip')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
              <div class="col-12">
                <button class="btn btn-success">Finalizar pedido</button>
              </div>
            </form>
          </div>
        </div>
      </div>
      <div class="col-md-5">
        <div class="card">
          <div class="card-header">Resumo</div>
          <div class="card-body">
            <ul class="list-group list-group-flush mb-3">
              @foreach($items as $i)
                <li class="list-group-item d-flex justify-content-between">
                  <span>{{ $i->product->name }} <span class="text-muted">x{{ $i->quantity }}</span></span>
                  <strong>R$ {{ number_format($i->product->price * $i->quantity,2,',','.') }}</strong>
                </li>
              @endforeach
            </ul>
            <div class="d-flex justify-content-between"><span>Subtotal</span><strong>R$ {{ number_format($subtotal,2,',','.') }}</strong></div>
            <div class="d-flex justify-content-between"><span>Frete</span><strong>R$ {{ number_format($shipping,2,',','.') }}</strong></div>
            <hr>
            <div class="d-flex justify-content-between h5"><span>Total</span><strong>R$ {{ number_format($total,2,',','.') }}</strong></div>
          </div>
        </div>
      </div>
    </div>
  @endif
</div>
@endsection
