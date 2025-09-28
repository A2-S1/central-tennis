@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Editar Ranking</h1>

  <form method="POST" action="{{ route('personal_rankings.update', $item) }}" class="row g-3 mt-2">
    @csrf
    @method('PUT')
    <div class="col-md-6">
      <label class="form-label">Título</label>
      <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $item->title) }}" required>
      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Categoria</label>
      <input type="text" name="category" class="form-control" value="{{ old('category', $item->category) }}">
    </div>
    <div class="col-md-3">
      <label class="form-label">Posição</label>
      <input type="number" name="position" class="form-control" value="{{ old('position', $item->position) }}" min="1">
    </div>
    <div class="col-md-3">
      <label class="form-label">Pontos</label>
      <input type="number" name="points" class="form-control" value="{{ old('points', $item->points) }}" min="0">
    </div>
    <div class="col-md-3">
      <label class="form-label">Data</label>
      <input type="date" name="date" class="form-control" value="{{ old('date', optional($item->date)->format('Y-m-d')) }}">
    </div>
    <div class="col-12">
      <label class="form-label">Observações</label>
      <textarea name="notes" class="form-control" rows="3">{{ old('notes', $item->notes) }}</textarea>
    </div>
    <div class="col-12">
      <button class="btn btn-primary">Salvar</button>
      <a href="{{ route('personal_rankings.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
