@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Editar categoria</h1>
  <form method="POST" action="{{ route('admin.store.categories.update', $category) }}" class="row g-3 mt-1">
    @csrf
    @method('PUT')
    <div class="col-md-6">
      <label class="form-label">Nome</label>
      <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $category->name) }}" required>
      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Slug (opcional)</label>
      <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $category->slug) }}">
      @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-12">
      <button class="btn btn-success">Salvar</button>
      <a href="{{ route('admin.store.categories.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
