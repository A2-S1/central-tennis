@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Produtos</h1>
    <div class="d-flex gap-2">
      <form method="GET" action="{{ route('admin.store.products.index') }}" class="d-flex">
        <input type="text" class="form-control form-control-sm me-2" name="q" value="{{ $q ?? '' }}" placeholder="Buscar produto">
        <button class="btn btn-sm btn-outline-primary">Buscar</button>
      </form>
      <a href="{{ route('admin.store.products.create') }}" class="btn btn-sm btn-primary">Novo produto</a>
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
          <th>Preço</th>
          <th>Ativo</th>
          <th>Estoque</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $p)
        <tr>
          <td>{{ $p->id }}</td>
          <td>{{ $p->name }}</td>
          <td>R$ {{ number_format($p->price,2,',','.') }}</td>
          <td>{!! $p->is_active ? '<span class="badge text-bg-success">Sim</span>' : '—' !!}</td>
          <td>{{ $p->stock }}</td>
          <td class="text-end">
            <a class="btn btn-sm btn-outline-primary" href="{{ route('admin.store.products.edit', $p) }}">Editar</a>
            <form method="POST" action="{{ route('admin.store.products.delete', $p) }}" class="d-inline" onsubmit="return confirm('Excluir produto?')">
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
