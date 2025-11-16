@extends('layouts.app')
@section('title','Kelola Menu')
@section('page_title','Kelola Menu')
@section('back_url', route('admin.dashboard'))

@section('page_actions')
  <a href="{{ route('menu.create', ['back' => url()->current()]) }}" class="btn btn-gradient btn-sm">
    + Tambah Menu
  </a>
    <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">← Kembali</a>
@endsection

@section('content')
@if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="table-responsive">
  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:70px">#</th>
        <th>Nama Menu</th>
        <th style="width:160px">Harga</th>
        <th style="width:180px">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($menus as $i => $m)
        <tr>
          <td>{{ $menus->firstItem() + $i }}</td>
          <td>{{ $m->namamenu }}</td>
          <td>Rp{{ number_format($m->harga,0,',','.') }}</td>
          <td>
            <a href="{{ route('menu.edit', ['idmenu'=>$m->idmenu, 'back'=>url()->current()]) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('menu.destroy', $m->idmenu) }}" method="POST" class="d-inline" onsubmit="return confirm('Hapus menu ini?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Hapus</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center">Belum ada data menu</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $menus->links() }}
@endsection