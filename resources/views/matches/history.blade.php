@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Meu histórico de jogos</h1>
    <a href="{{ route('matches.index') }}" class="btn btn-outline-secondary">Voltar ao painel</a>
  </div>

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <select name="status" class="form-select">
        <option value="">Status</option>
        @foreach(['pending'=>'Pendente','accepted'=>'Aceito','rejected'=>'Recusado','completed'=>'Concluído','cancelled'=>'Cancelado'] as $k=>$v)
          <option value="{{ $k }}" {{ request('status')===$k?'selected':'' }}>{{ $v }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-4">
      <button class="btn btn-outline-secondary w-100">Filtrar</button>
    </div>
  </form>

  <div class="card">
    <ul class="list-group list-group-flush">
      @forelse($matches as $m)
        <li class="list-group-item">
          <div class="d-flex justify-content-between align-items-center">
            <div>
              <div class="fw-semibold">{{ $m->challenger->name }} vs {{ $m->opponent->name }}</div>
              <div class="text-muted small">{{ $m->scheduled_at ? $m->scheduled_at->format('d/m/Y H:i') : 'Sem data' }} — {{ $m->location ?: 'Local a combinar' }}</div>
              <span class="badge bg-secondary">{{ ucfirst($m->status) }}</span>
              @if($m->status==='completed')
                <span class="ms-2">Placar: {{ $m->challenger_sets }} x {{ $m->opponent_sets }}</span>
              @endif
            </div>
            <div>
              @if($m->status==='accepted')
                <a class="btn btn-sm btn-outline-primary" href="{{ route('matches.result.form', $m) }}">Lançar resultado</a>
              @endif
            </div>
          </div>
        </li>
      @empty
        <li class="list-group-item text-muted">Nenhum jogo encontrado.</li>
      @endforelse
    </ul>
    <div class="card-footer">{{ $matches->links() }}</div>
  </div>
</div>
@endsection
