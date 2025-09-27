@extends('layouts.app')

@section('content')
<div class="container">
  <h1 class="mb-3">Painel Administrativo</h1>
  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="row g-3">
    <div class="col-md-3">
      <div class="card text-center h-100">
        <div class="card-body">
          <div class="text-muted small">Usuários</div>
          <div class="display-6">{{ $usersCount }}</div>
          <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('admin.users') }}">Gerenciar</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center h-100">
        <div class="card-body">
          <div class="text-muted small">Quadras</div>
          <div class="display-6">{{ $courtsCount }}</div>
          <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('admin.courts') }}">Gerenciar</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center h-100">
        <div class="card-body">
          <div class="text-muted small">Torneios Locais</div>
          <div class="display-6">{{ $tournamentsCount }}</div>
          <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('admin.tournaments') }}">Gerenciar</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center h-100">
        <div class="card-body">
          <div class="text-muted small">Notícias</div>
          <div class="display-6">{{ $newsCount }}</div>
          <a class="btn btn-sm btn-outline-primary mt-2" href="{{ route('admin.news') }}">Gerenciar</a>
        </div>
      </div>
    </div>

    <div class="col-md-3">
      <div class="card text-center h-100 border-warning">
        <div class="card-body">
          <div class="text-muted small">Classificados pendentes</div>
          <div class="display-6">{{ $classifiedsPending }}</div>
          <a class="btn btn-sm btn-outline-warning mt-2" href="{{ route('admin.classifieds.pending') }}">Revisar</a>
        </div>
      </div>
    </div>
    <div class="col-md-3">
      <div class="card text-center h-100 border-success">
        <div class="card-body">
          <div class="text-muted small">Classificados aprovados</div>
          <div class="display-6">{{ $classifiedsApproved }}</div>
          <a class="btn btn-sm btn-outline-success mt-2" href="{{ route('admin.classifieds.approved') }}">Ver</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
