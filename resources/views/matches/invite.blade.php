@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Chamar amigo para amistoso</h1>
  <form method="POST" action="{{ route('matches.store') }}" class="mt-3 col-lg-8">
    @csrf

    <div class="mb-3">
      <label class="form-label">Jogador (slug ou e-mail)</label>
      <input type="text" name="opponent" class="form-control @error('opponent') is-invalid @enderror" value="{{ old('opponent', optional($to)->slug) }}" placeholder="ex.: alison-augusto ou email@dominio.com" required>
      @error('opponent')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Data e hora (opcional)</label>
        <input type="datetime-local" name="scheduled_at" class="form-control @error('scheduled_at') is-invalid @enderror" value="{{ old('scheduled_at') }}">
        @error('scheduled_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Local (opcional)</label>
        <input type="text" name="location" class="form-control @error('location') is-invalid @enderror" value="{{ old('location') }}" placeholder="Quadra/Clube/Parque">
        @error('location')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
    </div>

    <div class="mb-3 mt-3">
      <label class="form-label">Observações (opcional)</label>
      <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
      @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <button class="btn btn-success">Enviar convite</button>
    <a href="{{ route('matches.index') }}" class="btn btn-outline-secondary">Cancelar</a>
  </form>
</div>
@endsection
