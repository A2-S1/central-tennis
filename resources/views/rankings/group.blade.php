@extends('layouts.app')

@section('content')
<div class="container">
  <nav class="mb-3"><a href="{{ route('rankings.hub') }}" class="text-decoration-none">← Voltar</a></nav>
  <div class="d-flex justify-content-between align-items-center mb-2">
    <h1 class="mb-0">{{ $group->title }}</h1>
    @if($group->badge_url)
      <img src="{{ $group->badge_url }}" alt="badge" style="width:56px;height:56px;object-fit:contain">
    @endif
  </div>
  <div class="text-muted mb-3">Período {{ optional($group->period_start)->format('d/m/Y') ?: '—' }} até {{ optional($group->period_end)->format('d/m/Y') ?: '—' }}</div>

  <div class="row g-3">
    @forelse($tables as $t)
      <div class="col-12">
        <a href="{{ route('rankings.table', $t) }}" class="text-decoration-none">
          <div class="p-3 border rounded d-flex align-items-center justify-content-between">
            <div class="h5 mb-0">{{ $t->name }}</div>
            <div class="text-muted small">Ver tabela</div>
          </div>
        </a>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-light">Nenhuma tabela disponível.</div></div>
    @endforelse
  </div>
</div>
@endsection
