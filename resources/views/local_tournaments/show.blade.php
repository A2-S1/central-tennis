@extends('layouts.app')

@section('content')
<div class="container">
  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>{{ $t->name }}</h1>
    @auth
      @if($t->user_id === auth()->id())
        <div>
          <a href="{{ route('local_tournaments.edit', $t) }}" class="btn btn-outline-primary">Editar</a>
          <form class="d-inline" method="POST" action="{{ route('local_tournaments.destroy', $t) }}" onsubmit="return confirm('Remover torneio?')">
            @csrf @method('DELETE')
            <button class="btn btn-outline-danger">Remover</button>
          </form>
        </div>
      @endif
    @endauth
  </div>

  <div class="row">
    <div class="col-md-7">
      @if($t->photo_path)
        <img src="{{ asset('storage/'.$t->photo_path) }}" class="img-fluid rounded mb-3" alt="{{ $t->name }}">
      @endif

      <dl class="row">
        <dt class="col-sm-4">Período</dt>
        <dd class="col-sm-8">{{ $t->start_date->format('d/m/Y') }} {{ $t->end_date ? ' - '.$t->end_date->format('d/m/Y') : '' }}</dd>

        <dt class="col-sm-4">Cidade/Estado</dt>
        <dd class="col-sm-8">{{ $t->city }}{{ $t->state ? ', '.$t->state : '' }}</dd>

        <dt class="col-sm-4">Local</dt>
        <dd class="col-sm-8">{{ $t->venue ?: '—' }}</dd>

        <dt class="col-sm-4">Inscrição</dt>
        <dd class="col-sm-8">{{ $t->registration_is_free ? 'Gratuita' : ('R$ '.number_format($t->registration_fee,2,',','.')) }}</dd>

        <dt class="col-sm-4">Ingressos</dt>
        <dd class="col-sm-8">{{ $t->ticket_is_free ? 'Gratuito' : ('R$ '.number_format($t->ticket_price,2,',','.')) }}</dd>
      </dl>

      @if($t->description)
        <div class="mt-3">{!! nl2br(e($t->description)) !!}</div>
      @endif
    </div>

    <div class="col-md-5">
      <div class="card">
        <div class="card-header">Chaveamento</div>
        <div class="card-body">
          @if($t->bracket_path)
            @if(Str::endsWith($t->bracket_path, '.pdf'))
              <a class="btn btn-outline-primary" href="{{ asset('storage/'.$t->bracket_path) }}" target="_blank">Abrir PDF</a>
            @else
              <img src="{{ asset('storage/'.$t->bracket_path) }}" class="img-fluid" alt="Chaveamento">
            @endif
          @else
            <div class="text-muted">Nenhum arquivo enviado.</div>
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
