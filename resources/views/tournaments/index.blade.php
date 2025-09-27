@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex flex-wrap justify-content-between align-items-end mb-3">
    <h1 class="mb-0">Torneios</h1>
    <form method="GET" class="row g-2">
      <div class="col-auto">
        <select name="tour" class="form-select">
          <option value="">Tour</option>
          <option value="ATP" {{ request('tour')==='ATP'?'selected':'' }}>ATP</option>
          <option value="WTA" {{ request('tour')==='WTA'?'selected':'' }}>WTA</option>
        </select>
      </div>
      <div class="col-auto">
        <select name="status" class="form-select">
          <option value="">Status</option>
          <option value="ongoing" {{ request('status')==='ongoing'?'selected':'' }}>Em andamento</option>
          <option value="upcoming" {{ request('status')==='upcoming'?'selected':'' }}>Futuros</option>
          <option value="completed" {{ request('status')==='completed'?'selected':'' }}>Concluídos</option>
        </select>
      </div>
      <div class="col-auto">
        <select name="surface" class="form-select">
          <option value="">Superfície</option>
          <option value="clay" {{ request('surface')==='clay'?'selected':'' }}>Saibro</option>
          <option value="hard" {{ request('surface')==='hard'?'selected':'' }}>Rápida</option>
          <option value="grass" {{ request('surface')==='grass'?'selected':'' }}>Grama</option>
        </select>
      </div>
      <div class="col-auto">
        <input type="date" name="from" class="form-control" value="{{ request('from') }}" placeholder="Início de">
      </div>
      <div class="col-auto">
        <input type="date" name="to" class="form-control" value="{{ request('to') }}" placeholder="Fim até">
      </div>
      <div class="col-auto">
        <button class="btn btn-outline-secondary">Filtrar</button>
      </div>
    </form>
  </div>

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Nome</th>
          <th>Tour</th>
          <th>Nível</th>
          <th>Superfície</th>
          <th>Cidade</th>
          <th>País</th>
          <th>Início</th>
          <th>Fim</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>
        @forelse($tournaments as $t)
          <tr>
            <td>{{ $t->name }}</td>
            <td>{{ $t->tour }}</td>
            <td>{{ $t->level }}</td>
            <td>{{ $t->surface }}</td>
            <td>{{ $t->city }}</td>
            <td>{{ $t->country }}</td>
            <td>{{ $t->start_date }}</td>
            <td>{{ $t->end_date }}</td>
            <td>{{ $t->status }}</td>
          </tr>
        @empty
          <tr><td colspan="9" class="text-center text-muted">Sem torneios para os filtros selecionados.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>{{ $tournaments->withQueryString()->links() }}</div>
</div>
@endsection
