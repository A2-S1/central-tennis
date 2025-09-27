@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row g-4">
    <div class="col-md-6">
      @php $primary = $product->images->firstWhere('is_primary', true) ?? $product->images->first(); @endphp
      <div class="card">
        @if($primary)
          <img id="mainImg" src="{{ asset('storage/'.$primary->path) }}" class="card-img-top" alt="{{ $product->name }}" style="max-height:360px;object-fit:contain;background:#fff;cursor: zoom-in;" onclick="showImageModal('{{ asset('storage/'.$primary->path) }}')">
        @endif
        <div class="card-body">
          <div class="d-flex flex-wrap gap-2">
            @foreach($product->images as $img)
              <img src="{{ asset('storage/'.$img->path) }}" style="width:72px;height:72px;object-fit:cover;cursor:pointer;border:1px solid #ddd" onclick="document.getElementById('mainImg').src=this.src">
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <h1 class="h3">{{ $product->name }}</h1>
      <div class="text-muted mb-2">@foreach($product->categories as $c)<a href="{{ route('store.category',$c->slug) }}" class="me-2 small">#{{ $c->name }}</a>@endforeach</div>
      <div class="h4">R$ {{ number_format($product->price, 2, ',', '.') }}</div>
      <div class="mt-3">{!! nl2br(e($product->description)) !!}</div>

      <div class="mt-3 d-flex gap-2 align-items-center">
        @if($product->affiliate_url)
          <a class="btn btn-primary" href="{{ $product->affiliate_url }}" target="_blank" rel="noopener">Comprar no parceiro</a>
        @else
          <form method="POST" action="{{ route('cart.add') }}" class="d-flex gap-2 align-items-center">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <label class="text-muted small me-1">Qtd</label>
            <input type="number" name="quantity" min="1" value="1" class="form-control" style="width: 90px;">
            <button class="btn btn-success">Adicionar ao carrinho</button>
          </form>
        @endif
      </div>
    </div>
  </div>

  <!-- Modal para ampliar imagem -->
  <div class="modal fade" id="imgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body p-0">
          <img id="imgModalTarget" src="" alt="Imagem" style="width:100%;height:auto;display:block">
        </div>
      </div>
    </div>
  </div>
  <script>
    function showImageModal(src){
      const el = document.getElementById('imgModalTarget');
      if(el){ el.src = src; }
      const modal = new bootstrap.Modal(document.getElementById('imgModal'));
      modal.show();
    }
  </script>
</div>
@endsection
