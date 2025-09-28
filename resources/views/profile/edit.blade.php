@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      @if(session('status'))
        <div class="alert alert-success">{{ session('status') }}</div>
      @endif
      <div class="card">
        <div class="card-header">Meu Perfil</div>
        <div class="card-body">
          <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data">
            @csrf

            <div class="row mb-3">
              <label class="col-md-3 col-form-label text-md-end">Foto</label>
              <div class="col-md-7 d-flex align-items-center gap-3">
                @if($user->avatar)
                  <img src="{{ asset('storage/'.$user->avatar) }}" alt="avatar" class="avatar avatar-72 rounded">
                @else
                  <div class="text-muted small">Sem foto</div>
                @endif
                <input type="file" name="avatar" class="form-control @error('avatar') is-invalid @enderror" accept="image/*">
                @error('avatar')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-3 col-form-label text-md-end">Nome</label>
              <div class="col-md-7">
                <input type="text" name="name" value="{{ old('name', $user->name) }}" class="form-control @error('name') is-invalid @enderror" required>
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-3 col-form-label text-md-end">Pratica tênis?</label>
              <div class="col-md-7">
                <select name="plays_tennis" class="form-select">
                  <option value="0" {{ old('plays_tennis', (int)$user->plays_tennis)==0?'selected':'' }}>Não</option>
                  <option value="1" {{ old('plays_tennis', (int)$user->plays_tennis)==1?'selected':'' }}>Sim</option>
                </select>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-3 col-form-label text-md-end">Nível</label>
              <div class="col-md-7">
                <select name="tennis_level" class="form-select">
                  <option value="">Selecione...</option>
                  @foreach(['iniciante'=>'Iniciante','intermediario'=>'Intermediário','avancado'=>'Avançado','especial'=>'Especial'] as $val=>$label)
                    <option value="{{ $val }}" {{ old('tennis_level', $user->tennis_level)===$val?'selected':'' }}>{{ $label }}</option>
                  @endforeach
                </select>
                @error('tennis_level')<div class="text-danger small">{{ $message }}</div>@enderror
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-3 col-form-label text-md-end">Onde joga</label>
              <div class="col-md-7">
                <input type="text" name="usual_playing_location" value="{{ old('usual_playing_location', $user->usual_playing_location) }}" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-3 col-form-label text-md-end">Cidade</label>
              <div class="col-md-4">
                <input type="text" name="city" value="{{ old('city', $user->city) }}" class="form-control">
              </div>
              <label class="col-md-1 col-form-label text-md-end">UF</label>
              <div class="col-md-2">
                <input type="text" name="state" value="{{ old('state', $user->state) }}" class="form-control">
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-3 col-form-label text-md-end">Bio</label>
              <div class="col-md-7">
                <textarea name="bio" rows="3" class="form-control">{{ old('bio', $user->bio) }}</textarea>
              </div>
            </div>

            <div class="row mb-3">
              <label class="col-md-3 col-form-label text-md-end">Perfil público</label>
              <div class="col-md-7 d-flex align-items-center">
                <input type="checkbox" name="public_profile" value="1" class="form-check-input me-2" {{ old('public_profile', $user->public_profile) ? 'checked' : '' }}>
                <span class="small text-muted">Permitir que outros encontrem meu perfil</span>
              </div>
            </div>

            <div class="row">
              <div class="col-md-7 offset-md-3">
                <button class="btn btn-primary">Salvar alterações</button>
              </div>
            </div>

          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
