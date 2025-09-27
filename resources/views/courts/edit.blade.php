@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Editar Quadra</h1>
  <form method="POST" action="{{ route('courts.update', $court) }}" class="mt-3">
    @csrf
    @method('PUT')
    <div class="row g-3">
      <div class="col-md-6">
        <label class="form-label">Nome</label>
        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $court->name) }}" required>
        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Endereço</label>
        <input type="text" name="address" class="form-control @error('address') is-invalid @enderror" value="{{ old('address', $court->address) }}" required>
        @error('address')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-4">
        <label class="form-label">Cidade</label>
        <input type="text" name="city" class="form-control @error('city') is-invalid @enderror" value="{{ old('city', $court->city) }}" required>
        @error('city')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-2">
        <label class="form-label">Estado</label>
        <input type="text" name="state" class="form-control @error('state') is-invalid @enderror" value="{{ old('state', $court->state) }}">
        @error('state')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Tipo de quadra</label>
        <select name="court_type" class="form-select @error('court_type') is-invalid @enderror" required>
          @foreach(['saibro'=>'Saibro','rapida'=>'Rápida','grama'=>'Grama','outro'=>'Outro'] as $val=>$label)
            <option value="{{ $val }}" {{ old('court_type', $court->court_type)===$val?'selected':'' }}>{{ $label }}</option>
          @endforeach
        </select>
        @error('court_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Acesso</label>
        <select name="access_type" class="form-select @error('access_type') is-invalid @enderror" required>
          @foreach(['publica'=>'Pública','paga'=>'Paga','condominio'=>'Condomínio'] as $val=>$label)
            <option value="{{ $val }}" {{ old('access_type', $court->access_type)===$val?'selected':'' }}>{{ $label }}</option>
          @endforeach
        </select>
        @error('access_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Vídeo (YouTube URL)</label>
        <input type="url" name="video_url" class="form-control @error('video_url') is-invalid @enderror" value="{{ old('video_url', $court->video_url) }}">
        @error('video_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-6">
        <label class="form-label">Descrição</label>
        <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $court->description) }}</textarea>
        @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Latitude</label>
        <input type="text" name="latitude" class="form-control @error('latitude') is-invalid @enderror" value="{{ old('latitude', $court->latitude) }}">
        @error('latitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-md-3">
        <label class="form-label">Longitude</label>
        <input type="text" name="longitude" class="form-control @error('longitude') is-invalid @enderror" value="{{ old('longitude', $court->longitude) }}">
        @error('longitude')<div class="invalid-feedback">{{ $message }}</div>@enderror
      </div>
      <div class="col-12">
        <button class="btn btn-success">Salvar alterações</button>
        <a href="{{ route('courts.show', $court) }}" class="btn btn-outline-secondary">Cancelar</a>
      </div>
    </div>
  </form>
</div>
@endsection
