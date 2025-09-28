@extends('layouts.app')

@section('content')
<div class="container">
  <nav class="mb-3"><a href="{{ route('admin.rankings.index') }}" class="text-decoration-none">← Voltar</a></nav>

  <div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="mb-0">Entradas • {{ $table->name }}</h1>
    <div class="d-flex gap-2">
      <form method="POST" action="{{ route('admin.rankings.entries.clear', $table) }}" onsubmit="return confirm('Remover todas as entradas desta tabela?')">
        @csrf
        <button class="btn btn-outline-danger">Limpar</button>
      </form>
      <form method="POST" action="{{ route('admin.rankings.entries.import', $table) }}" enctype="multipart/form-data" class="d-flex gap-2">
        @csrf
        <input type="file" name="csv" class="form-control" accept=".csv,text/csv">
        <select name="delimiter" class="form-select">
          <option value=";">;</option>
          <option value=",">,</option>
        </select>
        <button class="btn btn-primary">Importar CSV</button>
      </form>
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
  @if($errors->any())<div class="alert alert-danger">{{ $errors->first() }}</div>@endif

  <div class="table-responsive mt-3">
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
