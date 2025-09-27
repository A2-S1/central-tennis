@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Meus anúncios</h1>
    <a href="{{ route('classifieds.create') }}" class="btn btn-sm btn-primary">Novo anúncio</a>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Título</th>
          <th>Status</th>
          <th>Preço</th>
          <th>Atualizado</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @forelse($listings as $l)
        <tr>
          <td>{{ $l->id }}</td>
          <td><a href="{{ route('classifieds.show', $l) }}" target="_blank">{{ $l->title }}</a></td>
          <td>
            <span class="badge {{ $l->status==='approved'?'text-bg-success':($l->status==='pending'?'text-bg-warning':($l->status==='sold'?'text-bg-secondary':'text-bg-dark'))}}">
              {{ strtoupper($l->status) }}
            </span>
          </td>
          <td>{{ $l->price ? 'R$ '.number_format($l->price,2,',','.') : '—' }}</td>
          <td>{{ $l->updated_at->diffForHumans() }}</td>
          <td class="text-end">
            <a href="{{ route('classifieds.edit', $l) }}" class="btn btn-sm btn-outline-primary me-1">Editar</a>
            @if($l->status==='approved')
              <form method="POST" action="{{ route('classifieds.sold', $l) }}" class="d-inline" onsubmit="return confirm('Marcar este anúncio como vendido?')">
                @csrf
                <button class="btn btn-sm btn-outline-secondary">Marcar como vendido</button>
              </form>
            @endif
          </td>
        </tr>
        @empty
        <tr><td colspan="6" class="text-center text-muted">Você ainda não publicou anúncios.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $listings->links() }}</div>
</div>
@endsection
