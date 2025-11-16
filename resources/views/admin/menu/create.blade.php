@extends('layouts.app')
@section('title','Tambah Menu')
@section('page_title','Tambah Menu')
@section('back_url', request('back', route('menu.index')))

@section('content')
<form action="{{ route('menu.store') }}" method="POST" class="row g-3">
  @csrf

  <div class="col-md-8">
    <label class="form-label">Nama Menu</label>
    <input type="text" name="namamenu" class="form-control" value="{{ old('namamenu') }}" required>
    @error('namamenu') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Harga (Rp)</label>
    <input type="number" name="harga" class="form-control" min="0" value="{{ old('harga') }}" required>
    @error('harga') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-gradient">Simpan</button>
    <a href="{{ request('back', route('menu.index')) }}" class="btn btn-secondary">Batal & Kembali</a>
  </div>
</form>
@endsection
