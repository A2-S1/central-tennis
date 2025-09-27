@extends('layouts.app')

@section('content')
<div class="container">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Usuários</h1>
    <div class="d-flex gap-2">
      <form method="GET" action="{{ route('admin.users') }}" class="d-flex">
        <input type="text" class="form-control form-control-sm me-2" name="q" value="{{ $q ?? '' }}" placeholder="Buscar nome ou email">
        <button class="btn btn-sm btn-outline-primary">Buscar</button>
      </form>
      <a href="{{ route('admin.dashboard') }}" class="btn btn-sm btn-outline-secondary">Voltar</a>
    </div>
  </div>

  @if(session('status'))<div class="alert alert-success">{{ session('status') }}</div>@endif

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th>
          <th>Nome</th>
          <th>Email</th>
          <th>Cidade/UF</th>
          <th>Admin</th>
          <th>Registrado em</th>
          <th></th>
        </tr>
      </thead>
      <tbody>
        @foreach($users as $u)
        <tr>
          <td>{{ $u->id }}</td>
          <td>{{ $u->name }}</td>
          <td>{{ $u->email }}</td>
          <td>{{ $u->city }} {{ $u->state ? ', '.$u->state : '' }}</td>
          <td>
            @if(auth()->id() !== $u->id)
              <form method="POST" action="{{ route('admin.users.set_admin', ['user' => $u->id]) }}" class="d-inline">
                @csrf @method('PUT')
                <input type="hidden" name="is_admin" value="{{ $u->is_admin ? 0 : 1 }}">
                @if($u->is_admin)
                  <button type="submit" class="btn btn-sm btn-outline-warning" onclick="return confirm('Remover privilégios de administrador deste usuário?')">Rebaixar</button>
                @else
                  <button type="submit" class="btn btn-sm btn-outline-success" onclick="return confirm('Promover este usuário a administrador?')">Promover</button>
                @endif
              </form>
            @else
              <span class="badge text-bg-primary">Você</span>
            @endif
          </td>
          <td>{{ $u->created_at->format('d/m/Y') }}</td>
          <td class="text-end">
            @if(auth()->id() !== $u->id)
            <form method="POST" action="{{ route('admin.users.delete', ['user' => $u->id]) }}" onsubmit="return confirm('Excluir usuário? Esta ação é irreversível.')" class="d-inline">
              @csrf
              <button type="submit" class="btn btn-sm btn-outline-danger">Excluir</button>
            </form>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
    </table>
  </div>

  <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection
