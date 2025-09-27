@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Editar notícia</h1>
  <form method="POST" action="{{ route('news.update', $news) }}" enctype="multipart/form-data" class="row g-3 mt-2">
    @csrf
    @method('PUT')
    <div class="col-md-8">
      <label class="form-label">Título</label>
      <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $news->title) }}" required>
      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Publicar em</label>
      <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at', optional($news->published_at)->format('Y-m-d\TH:i')) }}">
      @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
      <label class="form-label">Texto</label>
      <textarea name="body" rows="10" class="form-control @error('body') is-invalid @enderror">{{ old('body', $news->body) }}</textarea>
      @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
      <label class="form-label">Link externo (opcional)</label>
      <input type="url" name="external_url" class="form-control @error('external_url') is-invalid @enderror" value="{{ old('external_url', $news->external_url) }}" placeholder="https://exemplo.com/noticia">
      @error('external_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <label class="form-label">Imagem de destaque (opcional)</label>
      @if($news->image_path)
        <div class="mb-2"><img src="{{ asset('storage/'.$news->image_path) }}" alt="Imagem atual" style="height:80px"></div>
      @endif
      <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
      @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 d-flex align-items-end">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="pin" {{ old('is_pinned', $news->is_pinned) ? 'checked' : '' }}>
        <label class="form-check-label" for="pin">Destacar notícia (fixar no topo)</label>
      </div>
    </div>

    <div class="col-12">
      <button class="btn btn-success">Salvar</button>
      <a href="{{ route('news.show', $news) }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
