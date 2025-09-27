@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ $court->name }}</h1>
    @can('update', $court)
      <div>
        <a href="{{ route('courts.edit', $court) }}" class="btn btn-outline-primary">Editar</a>
        <form action="{{ route('courts.destroy', $court) }}" method="POST" class="d-inline" onsubmit="return confirm('Remover esta quadra?')">
          @csrf
          @method('DELETE')
          <button class="btn btn-outline-danger">Remover</button>
        </form>
      </div>
    @endcan
  </div>

  <div class="row">
    <div class="col-md-7">
      @if($court->images->count())
        <div id="carouselCourt" class="carousel slide mb-3" data-bs-ride="carousel">
          <div class="carousel-inner">
            @foreach($court->images as $idx => $img)
              <div class="carousel-item {{ $idx===0 ? 'active' : '' }}">
                <img src="{{ asset('storage/'.$img->path) }}" class="d-block w-100" alt="{{ $court->name }}">
                @if($img->caption)
                  <div class="carousel-caption d-none d-md-block">
                    <p>{{ $img->caption }}</p>
                  </div>
                @endif
              </div>
            @endforeach
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#carouselCourt" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#carouselCourt" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Next</span>
          </button>
        </div>
      @endif

      <p class="mb-1"><strong>Endereço:</strong> {{ $court->address }}</p>
      <p class="mb-1"><strong>Cidade/Estado:</strong> {{ $court->city }} {{ $court->state ? ', '.$court->state : '' }}</p>
      @php
        $access = $court->access_type;
        $acBadge = $access === 'publica' ? 'bg-success' : ($access === 'paga' ? 'bg-warning text-dark' : 'bg-dark');
      @endphp
      <p class="mb-1">
        <strong>Tipo:</strong> {{ ucfirst($court->court_type) }}
        <span class="badge {{ $acBadge }} ms-2">{{ ucfirst($court->access_type) }}</span>
      </p>
      @if($court->video_url)
        <div class="ratio ratio-16x9 my-3">
          <iframe src="{{ $court->video_url }}" title="Video" allowfullscreen></iframe>
        </div>
      @endif
      @if($court->description)
        <div class="mt-3">{!! nl2br(e($court->description)) !!}</div>
      @endif
    </div>

    <div class="col-md-5">
      <h4>Avaliações ({{ $court->reviews->count() }})</h4>
      @forelse($court->reviews as $rev)
        <div class="border rounded p-2 mb-2">
          <div class="small text-muted">{{ $rev->user->name }} • {{ $rev->created_at->diffForHumans() }}</div>
          <div>Nota: {{ $rev->rating }} / 5</div>
          @if($rev->comment)
            <div class="mt-1">{!! nl2br(e($rev->comment)) !!}</div>
          @endif
        </div>
      @empty
        <div class="alert alert-light">Ainda não há avaliações.</div>
      @endforelse
    </div>
  </div>
</div>
@endsection
