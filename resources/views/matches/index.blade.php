@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Jogos</h1>
    <div class="d-flex gap-2">
      <a class="btn btn-success" href="{{ route('matches.invite') }}">Chamar amigo para amistoso</a>
      <a class="btn btn-outline-secondary" href="{{ route('matches.history') }}">Meu histórico</a>
    </div>
  </div>

  @if(session('status'))
    <div class="alert alert-success">{{ session('status') }}</div>
  @endif

  <div class="row g-3">
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-header">Convites recebidos</div>
        <ul class="list-group list-group-flush">
          @forelse($incoming as $m)
            <li class="list-group-item">
              <div class="fw-semibold">{{ $m->challenger->name }}</div>
              <div class="text-muted small">{{ $m->scheduled_at ? $m->scheduled_at->format('d/m/Y H:i') : 'Sem data' }} — {{ $m->location ?: 'Local a combinar' }}</div>
              <div class="mt-2 d-flex gap-2">
                <form method="POST" action="{{ route('matches.accept', $m) }}">@csrf<button class="btn btn-sm btn-success">Aceitar</button></form>
                <form method="POST" action="{{ route('matches.reject', $m) }}">@csrf<button class="btn btn-sm btn-outline-danger">Recusar</button></form>
              </div>
            </li>
          @empty
            <li class="list-group-item text-muted">Nenhum convite.</li>
          @endforelse
        </ul>
        <div class="card-footer">{{ $incoming->links() }}</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-header">Meus convites</div>
        <ul class="list-group list-group-flush">
          @forelse($outgoing as $m)
            <li class="list-group-item">
              <div class="fw-semibold">{{ $m->opponent->name }}</div>
              <div class="text-muted small">{{ $m->scheduled_at ? $m->scheduled_at->format('d/m/Y H:i') : 'Sem data' }} — {{ $m->location ?: 'Local a combinar' }}</div>
              <div class="mt-2">
                <span class="badge bg-secondary">{{ ucfirst($m->status) }}</span>
                @if(in_array($m->status, ['pending','accepted']))
                  <form class="d-inline" method="POST" action="{{ route('matches.cancel', $m) }}">@csrf<button class="btn btn-sm btn-outline-danger">Cancelar</button></form>
                @endif
              </div>
            </li>
          @empty
            <li class="list-group-item text-muted">Sem convites enviados.</li>
          @endforelse
        </ul>
        <div class="card-footer">{{ $outgoing->links() }}</div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-header">Recentes</div>
        <ul class="list-group list-group-flush">
          @forelse($recent as $m)
            <li class="list-group-item">
              <div class="fw-semibold">{{ $m->challenger->name }} vs {{ $m->opponent->name }}</div>
              <div class="text-muted small">{{ $m->scheduled_at ? $m->scheduled_at->format('d/m/Y H:i') : 'Sem data' }} — {{ $m->location ?: 'Local a combinar' }}</div>
              @if($m->status==='completed')
                <div class="mt-1">Placar: {{ $m->challenger_sets }} x {{ $m->opponent_sets }}</div>
              @elseif($m->status==='accepted')
                <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('matches.result.form', $m) }}">Lançar resultado</a>
              @endif
            </li>
          @empty
            <li class="list-group-item text-muted">Sem jogos recentes.</li>
          @endforelse
        </ul>
        <div class="card-footer">{{ $recent->links() }}</div>
      </div>
    </div>
  </div>
</div>
@endsection
