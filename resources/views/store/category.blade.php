@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ $category->name }}</h1>
    <a href="{{ route('store.index') }}" class="btn btn-outline-secondary">Voltar</a>
  </div>

  <div class="row">
    @forelse($products as $p)
      <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
        <div class="card h-100">
          @php $img = $p->images->firstWhere('is_primary', true) ?? $p->images->first(); @endphp
          @if($img)
            <img src="{{ asset('storage/'.$img->path) }}" class="card-img-top" alt="{{ $p->name }}" style="max-height: 180px; object-fit: contain; background:#fff; cursor: zoom-in;" onclick="showImageModal('{{ asset('storage/'.$img->path) }}')" />
          @endif
          <div class="card-body py-3 d-flex flex-column">
            <h5 class="card-title">{{ $p->name }}</h5>
            <div class="mb-2">R$ {{ number_format($p->price, 2, ',', '.') }}</div>
            <div class="mt-auto">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('store.product', $p->slug) }}">Ver produto</a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-light">Nenhum produto nesta categoria.</div></div>
    @endforelse
  </div>

  <div class="mt-3">{{ $products->links() }}</div>

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
