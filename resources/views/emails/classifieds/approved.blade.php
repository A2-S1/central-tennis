<p>Olá {{ $listing->user->name }},</p>
<p>Seu anúncio <strong>{{ $listing->title }}</strong> foi aprovado e já está visível nos Classificados.</p>
<p><a href="{{ route('classifieds.show', $listing) }}">Ver anúncio</a></p>
<p>Obrigado por usar o CentralTennis!</p>
