@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row justify-content-center">
    <div class="col-lg-8">
      <nav class="mb-3">
        <a class="text-decoration-none" href="{{ route('community.index') }}">← Voltar para Comunidade</a>
      </nav>

      <div class="card">
        <div class="card-body d-flex flex-wrap align-items-start gap-3">
          <div>
            @if($player->avatar)
              <img src="{{ asset('storage/'.$player->avatar) }}" alt="{{ $player->name }}" style="width:128px;height:128px;object-fit:contain;border-radius:50%;background:#fff">
            @else
              <div style="width:128px;height:128px;border-radius:50%;background:#e9ecef;display:flex;align-items:center;justify-content:center;font-size:48px;">👤</div>
            @endif
          </div>
          <div class="flex-grow-1">
            <h2 class="mb-1">{{ $player->name }}</h2>
            <div class="text-muted mb-2">{{ $player->city }} {{ $player->state ? ', '.$player->state : '' }}</div>
            <dl class="row mb-0">
              <dt class="col-sm-4">Nível</dt>
              <dd class="col-sm-8">{{ $player->tennis_level ? ucfirst($player->tennis_level) : '—' }}</dd>

              <dt class="col-sm-4">Onde joga</dt>
              <dd class="col-sm-8">{{ $player->usual_playing_location ?: '—' }}</dd>

              <dt class="col-sm-4">Bio</dt>
              <dd class="col-sm-8">{!! $player->bio ? nl2br(e($player->bio)) : '—' !!}</dd>
            </dl>

            <div class="mt-3 d-flex gap-2">
              <a class="btn btn-primary" href="mailto:{{ $player->email }}">Entrar em contato</a>
              @auth
                @if(auth()->id() !== $player->id)
                  <a class="btn btn-success" href="{{ route('matches.invite', ['to' => $player->slug]) }}">Chamar para amistoso</a>
                @endif
              @endauth
              @auth
                @if(auth()->id() === $player->id)
                  <a class="btn btn-outline-secondary" href="{{ route('profile.edit') }}">Editar meu perfil</a>
                @endif
              @endauth
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</div>
@endsection
