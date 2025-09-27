@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Categorias</h1>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.store.categories.create') }}" class="btn btn-sm btn-primary">Nova categoria</a>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Slug</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $c)
        <tr>
          <td>{{ $c->id }}</td>
          <td>{{ $c->name }}</td>
          <td class="text-muted">{{ $c->slug }}</td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.store.categories.edit', $c) }}">Editar</a>
            <form method="POST" action="{{ route('admin.store.categories.delete', $c) }}" class="d-inline" onsubmit="return confirm('Excluir categoria?')">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
            </form>
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection
