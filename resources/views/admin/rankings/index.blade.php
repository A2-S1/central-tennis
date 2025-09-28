@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Admin • Rankings</h1>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.rankings.groups.create') }}" class="btn btn-primary">Novo Grupo</a>
      <a href="{{ route('admin.rankings.tables.create') }}" class="btn btn-outline-secondary">Nova Tabela</a>
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>Título</th>
          <th>Período</th>
          <th>Categoria</th>
          <th>Gênero</th>
          <th>Público</th>
          <th>Ações</th>
        </tr>
      </thead>
      <tbody>
        @forelse($groups as $g)
          <tr>
            <td>{{ $g->title }}</td>
            <td>{{ optional($g->period_start)->format('d/m/Y') }} - {{ optional($g->period_end)->format('d/m/Y') }}</td>
            <td>{{ $g->category ?: '—' }}</td>
            <td>{{ $g->gender ?: '—' }}</td>
            <td>{{ $g->is_public ? 'Sim' : 'Não' }}</td>
            <td class="text-end">
              <a href="{{ route('admin.rankings.groups.edit', $g) }}" class="btn btn-sm btn-outline-secondary">Editar</a>
              <form method="POST" action="{{ route('admin.rankings.groups.delete', $g) }}" class="d-inline" onsubmit="return confirm('Excluir grupo e suas tabelas?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Excluir</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="6" class="text-center text-muted">Nenhum grupo.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div>{{ $groups->links() }}</div>
</div>
@endsection
