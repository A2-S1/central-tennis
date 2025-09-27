@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Register') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="row mb-3">
                            <label for="name" class="col-md-4 col-form-label text-md-end">{{ __('Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="email" class="col-md-4 col-form-label text-md-end">{{ __('Email Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password" class="col-md-4 col-form-label text-md-end">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="password-confirm" class="col-md-4 col-form-label text-md-end">{{ __('Confirm Password') }}</label>

                            <div class="col-md-6">
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                            </div>
                        </div>

                        <hr>
                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Já pratica tênis?</label>
                            <div class="col-md-6">
                                <select id="plays_tennis" name="plays_tennis" class="form-select">
                                    <option value="0" {{ old('plays_tennis') == '0' ? 'selected' : '' }}>Não</option>
                                    <option value="1" {{ old('plays_tennis') == '1' ? 'selected' : '' }}>Sim</option>
                                </select>
                            </div>
                        </div>

                        <div id="tennis_level_group" class="row mb-3" style="display:none;">
                            <label class="col-md-4 col-form-label text-md-end">Nível</label>
                            <div class="col-md-6">
                                <select id="tennis_level" name="tennis_level" class="form-select">
                                    <option value="">Selecione...</option>
                                    <option value="iniciante" {{ old('tennis_level')=='iniciante' ? 'selected' : '' }}>Iniciante</option>
                                    <option value="intermediario" {{ old('tennis_level')=='intermediario' ? 'selected' : '' }}>Intermediário</option>
                                    <option value="avancado" {{ old('tennis_level')=='avancado' ? 'selected' : '' }}>Avançado</option>
                                    <option value="especial" {{ old('tennis_level')=='especial' ? 'selected' : '' }}>Especial</option>
                                </select>
                                @error('tennis_level')
                                    <span class="text-danger small">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Onde normalmente joga?</label>
                            <div class="col-md-6">
                                <input type="text" name="usual_playing_location" class="form-control" value="{{ old('usual_playing_location') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Cidade</label>
                            <div class="col-md-6">
                                <input type="text" name="city" class="form-control" value="{{ old('city') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Estado</label>
                            <div class="col-md-6">
                                <input type="text" name="state" class="form-control" value="{{ old('state') }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label class="col-md-4 col-form-label text-md-end">Perfil público</label>
                            <div class="col-md-6">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value="1" id="public_profile" name="public_profile" {{ old('public_profile') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="public_profile">
                                        Permitir que outros encontrem meu perfil
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Register') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const plays = document.getElementById('plays_tennis');
    const levelGroup = document.getElementById('tennis_level_group');
    function toggleLevel(){
        levelGroup.style.display = plays.value === '1' ? '' : 'none';
    }
    plays.addEventListener('change', toggleLevel);
    toggleLevel();
});
</script>
@if(filter_var(env('RECAPTCHA_ENABLED', false), FILTER_VALIDATE_BOOLEAN))
<script src="https://www.google.com/recaptcha/api.js" async defer></script>
<script>
  // Append widget to form end
  document.addEventListener('DOMContentLoaded', function(){
    const form = document.querySelector('form[action="{{ route('register') }}"]');
    if (form) {
      const wrap = document.createElement('div');
      wrap.className = 'row mb-3';
      wrap.innerHTML = '<div class="col-md-6 offset-md-4">\n  <div class="g-recaptcha" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>\n  @error('captcha')<div class="text-danger small mt-1">{{ $message }}</div>@enderror\n</div>';
      form.insertBefore(wrap, form.querySelector('.row.mb-0'));
    }
  });
</script>
@endif
@endsection

