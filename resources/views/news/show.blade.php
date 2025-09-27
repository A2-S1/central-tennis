@extends('layouts.app')

@section('content')
<div class="container">
  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif
  <div class="row justify-content-center">
    <div class="col-lg-9">
      <article class="card">
        @if($news->image_path)
          <img src="{{ asset('storage/'.$news->image_path) }}" class="card-img-top" alt="{{ $news->title }}">
        @endif
        <div class="card-body">
          <h1 class="card-title">{{ $news->title }}</h1>
          <div class="text-muted small mb-3">{{ optional($news->published_at)->format('d/m/Y H:i') }} — por {{ $news->author->name }}</div>
          <div class="card-text">{!! nl2br(e($news->body)) !!}</div>
          <div class="mt-3 d-flex gap-2">
            @if($news->external_url)
              <a class="btn btn-primary" href="{{ $news->external_url }}" target="_blank" rel="noopener">Ler no site</a>
            @endif
            <a class="btn btn-sm btn-outline-primary" href="{{ route('rankings.index') }}">Rankings</a>
            <a class="btn btn-sm btn-outline-secondary" href="{{ route('tournaments.index') }}">Torneios</a>
          </div>
        </div>
      </article>

      @auth
        @if(auth()->id() === $news->author_id)
          <div class="mt-3 d-flex gap-2">
            <a class="btn btn-outline-primary" href="{{ route('news.edit', $news) }}">Editar</a>
            <form method="POST" action="{{ route('news.destroy', $news) }}" onsubmit="return confirm('Remover notícia?')">
              @csrf @method('DELETE')
              <button class="btn btn-outline-danger">Remover</button>
            </form>
          </div>
        @endif
      @endauth
    </div>
  </div>
</div>
@endsection
