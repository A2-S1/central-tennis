@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Dashboard</h1>

    @if (session('status'))
      <div class="alert alert-success" role="alert">{{ session('status') }}</div>
    @endif

    <div class="row g-3">
      <!-- Sidebar esquerda -->
      <div class="col-lg-3 order-2 order-lg-1">
        <div class="card mb-3">
          <div class="card-body d-flex align-items-center py-2">
            @php($u = Auth::user())
            @if($u && $u->avatar)
              <img src="{{ asset('storage/'.$u->avatar) }}" class="avatar avatar-48 rounded-circle me-3" alt="avatar">
            @else
              <div class="rounded-circle bg-secondary text-white d-flex align-items-center justify-content-center me-3" style="width:48px;height:48px;">{{ Str::upper(Str::substr($u->name ?? 'U',0,1)) }}</div>
            @endif
            <div>
              <div class="fw-semibold mb-0">{{ $u->name ?? 'Usuário' }}</div>
              @if($u && $u->slug)
                <div class="text-muted small">{{ '@'.$u->slug }}</div>
              @endif
            </div>
          </div>
        </div>

        <div class="card mb-3 bg-light">
          <div class="list-group list-group-flush small">
            <a href="{{ route('home') }}" class="list-group-item list-group-item-action py-2 {{ request()->routeIs('home') ? 'active' : '' }}"><i class="bi bi-house me-2"></i>Página Inicial</a>
            <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action py-2 {{ request()->routeIs('profile.edit') ? 'active' : '' }}"><i class="bi bi-person me-2"></i>Meu Perfil</a>
            <a href="{{ route('matches.index') }}" class="list-group-item list-group-item-action py-2 {{ request()->routeIs('matches.*') ? 'active' : '' }}"><i class="bi bi-controller me-2"></i>Painel de Jogos</a>
            <a href="{{ route('local_tournaments.index') }}" class="list-group-item list-group-item-action py-2 {{ request()->is('local-tournaments*') ? 'active' : '' }}"><i class="bi bi-trophy me-2"></i>Meus Torneios</a>
            <a href="{{ route('personal_rankings.index') }}" class="list-group-item list-group-item-action py-2 {{ request()->routeIs('personal_rankings.*') ? 'active' : '' }}"><i class="bi bi-bar-chart-line me-2"></i>Meus Rankings</a>
            <a href="{{ route('tournaments.index') }}" class="list-group-item list-group-item-action py-2 {{ request()->routeIs('tournaments.index') ? 'active' : '' }}"><i class="bi bi-search me-2"></i>Encontrar Torneios</a>
            <a href="{{ route('courts.index') }}" class="list-group-item list-group-item-action py-2 {{ request()->is('courts*') ? 'active' : '' }}"><i class="bi bi-building me-2"></i>Alugue uma Quadra</a>
          </div>
        </div>

        <div class="card">
          <div class="card-header">Atalhos</div>
          <div class="card-body d-grid gap-2">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('matches.invite') }}"><i class="bi bi-people-fill me-1"></i>Chamar para Amistoso</a>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('matches.history') }}"><i class="bi bi-clock-history me-1"></i>Histórico de Jogos</a>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('community.index') }}"><i class="bi bi-people me-1"></i>Comunidade</a>
            <!-- Rankings e Torneios agora estão em Notícias -->
          </div>
        </div>
      </div>

      <!-- Área principal -->
      <div class="col-lg-9 order-1 order-lg-2">
        <div class="row g-3 mb-3">
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <div class="text-muted small">Minhas quadras</div>
                <div class="display-6">{{ $myCourts }}</div>
                <a href="{{ route('courts.create') }}" class="btn btn-sm btn-primary mt-2">Cadastrar quadra</a>
                <a href="{{ route('courts.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Onde jogar</a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <div class="text-muted small">Jogadores na comunidade</div>
                <div class="display-6">{{ $communityCount }}</div>
                <a href="{{ route('community.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Ver comunidade</a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-body">
                <div class="text-muted small">Meus torneios locais</div>
                <div class="display-6">{{ $myLocalTournaments }}</div>
                <a href="{{ route('local_tournaments.create') }}" class="btn btn-sm btn-primary mt-2">Cadastrar torneio</a>
                <a href="{{ route('local_tournaments.index') }}" class="btn btn-sm btn-outline-secondary mt-2">Ver lista</a>
              </div>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header">Minhas últimas quadras</div>
              <ul class="list-group list-group-flush">
                @forelse($myCourtsList as $c)
                  <li class="list-group-item d-flex justify-content-between align-items-center">
                    <div>
                      <a href="{{ route('courts.show', $c) }}">{{ $c->name }}</a>
                      <span class="text-muted small">— {{ $c->city }}{{ $c->state ? ', '.$c->state : '' }}</span>
                    </div>
                    <a href="{{ route('courts.edit', $c) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
                  </li>
                @empty
                  <li class="list-group-item text-muted">Você ainda não cadastrou quadras.</li>
                @endforelse
              </ul>
            </div>
          </div>
          <div class="col-md-6">
            <div class="card h-100">
              <div class="card-header">Suas Informações</div>
              <div class="card-body">
                <div class="mb-2 text-muted small">Últimos 20 jogos</div>
                @php($played = max($recentPlayed,1))
                <div class="mb-2">Jogos: <strong>{{ $recentPlayed }}</strong></div>
                <div class="mb-2">Vitórias: <strong>{{ $recentWins }}</strong></div>
                <div class="progress mb-3" style="height:10px;">
                  <div class="progress-bar bg-success" role="progressbar" style="width: {{ round(($recentWins/$played)*100) }}%"></div>
                </div>
                <div class="mb-2">Derrotas: <strong>{{ $recentLosses }}</strong></div>
                <div class="progress" style="height:10px;">
                  <div class="progress-bar bg-danger" role="progressbar" style="width: {{ round(($recentLosses/$played)*100) }}%"></div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection

