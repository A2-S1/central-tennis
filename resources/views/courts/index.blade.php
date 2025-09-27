@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Onde jogar</h1>
    @auth
      <a href="{{ route('courts.create') }}" class="btn btn-primary">Cadastrar quadra</a>
    @endauth
  </div>

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="city" value="{{ request('city') }}" placeholder="Cidade" class="form-control" />
    </div>
    <div class="col-md-4">
      <select name="court_type" class="form-select">
        <option value="">Tipo de quadra</option>
        @foreach(['saibro'=>'Saibro','rapida'=>'Rápida','grama'=>'Grama','outro'=>'Outro'] as $val=>$label)
          <option value="{{ $val }}" {{ request('court_type')===$val?'selected':'' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <select name="access_type" class="form-select">
        <option value="">Acesso</option>
        @foreach(['publica'=>'Pública','paga'=>'Paga','condominio'=>'Condomínio'] as $val=>$label)
          <option value="{{ $val }}" {{ request('access_type')===$val?'selected':'' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <button class="btn btn-outline-secondary w-100">Filtrar</button>
    </div>
  </form>

  <div class="row">
    @forelse($courts as $court)
      <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
        <div class="card h-100">
          @php $img = $court->images->firstWhere('is_primary', true) ?? $court->images->first(); @endphp
          @if($img)
            <img src="{{ asset('storage/'.$img->path) }}" class="card-img-top" alt="{{ $court->name }}" style="max-height: 160px; width: 100%; object-fit: contain; background: #fff; cursor: zoom-in;" onclick="showImageModal('{{ asset('storage/'.$img->path) }}')" />
          @endif
          <div class="card-body py-3">
            <h5 class="card-title">{{ $court->name }}</h5>
            <p class="card-text small mb-1">{{ $court->city }} {{ $court->state ? ', '.$court->state : '' }}</p>
            @php
              $access = $court->access_type;
              $acBadge = $access === 'publica' ? 'bg-success' : ($access === 'paga' ? 'bg-warning text-dark' : 'bg-dark');
            @endphp
            <p class="card-text">
              <span class="badge bg-info text-dark">{{ ucfirst($court->court_type) }}</span>
              <span class="badge {{ $acBadge }}">{{ ucfirst($court->access_type) }}</span>
            </p>
            <a href="{{ route('courts.show', $court) }}" class="btn btn-sm btn-outline-primary">Ver detalhes</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12">
        <div class="alert alert-info">Nenhuma quadra encontrada.</div>
      </div>
    @endforelse
  </div>

  <div class="mt-3">{{ $courts->withQueryString()->links() }}</div>

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
