@extends('layouts.app')

@section('content')
<div class="container">
  <nav class="mb-3"><a href="{{ route('admin.rankings.index') }}" class="text-decoration-none">← Voltar</a></nav>
  <h1 class="mb-3">{{ $group->exists ? 'Editar Grupo' : 'Novo Grupo' }}</h1>

  <form method="POST" action="{{ $group->exists ? route('admin.rankings.groups.update', $group) : route('admin.rankings.groups.store') }}" class="row g-3">
    @csrf
    @if($group->exists) @method('PUT') @endif

    <div class="col-md-6">
      <label class="form-label">Título</label>
      <input type="text" name="title" value="{{ old('title', $group->title) }}" class="form-control @error('title') is-invalid @enderror" required>
      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Slug</label>
      <input type="text" name="slug" value="{{ old('slug', $group->slug) }}" class="form-control">
    </div>

    <div class="col-md-3">
      <label class="form-label">Início</label>
      <input type="date" name="period_start" value="{{ old('period_start', optional($group->period_start)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">Fim</label>
      <input type="date" name="period_end" value="{{ old('period_end', optional($group->period_end)->format('Y-m-d')) }}" class="form-control">
    </div>
    <div class="col-md-3">
      <label class="form-label">Categoria</label>
      <select name="category" class="form-select">
        <option value="">—</option>
        @foreach(['Juvenil','Adulto'] as $cat)
          <option value="{{ $cat }}" {{ old('category', $group->category) === $cat ? 'selected' : '' }}>{{ $cat }}</option>
        @endforeach
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Gênero</label>
      <select name="gender" class="form-select">
        <option value="">—</option>
        @foreach(['M'=>'Masculino','F'=>'Feminino','Misto'=>'Misto'] as $val=>$label)
          <option value="{{ $val }}" {{ old('gender', $group->gender) === $val ? 'selected' : '' }}>{{ $label }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Badge URL (opcional)</label>
      <input type="url" name="badge_url" value="{{ old('badge_url', $group->badge_url) }}" class="form-control">
      <div class="form-text">URL de imagem exibida no card.</div>
    </div>
    <div class="col-md-6 d-flex align-items-end">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', $group->is_public) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_public">Público</label>
      </div>
    </div>

    <div class="col-12">
      <button class="btn btn-primary">Salvar</button>
      <a href="{{ route('admin.rankings.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
