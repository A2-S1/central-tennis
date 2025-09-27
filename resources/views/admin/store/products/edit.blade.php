@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Editar produto</h1>
  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <form method="POST" action="{{ route('admin.store.products.update', $product) }}" enctype="multipart/form-data" class="row g-3 mt-1">
    @csrf
    @method('PUT')

    <div class="col-md-6">
      <label class="form-label">Nome</label>
      <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $product->name) }}" required>
      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">Slug</label>
      <input type="text" name="slug" class="form-control @error('slug') is-invalid @enderror" value="{{ old('slug', $product->slug) }}">
      @error('slug')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-8">
      <label class="form-label">Descrição</label>
      <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description', $product->description) }}</textarea>
      @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Categorias</label>
      <select name="categories[]" class="form-select" multiple size="6">
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ in_array($c->id, $selected ?? []) ? 'selected' : '' }}>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-3">
      <label class="form-label">Preço (R$)</label>
      <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $product->price) }}" required>
      @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-3">
      <label class="form-label">Estoque</label>
      <input type="number" min="0" name="stock" class="form-control @error('stock') is-invalid @enderror" value="{{ old('stock', $product->stock) }}">
      @error('stock')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">URL de afiliado (opcional)</label>
      <input type="url" name="affiliate_url" class="form-control @error('affiliate_url') is-invalid @enderror" value="{{ old('affiliate_url', $product->affiliate_url) }}">
      @error('affiliate_url')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3 d-flex align-items-end">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_digital" value="1" id="is_digital" {{ old('is_digital', $product->is_digital) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_digital">Produto digital</label>
      </div>
    </div>
    <div class="col-md-3 d-flex align-items-end">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" {{ old('is_active', $product->is_active) ? 'checked' : '' }}>
        <label class="form-check-label" for="is_active">Ativo</label>
      </div>
    </div>

    <div class="col-12">
      <label class="form-label">Imagens (adicionar novas)</label>
      <input type="file" name="images[]" accept="image/*" multiple class="form-control @error('images.*') is-invalid @enderror">
      @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    @if($product->images->count())
    <div class="col-12">
      <label class="form-label">Imagens existentes</label>
      <div class="d-flex flex-wrap gap-3">
        @foreach($product->images as $img)
          <label class="text-center" style="width:120px">
            <img src="{{ asset('storage/'.$img->path) }}" style="width:120px;height:120px;object-fit:cover;border:1px solid #ddd;display:block;margin-bottom:6px">
            <input type="radio" name="primary_image_id" value="{{ $img->id }}" {{ $img->is_primary ? 'checked' : '' }}> Principal
          </label>
        @endforeach
      </div>
    </div>
    @endif

    <div class="col-12">
      <button class="btn btn-success">Salvar</button>
      <a href="{{ route('admin.store.products.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
</div>
@endsection
