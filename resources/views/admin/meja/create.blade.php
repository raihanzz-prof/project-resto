@extends('layouts.app')
@section('title','Tambah Meja')
@section('page_title','Tambah Meja')  {{-- atau Edit Meja --}}
@section('back_url', route('meja.index'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Tambah Meja</h3>
  <a href="{{ route('meja.index') }}" class="btn btn-outline-secondary">← Kembali</a>
</div>

<form action="{{ route('meja.store') }}" method="POST" class="row g-3">
  @csrf
  <div class="col-md-4">
    <label class="form-label">Nomor Meja</label>
    <input type="text" name="nomormeja" class="form-control" placeholder="M01" required value="{{ old('nomormeja') }}">
    @error('nomormeja') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select" required>
      <option value="kosong">Kosong</option>
      <option value="terisi">Terisi</option>
      <option value="booking">Booking</option>
    </select>
    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary">Simpan</button>
    <a href="{{ route('meja.index') }}" class="btn btn-secondary">Batal & Kembali</a>
  </div>
</form>
@endsection
