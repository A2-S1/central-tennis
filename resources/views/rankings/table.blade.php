@extends('layouts.app')

@section('content')
<div class="container">
  <nav class="mb-3"><a href="{{ route('rankings.group', $table->group->slug) }}" class="text-decoration-none">← {{ $table->group->title }}</a></nav>

  <div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="mb-0">{{ $table->name }}</h1>
    <a href="{{ route('rankings.table.export', $table) }}" class="btn btn-outline-secondary">Exportar CSV</a>
  </div>
  <div class="text-muted mb-3">Período {{ optional($table->group->period_start)->format('d/m/Y') ?: '—' }} até {{ optional($table->group->period_end)->format('d/m/Y') ?: '—' }}</div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Jogador</th>
          <th>Clube</th>
          <th>UF</th>
          <th>Pontos</th>
          <th>Jogos</th>
          <th>Obs</th>
        </tr>
      </thead>
      <tbody>
        @forelse($entries as $e)
          <tr>
            <td>{{ $e->position }}</td>
            <td>{{ $e->player_name }}</td>
            <td>{{ $e->club ?: '—' }}</td>
            <td>{{ $e->state ?: '—' }}</td>
            <td>{{ number_format($e->points) }}</td>
            <td>{{ $e->matches ?: '—' }}</td>
            <td>{{ $e->obs ?: '—' }}</td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center text-muted">Sem entradas.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>{{ $entries->links() }}</div>
</div>
@endsection
