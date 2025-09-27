@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Classificados</h1>
    <div class="d-flex gap-2">
      <form method="GET" class="d-flex" action="{{ route('classifieds.index') }}">
        <select name="category_id" class="form-select form-select-sm me-2">
          <option value="">Todas categorias</option>
          @foreach($categories as $c)
            <option value="{{ $c->id }}" {{ (int)($cat ?? 0) === $c->id ? 'selected' : '' }}>{{ $c->name }}</option>
          @endforeach
        </select>
        <input type="text" name="q" value="{{ $q ?? '' }}" class="form-control form-control-sm me-2" placeholder="Buscar anúncio">
        <button class="btn btn-sm btn-outline-primary">Buscar</button>
      </form>
      @auth
        <a href="{{ route('classifieds.create') }}" class="btn btn-sm btn-primary">Anunciar</a>
      @else
        <a href="{{ route('login') }}" class="btn btn-sm btn-outline-secondary">Anunciar</a>
      @endauth
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="row">
    @forelse($listings as $l)
      <div class="col-md-6 col-lg-4 col-xl-3 mb-3">
        <div class="card h-100">
          @php $img = $l->images->firstWhere('is_primary', true) ?? $l->images->first(); @endphp
          @if($img)
            <img src="{{ asset('storage/'.$img->path) }}" class="card-img-top" alt="{{ $l->title }}" style="max-height: 180px; object-fit: contain; background:#fff;">
          @endif
          <div class="card-body py-3">
            <h5 class="card-title">{{ $l->title }}</h5>
            @if($l->price)
              <div class="mb-2">R$ {{ number_format($l->price,2,',','.') }}</div>
            @endif
            <a class="btn btn-sm btn-outline-primary" href="{{ route('classifieds.show', $l) }}">Ver anúncio</a>
          </div>
        </div>
      </div>
    @empty
      <div class="col-12"><div class="alert alert-light">Nenhum anúncio encontrado.</div></div>
    @endforelse
  </div>

  <div class="mt-3">{{ $listings->links() }}</div>
</div>
@endsection
