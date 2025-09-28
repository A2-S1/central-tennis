@extends('layouts.app')

@section('content')
<div class="container">
  <nav class="mb-3"><a href="{{ route('admin.rankings.index') }}" class="text-decoration-none">← Voltar</a></nav>
  <h1 class="mb-3">{{ $table->exists ? 'Editar Tabela' : 'Nova Tabela' }}</h1>

  <form method="POST" action="{{ $table->exists ? route('admin.rankings.tables.update', $table) : route('admin.rankings.tables.store') }}" class="row g-3">
    @csrf
    @if($table->exists) @method('PUT') @endif

    <div class="col-md-6">
      <label class="form-label">Grupo</label>
      <select name="ranking_group_id" class="form-select" required>
        @php($groups = \App\Models\RankingGroup::orderBy('title')->get())
        @foreach($groups as $g)
          <option value="{{ $g->id }}" {{ old('ranking_group_id', $table->ranking_group_id ?? request('group')) == $g->id ? 'selected' : '' }}>{{ $g->title }}</option>
        @endforeach
      </select>
    </div>

    <div class="col-md-6">
      <label class="form-label">Nome da Tabela</label>
      <input type="text" name="name" value="{{ old('name', $table->name) }}" class="form-control @error('name') is-invalid @enderror" required>
      @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
      <label class="form-label">Notas</label>
      <textarea name="notes" class="form-control" rows="3">{{ old('notes', $table->notes) }}</textarea>
    </div>

    <div class="col-12">
      <div class="form-check">
        <input class="form-check-input" type="checkbox" id="is_public" name="is_public" value="1" {{ old('is_public', $table->is_public) ? 'checked' : '' }}>
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
