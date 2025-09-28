@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Cadastrar Ranking</h1>

  <form method="POST" action="{{ route('personal_rankings.store') }}" class="row g-3 mt-2">
    @csrf
    <div class="col-md-6">
      <label class="form-label">Título</label>
      <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Categoria</label>
      <input type="text" name="category" class="form-control" value="{{ old('category') }}" placeholder="Ex.: Liga interna, ATP 250, Regional...">
    </div>
    <div class="col-md-3">
      <label class="form-label">Posição</label>
      <input type="number" name="position" class="form-control" value="{{ old('position') }}" min="1">
    </div>
    <div class="col-md-3">
      <label class="form-label">Pontos</label>
      <input type="number" name="points" class="form-control" value="{{ old('points') }}" min="0">
    </div>
    <div class="col-md-3">
      <label class="form-label">Data</label>
      <input type="date" name="date" class="form-control" value="{{ old('date') }}">
    </div>
    <div class="col-12">
      <label class="form-label">Observações</label>
      <textarea name="notes" class="form-control" rows="3">{{ old('notes') }}</textarea>
    </div>
    <div class="col-12">
      <button class="btn btn-primary">Salvar</button>
      <a href="{{ route('personal_rankings.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
