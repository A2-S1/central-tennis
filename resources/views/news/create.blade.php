@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Escrever notícia</h1>
  <form method="POST" action="{{ route('news.store') }}" enctype="multipart/form-data" class="row g-3 mt-2">
    @csrf
    <div class="col-md-8">
      <label class="form-label">Título</label>
      <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Publicar em</label>
      <input type="datetime-local" name="published_at" class="form-control @error('published_at') is-invalid @enderror" value="{{ old('published_at') }}">
      @error('published_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
      <label class="form-label">Texto</label>
      <textarea name="body" rows="10" class="form-control @error('body') is-invalid @enderror" placeholder="Escreva a notícia aqui. Use Enter para pular linhas.">{{ old('body') }}</textarea>
      @error('body')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
      <label class="form-label">Link externo (opcional)</label>
      <input type="url" name="external_url" class="form-control @error('external_url') is-invalid @enderror" value="{{ old('external_url') }}" placeholder="https://exemplo.com/noticia">
      @error('external_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <label class="form-label">Imagem de destaque (opcional)</label>
      <input type="file" name="image" accept="image/*" class="form-control @error('image') is-invalid @enderror">
      @error('image')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6 d-flex align-items-end">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_pinned" value="1" id="pin" {{ old('is_pinned') ? 'checked' : '' }}>
        <label class="form-check-label" for="pin">Destacar notícia (fixar no topo)</label>
      </div>
    </div>

    <div class="col-12">
      <button class="btn btn-success">Publicar</button>
      <a href="{{ route('news.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
