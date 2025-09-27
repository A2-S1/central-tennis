@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Quadras</h1>
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
          <th>Tipo</th>
          <th>Acesso</th>
          <th>Atualizado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($courts as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td><a href="{{ route('courts.show', $c) }}" target="_blank">{{ $c->name }}</a></td>
          <td>{{ $c->city }} {{ $c->state ? ', '.$c->state : '' }}</td>
          <td>{{ ucfirst($c->court_type) }}</td>
          <td>{{ ucfirst($c->access_type) }}</td>
          <td>{{ $c->updated_at->diffForHumans() }}</td>
          <td class="text-end">
            <form method="POST" action="{{ route('admin.courts.delete', $c) }}" onsubmit="return confirm('Excluir esta quadra?')" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Excluir</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $courts->links() }}</div>
</div>
@endsection
