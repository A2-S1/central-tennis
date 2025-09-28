@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Rankings</h1>
    <button class="btn btn-outline-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#filters" aria-expanded="false">Filtros <i class="bi bi-sliders"></i></button>
  </div>

  <div class="collapse mb-3" id="filters">
    <form method="GET" class="row g-2">
      <div class="col-md-3">
        <input type="text" name="q" class="form-control" placeholder="Buscar por título" value="{{ $filters['term'] ?? '' }}">
      </div>
      <div class="col-md-3">
        <select name="category" class="form-select">
          <option value="">Categoria</option>
          <option value="Juvenil" {{ ($filters['category'] ?? '')==='Juvenil'?'selected':'' }}>Juvenil</option>
          <option value="Adulto" {{ ($filters['category'] ?? '')==='Adulto'?'selected':'' }}>Adulto</option>
        </select>
      </div>
      <div class="col-md-3">
        <select name="gender" class="form-select">
          <option value="">Gênero</option>
          <option value="M" {{ ($filters['gender'] ?? '')==='M'?'selected':'' }}>Masculino</option>
          <option value="F" {{ ($filters['gender'] ?? '')==='F'?'selected':'' }}>Feminino</option>
          <option value="Misto" {{ ($filters['gender'] ?? '')==='Misto'?'selected':'' }}>Misto</option>
        </select>
      </div>
      <div class="col-md-3 d-grid">
        <button class="btn btn-primary">Aplicar</button>
      </div>
    </form>
  </div>

  <style>
    .rank-card { background: #1f4a4a; color: #fff; border-radius: 8px; position: relative; overflow: hidden; }
    .rank-card .angle { position: absolute; right: 0; top: 0; width: 0; height: 0; border-top: 100% solid rgba(0,0,0,.15); border-left: 60px solid transparent; }
    .rank-card .badge-slot { position: absolute; right: 12px; top: 50%; transform: translateY(-50%); }
    .rank-card .badge-slot img { width: 48px; height: 48px; object-fit: contain; }
  </style>

  <div class="row g-3">
    @forelse($groups as $g)
      <div class="col-12">
        <a href="{{ route('rankings.group', $g->slug) }}" class="text-decoration-none">
          <div class="p-3 rank-card">
            <div class="d-flex align-items-center justify-content-between">
              <div>
                <div class="h5 mb-1">{{ $g->title }}</div>
                <div class="small" style="opacity:.9">Período {{ optional($g->period_start)->format('d/m/Y') ?: '—' }} até {{ optional($g->period_end)->format('d/m/Y') ?: '—' }}</div>
              </div>
              <div class="badge-slot">
                @if($g->badge_url)
                  <img src="{{ $g->badge_url }}" alt="badge">
                @endif
              </div>
            </div>
            <div class="angle"></div>
          </div>
        </a>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-light">Nenhum ranking disponível.</div></div>
    @endforelse
  </div>

  <div class="mt-3">{{ $groups->links() }}</div>
</div>
@endsection
