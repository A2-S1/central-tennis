@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Classificados pendentes</h1>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.classifieds.approved') }}" class="btn btn-sm btn-outline-primary">Aprovados</a>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Título</th>
          <th>Anunciante</th>
          <th>Preço</th>
          <th>Enviado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($items as $l)
        <tr>
          <td>{{ $l->id }}</td>
          <td><a href="{{ route('classifieds.show', $l) }}" target="_blank">{{ $l->title }}</a></td>
          <td>{{ $l->user?->name }}</td>
          <td>{{ $l->price ? 'R$ '.number_format($l->price,2,',','.') : '—' }}</td>
          <td>{{ $l->created_at->diffForHumans() }}</td>
          <td class="text-end">
            <form method="POST" action="{{ route('admin.classifieds.approve', $l) }}" class="d-inline">
              @csrf
              <button class="btn btn-sm btn-success">Aprovar</button>
            </form>
            <form method="POST" action="{{ route('admin.classifieds.reject', $l) }}" class="d-inline ms-1">
              @csrf
              <button class="btn btn-sm btn-warning">Rejeitar</button>
            </form>
            <form method="POST" action="{{ route('admin.classifieds.delete', $l) }}" class="d-inline ms-1" onsubmit="return confirm('Excluir anúncio?')">
              @csrf
              <button class="btn btn-sm btn-outline-danger">Excluir</button>
            </form>
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Nenhum anúncio pendente.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $items->links() }}</div>
</div>
@endsection
