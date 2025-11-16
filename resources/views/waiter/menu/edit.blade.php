@extends('layouts.app')
@section('title', 'Edit Menu')
@section('page_title', 'Edit Menu')

@section('content')
<div class="card">
  <div class="card-body">

    <form method="POST" action="{{ route('waiter.menu.update', $menu->idmenu) }}">
      @csrf
      @method('PUT')

      <div class="mb-3">
        <label class="form-label">Nama Menu</label>
        <input type="text" name="namamenu" class="form-control"
               value="{{ old('namamenu', $menu->namamenu) }}" required>
      </div>

      <div class="mb-3">
        <label class="form-label">Harga</label>
        <input type="number" name="harga" class="form-control"
               value="{{ old('harga', $menu->harga) }}" required>
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-gradient">Update</button>
        <a href="{{ $back ?? route('waiter.menu.index') }}" class="btn btn-secondary">Batal</a>
      </div>
    </form>

  </div>
</div>
@endsection
