@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Torneios Locais</h1>
    @auth
      <a href="{{ route('local_tournaments.create') }}" class="btn btn-primary">Cadastrar torneio</a>
    @endauth
  </div>

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4"><input class="form-control" name="city" value="{{ request('city') }}" placeholder="Cidade"></div>
    <div class="col-md-3"><input type="date" class="form-control" name="from" value="{{ request('from') }}" placeholder="De"></div>
    <div class="col-md-3"><input type="date" class="form-control" name="to" value="{{ request('to') }}" placeholder="Até"></div>
    <div class="col-md-2"><button class="btn btn-outline-secondary w-100">Filtrar</button></div>
  </form>

  <div class="row">
    @forelse($tournaments as $t)
      <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
        <div class="card h-100">
          @if($t->photo_path)
            <img src="{{ asset('storage/'.$t->photo_path) }}" class="card-img-top" alt="{{ $t->name }}" style="max-height: 160px; width: 100%; object-fit: contain; background: #fff; cursor: zoom-in;" onclick="showImageModal('{{ asset('storage/'.$t->photo_path) }}')">
          @endif
          <div class="card-body py-3">
            <h5 class="card-title">{{ $t->name }}</h5>
            <div class="text-muted small mb-1">{{ $t->city }} {{ $t->state ? ', '.$t->state : '' }}</div>
            <div class="mb-2">Data: {{ $t->start_date->format('d/m/Y') }} {{ $t->end_date ? ' - '.$t->end_date->format('d/m/Y') : '' }}</div>
            <a class="btn btn-sm btn-outline-primary" href="{{ route('local_tournaments.show', $t) }}">Ver detalhes</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-light">Nenhum torneio encontrado.</div></div>
    @endforelse
  </div>

  <div class="mt-3">{{ $tournaments->links() }}</div>

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
