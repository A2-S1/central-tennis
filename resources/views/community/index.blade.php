@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Comunidade</h1>
  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="city" value="{{ request('city') }}" placeholder="Cidade" class="form-control" />
    </div>
    <div class="col-md-4">
      <select name="tennis_level" class="form-select">
        <option value="">Nível</option>
        <option value="iniciante" {{ request('tennis_level')==='iniciante'?'selected':'' }}>Iniciante</option>
        <option value="intermediario" {{ request('tennis_level')==='intermediario'?'selected':'' }}>Intermediário</option>
        <option value="avancado" {{ request('tennis_level')==='avancado'?'selected':'' }}>Avançado</option>
      </select>
    </div>
    <div class="col-md-4">
      <input type="text" name="usual_playing_location" value="{{ request('usual_playing_location') }}" placeholder="Local onde joga" class="form-control" />
    </div>
    <div class="col-md-4">
      <select name="sort" class="form-select">
        @php($s = request('sort'))
        <option value="name" {{ $s==='name'?'selected':'' }}>Ordenar por: Nome</option>
        <option value="city" {{ $s==='city'?'selected':'' }}>Ordenar por: Cidade</option>
        <option value="level" {{ $s==='level'?'selected':'' }}>Ordenar por: Nível</option>
        <option value="recent" {{ $s==='recent'?'selected':'' }}>Ordenar por: Recentes</option>
      </select>
    </div>
    <div class="col-12 col-md-3 mt-2">
      <button class="btn btn-outline-secondary w-100">Filtrar</button>
    </div>
  </form>
<div class="row">
    @forelse($players as $p)
      <div class="col-md-4 mb-3">
        <div class="card h-100">
          <div class="card-body d-flex align-items-start gap-3">
            <a href="{{ route('players.show', $p->slug ?? $p->id) }}" class="text-decoration-none">
              @if($p->avatar)
                <img src="{{ asset('storage/'.$p->avatar) }}" alt="{{ $p->name }}" class="avatar avatar-60">
              @else
                <div style="width:60px;height:60px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;">👤</div>
              @endif
            </a>
            <div>
              <h5 class="card-title mb-1"><a href="{{ route('players.show', $p->slug ?? $p->id) }}">{{ $p->name }}</a></h5>
              <div class="small text-muted mb-1">{{ $p->city }} {{ $p->state ? ', '.$p->state : '' }}</div>
              <div class="mb-1">Nível: <strong>{{ $p->tennis_level ? ucfirst($p->tennis_level) : '—' }}</strong></div>
              <div class="mb-2">Joga em: {{ $p->usual_playing_location ?: '—' }}</div>
              <a href="mailto:{{ $p->email }}" class="btn btn-sm btn-primary">Entrar em contato</a>
              <a href="{{ route('players.show', $p->slug ?? $p->id) }}" class="btn btn-sm btn-outline-secondary">Perfil público</a>
            </div>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-light">Nenhum jogador encontrado.</div></div>
    @endforelse
  </div>

  <div class="mt-3">{{ $players->withQueryString()->links() }}</div>
</div>
@endsection
