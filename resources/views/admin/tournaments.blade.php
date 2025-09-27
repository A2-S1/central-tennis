@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Torneios Locais</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Voltar</a>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Cidade/UF</th>
          <th>Data</th>
          <th>Atualizado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($tournaments as $t)
        <tr>
          <td>{{ $t->id }}</td>
          <td><a href="{{ route('local_tournaments.show', $t) }}" target="_blank">{{ $t->name }}</a></td>
          <td>{{ $t->city }} {{ $t->state ? ', '.$t->state : '' }}</td>
          <td>{{ $t->start_date?->format('d/m/Y') }} {{ $t->end_date ? ' - '.$t->end_date->format('d/m/Y') : '' }}</td>
          <td>{{ $t->updated_at->diffForHumans() }}</td>
          <td class="text-end">
            <form method="POST" action="{{ route('admin.tournaments.delete', $t) }}" onsubmit="return confirm('Excluir este torneio?')" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Excluir</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $tournaments->links() }}</div>
</div>
@endsection
