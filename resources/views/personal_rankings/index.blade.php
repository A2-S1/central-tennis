@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Meus Rankings</h1>
    <a href="{{ route('personal_rankings.create') }}" class="btn btn-primary">Cadastrar ranking</a>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

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
