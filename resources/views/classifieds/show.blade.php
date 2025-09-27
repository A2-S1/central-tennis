@extends('layouts.app')

@section('content')
<div class="container">
  <div class="row g-4">
    <div class="col-md-6">
      @php $primary = $listing->images->firstWhere('is_primary', true) ?? $listing->images->first(); @endphp
      <div class="card">
        @if($primary)
          <img id="mainImg" src="{{ asset('storage/'.$primary->path) }}" class="card-img-top" alt="{{ $listing->title }}" style="max-height:360px;object-fit:contain;background:#fff;cursor:zoom-in" onclick="showImageModal('{{ asset('storage/'.$primary->path) }}')">
        @endif
        <div class="card-body">
          <div class="d-flex flex-wrap gap-2">
            @foreach($listing->images as $img)
              <img src="{{ asset('storage/'.$img->path) }}" style="width:72px;height:72px;object-fit:cover;cursor:pointer;border:1px solid #ddd" onclick="document.getElementById('mainImg').src=this.src">
            @endforeach
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6">
      <h1 class="h3">{{ $listing->title }}</h1>
      @if($listing->price)
        <div class="h4">R$ {{ number_format($listing->price,2,',','.') }}</div>
      @endif
      <div class="text-muted small mb-2">Condição: {{ ucfirst($listing->condition ?? '—') }}</div>
      <div>{!! nl2br(e($listing->description)) !!}</div>
      <div class="mt-3 text-muted small">Anunciante: {{ $listing->user->name }}</div>
      <div class="mt-2">Status: <span class="badge {{ $listing->status==='approved'?'text-bg-success':($listing->status==='pending'?'text-bg-warning':'text-bg-secondary') }}">{{ ucfirst($listing->status) }}</span></div>
      @if($listing->status==='approved')
        <div class="mt-3">
          @if($listing->phone)
            <div><i class="bi bi-telephone me-1"></i><strong>Telefone:</strong> {{ $listing->phone }}</div>
          @endif
          @if($listing->whatsapp)
            @php($wa = preg_replace('/\D+/', '', $listing->whatsapp))
            <div>
              <i class="bi bi-whatsapp me-1"></i>
              <strong>WhatsApp:</strong> {{ $listing->whatsapp }}
              <a class="btn btn-sm btn-success ms-2" target="_blank" rel="noopener" href="https://api.whatsapp.com/send?phone={{ $wa }}">Conversar</a>
            </div>
          @endif
        </div>
      @endif
      @auth
        @if(auth()->id()===$listing->user_id)
          <div class="mt-3">
            <a href="{{ route('classifieds.edit', $listing) }}" class="btn btn-sm btn-outline-primary">Editar anúncio</a>
          </div>
        @endif
      @endauth
      @auth
        @if($listing->status==='pending' && auth()->id()===$listing->user_id)
          <div class="mt-2 alert alert-warning py-2">Seu anúncio está aguardando aprovação do administrador.</div>
        @endif
      @endauth
    </div>
  </div>

  <!-- Modal para ampliar imagem -->
  <div class="modal fade" id="imgModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
      <div class="modal-content">
        <div class="modal-body p-0">
          <img id="imgModalTarget" src="" alt="Imagem" style="width:100%;height:auto;display:block">
        </div>
      </div>
    </div>
  </div>
  <script>
    function showImageModal(src){
      const el = document.getElementById('imgModalTarget');
      if(el){ el.src = src; }
      const modal = new bootstrap.Modal(document.getElementById('imgModal'));
      modal.show();
    }
  </script>
</div>
@endsection
