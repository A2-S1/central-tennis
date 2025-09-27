@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Editar anúncio</h1>
    <div>
      <a href="{{ route('classifieds.show', $listing) }}" class="btn btn-sm btn-outline-secondary" target="_blank">Ver anúncio</a>
      <a href="{{ route('classifieds.my') }}" class="btn btn-sm btn-outline-primary">Meus anúncios</a>
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <form method="POST" action="{{ route('classifieds.update', $listing) }}" enctype="multipart/form-data" class="row g-3">
    @csrf
    @method('PUT')

    <div class="col-md-8">
      <label class="form-label">Título</label>
      <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $listing->title) }}" required>
      @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-4">
      <label class="form-label">Preço (R$)</label>
      <input type="number" step="0.01" min="0" name="price" class="form-control @error('price') is-invalid @enderror" value="{{ old('price', $listing->price) }}">
      @error('price')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-6">
      <label class="form-label">Condição</label>
      <select name="condition" class="form-select">
        @php($cond = old('condition', $listing->condition))
        <option value="">Selecione</option>
        <option value="novo" {{ $cond==='novo'?'selected':'' }}>Novo</option>
        <option value="seminovo" {{ $cond==='seminovo'?'selected':'' }}>Seminovo</option>
        <option value="usado" {{ $cond==='usado'?'selected':'' }}>Usado</option>
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Categorias</label>
      <select name="categories[]" class="form-select" multiple size="5">
        @php($sel = collect(old('categories', $listing->categories->pluck('id')->all())))
        @foreach($categories as $c)
          <option value="{{ $c->id }}" {{ $sel->contains($c->id)?'selected':'' }}>{{ $c->name }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Telefone</label>
      <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" value="{{ old('phone', $listing->phone) }}" placeholder="(XX) XXXX-XXXX">
      @error('phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>
    <div class="col-md-6">
      <label class="form-label">WhatsApp</label>
      <input type="text" name="whatsapp" class="form-control @error('whatsapp') is-invalid @enderror" value="{{ old('whatsapp', $listing->whatsapp) }}" placeholder="(XX) XXXXX-XXXX">
      @error('whatsapp')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
      <label class="form-label">Descrição</label>
      <textarea name="description" rows="6" class="form-control @error('description') is-invalid @enderror">{{ old('description', $listing->description) }}</textarea>
      @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
      <label class="form-label">Imagens (máx. 5 no total)</label>
      <input type="file" name="images[]" accept="image/*" multiple class="form-control @error('images.*') is-invalid @enderror">
      @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
      <div class="form-text">Você pode adicionar novas imagens. O limite total é de 5.</div>
    </div>

    <div class="col-12">
      <div class="d-flex flex-wrap gap-3">
        @foreach($listing->images as $img)
          <div class="border rounded p-2 text-center" style="width:140px;">
            <img src="{{ asset('storage/'.$img->path) }}" alt="Imagem" style="width:100%;height:100px;object-fit:cover;">
            @if($img->is_primary)
              <div class="small text-success mt-1">Capa</div>
            @endif
            <form method="POST" action="{{ route('classifieds.images.delete', [$listing->id, $img->id]) }}" onsubmit="return confirm('Remover esta imagem?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-outline-danger mt-2">Remover</button>
            </form>
          </div>
        @endforeach
      </div>
    </div>

    <div class="col-12">
      <button class="btn btn-success">Salvar alterações</button>
      <a href="{{ route('classifieds.my') }}" class="btn btn-outline-secondary">Cancelar</a>
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
