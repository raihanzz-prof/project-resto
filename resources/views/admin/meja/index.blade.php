@extends('layouts.app')
@section('title','Kelola Meja')
@section('page_title','Kelola Meja')
@section('back_url', route('admin.dashboard'))
@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Kelola Meja</h3>
  <div class="d-flex gap-2">
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
      ← Kembali
    </a>
    <a href="{{ route('meja.create') }}" class="btn btn-primary">
      + Tambah Meja
    </a>
  </div>
</div>

@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<table class="table table-bordered">
  <thead>
    <tr>
      <th>#</th>
      <th>Nomor Meja</th>
      <th>Status</th>
      <th width="170">Aksi</th>
    </tr>
  </thead>
  <tbody>
    @forelse($mejas as $i => $m)
      <tr>
        <td>{{ $mejas->firstItem() + $i }}</td>
        <td>{{ $m->nomormeja }}</td>
        <td>{{ ucfirst($m->status) }}</td>
        <td>
          <a href="{{ route('meja.edit', $m->idmeja) }}" class="btn btn-sm btn-warning">Edit</a>
          <form action="{{ route('meja.destroy', $m->idmeja) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus meja ini?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Hapus</button>
          </form>
        </td>
      </tr>
    @empty
      <tr><td colspan="5" class="text-center">Belum ada data meja</td></tr>
    @endforelse
  </tbody>
</table>

{{ $mejas->links() }}
@endsection
