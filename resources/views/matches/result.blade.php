@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Lançar resultado</h1>
  <div class="mb-3 text-muted">{{ $match->challenger->name }} vs {{ $match->opponent->name }} — {{ $match->scheduled_at ? $match->scheduled_at->format('d/m/Y H:i') : 'Sem data' }}</div>

  <form method="POST" action="{{ route('matches.result.submit', $match) }}" class="col-lg-6">
    @csrf

    <div class="row g-3 align-items-end">
      <div class="col-md-5">
        <label class="form-label">Sets do {{ $match->challenger->name }}</label>
        <input type="number" name="challenger_sets" class="form-control @error('challenger_sets') is-invalid @enderror" value="{{ old('challenger_sets', $match->challenger_sets) }}" min="0" max="3" required>
        @error('challenger_sets')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-5">
        <label class="form-label">Sets do {{ $match->opponent->name }}</label>
        <input type="number" name="opponent_sets" class="form-control @error('opponent_sets') is-invalid @enderror" value="{{ old('opponent_sets', $match->opponent_sets) }}" min="0" max="3" required>
        @error('opponent_sets')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="mb-3 mt-3">
      <label class="form-label">Observações (opcional)</label>
      <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $match->notes) }}</textarea>
      @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button class="btn btn-primary">Salvar resultado</button>
    <a href="{{ route('matches.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </form>
</div>
@endsection
