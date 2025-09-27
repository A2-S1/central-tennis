@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Novo anúncio</h1>
  <div class="alert alert-info">Seu anúncio será publicado após aprovação do administrador.</div>

  <form method="POST" action="{{ route('classifieds.store') }}" enctype="multipart/form-data" class="row g-3">
    @csrf
    <div class="col-md-8">
      <label class="form-label">Título</label>
      <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Preço (R$)</label>
      <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price') }}">
      @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <label class="form-label">Condição</label>
      <select name="condition" class="form-select">
        <option value="">Selecione</option>
        <option value="novo" {{ old('condition')==='novo'?'selected':'' }}>Novo</option>
        <option value="seminovo" {{ old('condition')==='seminovo'?'selected':'' }}>Seminovo</option>
        <option value="usado" {{ old('condition')==='usado'?'selected':'' }}>Usado</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Categorias</label>
      <select name="categories[]" class="form-select" multiple size="5">
        @foreach($categories as $c)
          <option value="{{ $c->id }}">{{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Telefone</label>
      <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone') }}" placeholder="(XX) XXXX-XXXX">
      @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">WhatsApp</label>
      <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp') }}" placeholder="(XX) XXXXX-XXXX">
      @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
      <label class="form-label">Descrição</label>
      <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description') }}</textarea>
      @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
      <label class="form-label">Imagens (a primeira será a capa) — máx. 5</label>
      <input type="file" name="images[]" accept="image/*" multiple class="form-control @error('images.*') is-invalid @enderror">
      @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
      <div class="form-text">Você pode selecionar até 5 imagens.</div>
    </div>

    <div class="col-12">
      <button class="btn btn-success">Enviar para aprovação</button>
      <a href="{{ route('classifieds.index') }}" class="btn btn-outline-secondary">Cancelar</a>
    </div>
  </form>
  <script>
    // Máscara simples de telefone/WhatsApp
    function maskPhone(input){
      let v = input.value.replace(/\D/g,'');
      if(v.length > 11) v = v.slice(0,11);
      if(v.length > 6){ input.value = `(${v.slice(0,2)}) ${v.slice(2,7)}-${v.slice(7)}`; return; }
      if(v.length > 2){ input.value = `(${v.slice(0,2)}) ${v.slice(2)}`; return; }
      if(v.length > 0){ input.value = `(${v}`; }
    }
    document.querySelectorAll('input[name="phone"], input[name="whatsapp"]').forEach(el=>{
      el.addEventListener('input', ()=>maskPhone(el));
    });
  </script>
</div>
@endsection
