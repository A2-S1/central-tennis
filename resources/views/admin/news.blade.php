@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Notícias</h1>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">Voltar</a>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Título</th>
          <th>Autor</th>
          <th>Publicada</th>
          <th>Destacada</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($items as $n)
        <tr>
          <td>{{ $n->id }}</td>
          <td><a href="{{ route('news.show', $n) }}" target="_blank">{{ $n->title }}</a></td>
          <td>{{ $n->author?->name }}</td>
          <td>{{ optional($n->published_at)->format('d/m/Y H:i') }}</td>
          <td>{!! $n->is_pinned ? '<span class="badge text-bg-warning">Sim</span>' : '—' !!}</td>
          <td class="text-end">
            <form method="POST" action="{{ route('admin.news.delete', $n) }}" onsubmit="return confirm('Excluir esta notícia?')" class="d-inline">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-outline-danger">Excluir</button>
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
