@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Notícias</h1>
    @auth
      <a href="{{ route('news.create') }}" class="btn btn-primary">Escrever notícia</a>
    @endauth
  </div>

  <div class="row mb-4 g-3">
    <div class="col-md-6">
      <div class="card h-100 border-primary">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title mb-1"><i class="bi bi-bar-chart-line me-1"></i> Rankings</h5>
            <div class="text-muted small">Acompanhe ATP/WTA atualizados</div>
          </div>
          <a class="btn btn-outline-primary" href="{{ route('rankings.index') }}">Ver Rankings</a>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <div class="card h-100 border-secondary">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h5 class="card-title mb-1"><i class="bi bi-trophy me-1"></i> Torneios</h5>
            <div class="text-muted small">Calendário e resultados recentes</div>
          </div>
          <a class="btn btn-outline-secondary" href="{{ route('tournaments.index') }}">Ver Torneios</a>
        </div>
      </div>
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="row">
    @forelse($items as $n)
      <div class="col-md-6 mb-3">
        <div class="card h-100">
          @if($n->image_path)
            <img src="{{ asset('storage/'.$n->image_path) }}" class="card-img-top" alt="{{ $n->title }}" style="max-height: 200px; width:100%; object-fit: contain; background:#fff; cursor: zoom-in;" onclick="showImageModal('{{ asset('storage/'.$n->image_path) }}')">
          @endif
          <div class="card-body">
            <h5 class="card-title">{{ $n->title }}</h5>
            <div class="text-muted small mb-2">{{ optional($n->published_at)->format('d/m/Y H:i') }} — por {{ $n->author->name }}</div>
            <div class="card-text">{!! nl2br(e(Str::limit($n->body, 400))) !!}</div>
            <div class="mt-2">
              @if($n->is_pinned)
                <span class="badge text-bg-warning me-2">Destacado</span>
              @endif
              @if($n->external_url)
                <a class="btn btn-sm btn-primary me-1" href="{{ $n->external_url }}" target="_blank" rel="noopener">Ler no site</a>
              @endif
              <a class="btn btn-sm btn-outline-secondary" href="{{ route('rankings.index') }}">Rankings</a>
              <a class="btn btn-sm btn-outline-secondary" href="{{ route('tournaments.index') }}">Torneios</a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-light">Nenhuma notícia publicada.</div></div>
    @endforelse
  </div>

  <div class="mt-3">{{ $items->links() }}</div>

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
