@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Editar Torneio Local</h1>
  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <form method="POST" action="{{ route('local_tournaments.update', $t) }}" enctype="multipart/form-data" class="row g-3 mt-2">
    @csrf
    @method('PUT')
    <div class="col-md-6">
      <label class="form-label">Nome</label>
      <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $t->name) }}" required>
      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
      <label class="form-label">Data início</label>
      <input type="date" name="start_date" class="form-control @error('start_date') is-invalid @enderror" value="{{ old('start_date', optional($t->start_date)->format('Y-m-d')) }}" required>
      @error('start_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
      <label class="form-label">Data fim</label>
      <input type="date" name="end_date" class="form-control @error('end_date') is-invalid @enderror" value="{{ old('end_date', optional($t->end_date)->format('Y-m-d')) }}">
      @error('end_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Cidade</label>
      <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $t->city) }}" required>
      @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-2">
      <label class="form-label">Estado</label>
      <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $t->state) }}">
      @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Local (clube/academia/parque)</label>
      <input type="text" name="venue" class="form-control @error('venue') is-invalid @enderror" value="{{ old('venue', $t->venue) }}">
      @error('venue')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
      <label class="form-label">Descrição</label>
      <textarea name="description" rows="3" class="form-control @error('description') is-invalid @enderror">{{ old('description', $t->description) }}</textarea>
      @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <label class="form-label">Foto do torneio (jpg/png/webp)</label>
      @if($t->photo_path)
        <div class="mb-2"><img src="{{ asset('storage/'.$t->photo_path) }}" alt="Foto atual" style="height:80px"></div>
      @endif
      <input type="file" name="photo" accept="image/*" class="form-control @error('photo') is-invalid @enderror">
      @error('photo')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Chaveamento (PDF/Imagem)</label>
      @if($t->bracket_path)
        <div class="mb-2"><a target="_blank" href="{{ asset('storage/'.$t->bracket_path) }}">Arquivo atual</a></div>
      @endif
      <input type="file" name="bracket" accept=".pdf,image/*" class="form-control @error('bracket') is-invalid @enderror">
      @error('bracket')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="registration_is_free" value="1" id="regfree" {{ old('registration_is_free', $t->registration_is_free) ? 'checked' : '' }}>
        <label class="form-check-label" for="regfree">Inscrição gratuita</label>
      </div>
      <label class="form-label">Valor inscrição (R$)</label>
      <input type="number" step="0.01" name="registration_fee" class="form-control @error('registration_fee') is-invalid @enderror" value="{{ old('registration_fee', $t->registration_fee) }}">
      @error('registration_fee')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <div class="form-check mb-2">
        <input class="form-check-input" type="checkbox" name="ticket_is_free" value="1" id="tickfree" {{ old('ticket_is_free', $t->ticket_is_free) ? 'checked' : '' }}>
        <label class="form-check-label" for="tickfree">Ingressos gratuitos</label>
      </div>
      <label class="form-label">Valor do ingresso (R$)</label>
      <input type="number" step="0.01" name="ticket_price" class="form-control @error('ticket_price') is-invalid @enderror" value="{{ old('ticket_price', $t->ticket_price) }}">
      @error('ticket_price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
      <button class="btn btn-success">Salvar alterações</button>
      <a href="{{ route('local_tournaments.show', $t) }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
