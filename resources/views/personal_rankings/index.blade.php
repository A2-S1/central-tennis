@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Meus Rankings</h1>
    <div class="d-flex gap-2">
      <a href="{{ route('personal_rankings.create') }}" class="btn btn-primary">Cadastrar ranking</a>
      <a href="{{ route('personal_rankings.export', request()->all()) }}" class="btn btn-outline-secondary">Exportar CSV</a>
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <form method="GET" class="row g-2 mb-3">
    <div class="col-md-4">
      <input type="text" name="category" class="form-control" placeholder="Categoria" value="{{ $filters['category'] ?? '' }}">
    </div>
    <div class="col-md-3">
      <input type="date" name="start_date" class="form-control" value="{{ $filters['start_date'] ?? '' }}">
    </div>
    <div class="col-md-3">
      <input type="date" name="end_date" class="form-control" value="{{ $filters['end_date'] ?? '' }}">
    </div>
    <div class="col-md-2 d-grid">
      <button class="btn btn-outline-secondary">Filtrar</button>
    </div>
  </form>

  <div class="row g-3 mb-3">
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="text-muted small">Melhor posição</div>
          <div class="display-6">{{ $summary['bestPosition'] ?: '—' }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="text-muted small">Total de pontos</div>
          <div class="display-6">{{ number_format($summary['totalPoints'] ?? 0) }}</div>
        </div>
      </div>
    </div>
    <div class="col-md-4">
      <div class="card h-100">
        <div class="card-body">
          <div class="text-muted small">Registros</div>
          <div class="display-6">{{ $summary['totalCount'] ?? 0 }}</div>
        </div>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Título</th>
          <th>Categoria</th>
          <th>Posição</th>
          <th>Pontos</th>
          <th>Data</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $r)
          <tr>
            <td>{{ $r->title }}</td>
            <td>{{ $r->category ?: '—' }}</td>
            <td>{{ $r->position ?: '—' }}</td>
            <td>{{ $r->points ? number_format($r->points) : '—' }}</td>
            <td>{{ optional($r->date)->format('d/m/Y') ?: '—' }}</td>
            <td class="text-end">
              <a href="{{ route('personal_rankings.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
              <form action="{{ route('personal_rankings.destroy', $r) }}" method="POST" class="d-inline" onsubmit="return confirm('Excluir este registro?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Excluir</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted">Nenhum ranking cadastrado.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>{{ $items->links() }}</div>
</div>
@endsection
