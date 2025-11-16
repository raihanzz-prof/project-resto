@extends('layouts.app')
@section('title','Edit Meja')
@section('page_title','Tambah Meja')  {{-- atau Edit Meja --}}
@section('back_url', route('meja.index'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Edit Meja</h3>
  <a href="{{ route('meja.index') }}" class="btn btn-outline-secondary">← Kembali</a>
</div>

<form action="{{ route('meja.update', $meja->idmeja) }}" method="POST" class="row g-3">
  @csrf @method('PUT')

  <div class="col-md-4">
    <label class="form-label">Nomor Meja</label>
    <input type="text" name="nomormeja" class="form-control" required value="{{ old('nomormeja', $meja->nomormeja) }}">
    @error('nomormeja') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Status</label>
    <select name="status" class="form-select" required>
      @foreach(['kosong','terisi','booking'] as $s)
        <option value="{{ $s }}" @selected(old('status',$meja->status)===$s)>{{ ucfirst($s) }}</option>
      @endforeach
    </select>
    @error('status') <small class="text-danger">{{ $message }}</small> @enderror
  </div>


  <div class="col-12 d-flex gap-2">
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('meja.index') }}" class="btn btn-secondary">Batal & Kembali</a>
  </div>
</form>
@endsection
