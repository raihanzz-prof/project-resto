@extends('layouts.app')
@section('title','Buat Pesanan')
@section('page_title','Buat Pesanan')
@section('back_url', request('back', route('order.index')))

@section('content')
<form action="{{ route('order.store') }}" method="POST" class="row g-3">
  @csrf

  {{-- Pelanggan existing (opsional) --}}
  <div class="col-md-6">
    <label class="form-label">Pelanggan (opsional)</label>
    <select name="idpelanggan" class="form-select">
      <option value="">— Pelanggan Baru —</option>
      @foreach($pelanggans as $plg)
        <option value="{{ $plg->idpelanggan }}" @selected(old('idpelanggan')==$plg->idpelanggan)>
          {{ $plg->namapelanggan }} ({{ $plg->nohp }})
        </option>
      @endforeach
    </select>
    <small class="text-muted">Kosongkan untuk pelanggan baru</small>
    @error('idpelanggan') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- Meja (opsional) --}}
  <div class="col-md-6">
    <label class="form-label">Meja</label>
    <select name="idmeja" class="form-select">
      <option value="">— Tanpa Meja —</option>
      @foreach($mejas as $m)
        <option value="{{ $m->idmeja }}" @selected(old('idmeja')==$m->idmeja)>
          {{ $m->nomormeja }} ({{ $m->status }})
        </option>
      @endforeach
    </select>
    @error('idmeja') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- Data pelanggan baru (aktif bila idpelanggan kosong) --}}
  <div class="col-md-4">
    <label class="form-label">Nama Pelanggan (baru)</label>
    <input type="text" name="namapelanggan" class="form-control" value="{{ old('namapelanggan') }}" placeholder="Jika pelanggan baru">
    @error('namapelanggan') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
  <div class="col-md-4">
    <label class="form-label">No HP</label>
    <input type="text" name="nohp" class="form-control" value="{{ old('nohp') }}">
    @error('nohp') <small class="text-danger">{{ $message }}</small> @enderror
  </div>
  <div class="col-md-4">
    <label class="form-label d-block">Jenis Kelamin</label>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="jeniskelamin" id="jk0" value="0" @checked(old('jeniskelamin')==='0')>
      <label class="form-check-label" for="jk0">Perempuan</label>
    </div>
    <div class="form-check form-check-inline">
      <input class="form-check-input" type="radio" name="jeniskelamin" id="jk1" value="1" @checked(old('jeniskelamin')==='1')>
      <label class="form-check-label" for="jk1">Laki-laki</label>
    </div>
    @error('jeniskelamin') <br><small class="text-danger">{{ $message }}</small> @enderror
  </div>
  <div class="col-12">
    <label class="form-label">Alamat</label>
    <input type="text" name="alamat" class="form-control" value="{{ old('alamat') }}">
    @error('alamat') <small class="text-danger">{{ $message }}</small> @enderror
  </div>

  {{-- ITEMS: menu[] + jumlah[] --}}
  <div class="col-12">
    <label class="form-label">Item Pesanan</label>
    <div class="table-responsive">
      <table class="table align-middle" id="items-table">
        <thead>
          <tr>
            <th style="width:55%">Menu</th>
            <th style="width:20%">Jumlah</th>
            <th style="width:25%"></th>
          </tr>
        </thead>
        <tbody>
          @php
            $oldMenus = old('menu', [null]);      // minimal 1 baris
            $oldJumlah = old('jumlah', [1]);
          @endphp

          @foreach($oldMenus as $i => $oldMenuId)
            <tr class="item-row">
              <td>
                <select name="menu[]" class="form-select" required>
                  <option value="" hidden>— Pilih Menu —</option>
                  @foreach($menus as $menu)
                    <option value="{{ $menu->idmenu }}" @selected($oldMenuId==$menu->idmenu)>
                      {{ $menu->namamenu }} — Rp{{ number_format($menu->harga,0,',','.') }}
                    </option>
                  @endforeach
                </select>
                @error("menu.$i") <small class="text-danger">{{ $message }}</small> @enderror
              </td>
              <td>
                <input type="number" name="jumlah[]" class="form-control" min="1" value="{{ $oldJumlah[$i] ?? 1 }}" required>
                @error("jumlah.$i") <small class="text-danger">{{ $message }}</small> @enderror
              </td>
              <td class="text-end">
                <button type="button" class="btn btn-outline-danger btn-sm btn-remove">Hapus</button>
              </td>
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

    <button type="button" class="btn btn-outline-primary" id="btn-add">+ Tambah Item</button>
    @error('menu') <div><small class="text-danger">{{ $message }}</small></div> @enderror
    @error('jumlah') <div><small class="text-danger">{{ $message }}</small></div> @enderror
  </div>

  <div class="col-12 d-flex gap-2">
    <button class="btn btn-gradient">Simpan Pesanan</button>
    <a href="{{ request('back', route('order.index')) }}" class="btn btn-secondary">Batal & Kembali</a>
  </div>
</form>

{{-- Template baris tersembunyi untuk cloning --}}
<template id="row-template">
  <tr class="item-row">
    <td>
      <select name="menu[]" class="form-select" required>
        <option value="" hidden>— Pilih Menu —</option>
        @foreach($menus as $menu)
          <option value="{{ $menu->idmenu }}">
            {{ $menu->namamenu }} — Rp{{ number_format($menu->harga,0,',','.') }}
          </option>
        @endforeach
      </select>
    </td>
    <td>
      <input type="number" name="jumlah[]" class="form-control" min="1" value="1" required>
    </td>
    <td class="text-end">
      <button type="button" class="btn btn-outline-danger btn-sm btn-remove">Hapus</button>
    </td>
  </tr>
</template>

{{-- JS vanilla untuk tambah/hapus baris --}}
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const tableBody = document.querySelector('#items-table tbody');
    const addBtn = document.getElementById('btn-add');
    const tpl = document.getElementById('row-template');

    addBtn.addEventListener('click', () => {
      tableBody.appendChild(tpl.content.firstElementChild.cloneNode(true));
    });

    tableBody.addEventListener('click', (e) => {
      if (e.target.classList.contains('btn-remove')) {
        const rows = tableBody.querySelectorAll('.item-row');
        if (rows.length > 1) e.target.closest('tr').remove(); // minimal 1 baris
      }
    });
  });
</script>
@endsection