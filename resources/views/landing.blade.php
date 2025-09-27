@extends('layouts.app')

@section('content')
<style>
  .hero { background: linear-gradient(180deg, #eef6ff 0%, #e6f1ff 100%); }
  .metrics { background:#0d2b59; color:#fff; }
  .hero-center { display:flex; align-items:center; justify-content:center; }
  .hero-card { max-width: 520px; width:100%; }
</style>

<div class="hero py-5">
  <div class="container hero-center">
        <div class="card shadow-sm hero-card">
          <div class="card-body p-4">
            <div class="text-center mb-3">
              @if (file_exists(public_path('images/centraltennis-logo.png')))
                <img src="{{ asset('images/centraltennis-logo.png') }}?v={{ filemtime(public_path('images/centraltennis-logo.png')) }}" alt="CentralTennis" style="height:92px; width:auto; object-fit:contain;">
              @else
                <h2 class="mb-0">CentralTennis</h2>
              @endif
            </div>
            <form method="POST" action="{{ route('login') }}" class="mb-3">
              @csrf
              <div class="mb-3">
                <label class="form-label">E-mail</label>
                <input type="email" name="email" class="form-control" required placeholder="Informe seu e-mail">
              </div>
              <div class="mb-3">
                <label class="form-label">Senha</label>
                <input type="password" name="password" class="form-control" required placeholder="Informe sua senha">
              </div>
              <button class="btn btn-primary w-100">Entrar na Plataforma</button>
            </form>
            <div class="d-flex justify-content-between small">
              <a href="{{ route('password.request') }}">Esqueceu a senha?</a>
              <a href="{{ route('register') }}">Cadastre-se gratuitamente</a>
            </div>
            <hr>
            <div class="text-center">
              <div class="mb-2 text-muted">Baixe o app</div>
              <div class="d-flex justify-content-center gap-2">
                <a href="#" aria-label="App Store"><img src="https://developer.apple.com/assets/elements/badges/download-on-the-app-store.svg" alt="App Store" style="height:40px"></a>
                <a href="#" aria-label="Google Play"><img src="https://upload.wikimedia.org/wikipedia/commons/7/78/Google_Play_Store_badge_EN.svg" alt="Google Play" style="height:40px"></a>
              </div>
            </div>
          </div>
        </div>
  </div>
</div>

<div class="metrics py-4">
    <div class="row text-center g-3">
      <div class="col-6 col-md"><div class="h4 mb-0">XX</div><div class="small">Locais e Gestores</div></div>
      <div class="col-6 col-md"><div class="h4 mb-0">XX</div><div class="small">Jogadores</div></div>
      <div class="col-6 col-md"><div class="h4 mb-0">XX</div><div class="small">Partidas</div></div>
      <div class="col-6 col-md"><div class="h4 mb-0">XX</div><div class="small">Competições</div></div>
      <div class="col-6 col-md"><div class="h4 mb-0">XX</div><div class="small">Aulas e Locações</div></div>
    </div>
  </div>
</div>

<div class="py-5">
  <div class="container">
    <div class="row g-4">
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Encontre Torneios</h5>
            <p class="card-text">Descubra eventos de Tênis, Beach Tennis e Padel perto de você e inscreva-se em poucos cliques.</p>
            <a href="{{ route('tournaments.index') }}" class="btn btn-outline-primary btn-sm">Ver torneios</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Jogue nas melhores quadras</h5>
            <p class="card-text">Explore quadras, fotos e avaliações. Faça contato e organize seus jogos.</p>
            <a href="{{ route('courts.index') }}" class="btn btn-outline-primary btn-sm">Onde jogar</a>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card h-100 shadow-sm">
          <div class="card-body">
            <h5 class="card-title">Loja & Classificados</h5>
            <p class="card-text">Compre produtos novos na Loja e negocie itens usados nos Classificados.</p>
            <a href="{{ route('store.index') }}" class="btn btn-outline-primary btn-sm me-2">Loja</a>
            <a href="{{ route('classifieds.index') }}" class="btn btn-outline-secondary btn-sm">Classificados</a>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<footer class="py-4 border-top mt-4 bg-white">
  <div class="container">
    <div class="row g-3 align-items-center">
      <div class="col-md-6 text-muted small">
        © {{ date('Y') }} CentralTennis. Todos os direitos reservados.
      </div>
      <div class="col-md-6">
        <div class="d-flex justify-content-md-end justify-content-start gap-3 small">
          <a href="#">XX Termos de Uso</a>
          <a href="#">XX Política de Privacidade</a>
          <a href="#">XX Contato</a>
        </div>
      </div>
    </div>
  </div>
  </footer>
@endsection
