@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
    <div>
      <h1 class="mb-0">Rankings {{ $tour }}</h1>
      <div class="text-muted small">Atualizado em: {{ $latestDate ?: '—' }}</div>
    </div>
    <form method="GET" class="d-flex gap-2">
      <select name="tour" class="form-select">
        <option value="ATP" {{ $tour==='ATP'?'selected':'' }}>ATP</option>
        <option value="WTA" {{ $tour==='WTA'?'selected':'' }}>WTA</option>
      </select>
      <button class="btn btn-outline-secondary">Trocar</button>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Jogador(a)</th>
          <th>País</th>
          <th>Pontos</th>
          <th>Torneios</th>
        </tr>
      </thead>
      <tbody>
        @forelse($rankings as $r)
          <tr>
            <td>{{ $r->rank }}</td>
            <td>{{ $r->player_name }}</td>
            <td>{{ $r->country }}</td>
            <td>{{ number_format($r->points) }}</td>
            <td>{{ $r->tournaments_played }}</td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted">Sem dados disponíveis.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>{{ $rankings->withQueryString()->links() }}</div>
</div>
@endsection
