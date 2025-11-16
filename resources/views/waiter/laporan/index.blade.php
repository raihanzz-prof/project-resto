@extends('layouts.app')
@section('title', 'Laporan Order')
@section('page_title', 'Laporan Order')
@section('back_url', route('waiter.dashboard'))

@section('page_actions')

  {{-- Form filter tanggal (Dari–Sampai) di action bar --}}
  <form method="GET" action="{{ route('waiter.laporan') }}" class="d-inline-flex align-items-end gap-2 ms-2">
    <div>
      <label for="startTop" class="form-label mb-1 small">Dari</label>
      <input id="startTop" type="date" name="start" value="{{ old('start', $start ?? now()->toDateString()) }}"
        class="form-control form-control-sm" required>
    </div>
    <div>
      <label for="endTop" class="form-label mb-1 small">Sampai</label>
      <input id="endTop" type="date" name="end" value="{{ old('end', $end ?? now()->toDateString()) }}"
        class="form-control form-control-sm" required>
    </div>
    <button class="btn btn-outline-secondary btn-sm">Terapkan</button>

    {{-- Unduh PDF mengikuti rentang tanggal terpilih --}}
    <a class="btn btn-gradient btn-sm" href="{{ route('waiter.laporan.pdf', [
    'start' => old('start', $start ?? now()->toDateString()),
    'end' => old('end', $end ?? now()->toDateString())
  ]) }}">
      <i class="bi bi-filetype-pdf me-1"></i> Unduh PDF
    </a>
  </form>
@endsection


@section('content')
  <form method="GET" action="{{ route('waiter.laporan') }}" class="row g-3 mb-3">
  </form>

  <div class="row g-3">
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card text-bg-primary border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2"><i class="bi bi-receipt fs-3"></i></div>
          <h4 class="mb-1">{{ $totalOrder }}</h4>
          <p class="mb-0 small opacity-75">Total Order</p>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card text-bg-info border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2"><i class="bi bi-list-check fs-3"></i></div>
          <h4 class="mb-1">{{ $totalItem }}</h4>
          <p class="mb-0 small opacity-75">Total Item</p>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card text-bg-success border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2"><i class="bi bi-cash-coin fs-3"></i></div>
          <h4 class="mb-1">Rp{{ number_format($omzet, 0, ',', '.') }}</h4>
          <p class="mb-0 small opacity-75">Omzet (Harga×Qty)</p>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card text-bg-warning border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2"><i class="bi bi-wallet2 fs-3"></i></div>
          <h4 class="mb-1">Cihuy</h4>
          <p class="mb-0 small opacity-75">Hehe</p>
        </div>
      </div>
    </div>
  </div>

  <h5 class="mt-4">Daftar Order</h5>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th style="width:60px">#</th>
          <th>Waktu</th>
          <th>Pelanggan</th>
          <th>Menu</th>
          <th style="width:90px">Total Qty</th>
          <th style="width:140px" class="text-end">Subtotal</th>
        </tr>
      </thead>
      <tbody>

        @forelse($orders as $i => $o)

          @php
            // Hitung total qty (semua detail)
            $totalQty = $o->details->sum('jumlah');

            // Hitung subtotal semua menu dalam pesanan
            $subtotal = $o->details->sum(function ($d) {
              return $d->menu->harga * $d->jumlah;
            });
          @endphp

          <tr>
            <td>{{ $orders->firstItem() + $i }}</td>
            <td>{{ $o->created_at->format('d/m/Y') }}</td>

            {{-- Pelanggan --}}
            <td>{{ optional($o->pelanggan)->namapelanggan ?? '—' }}</td>

            {{-- Menu multi-item --}}
            <td>
              <ul class="mb-0 ps-3">
                @foreach($o->details as $d)
                  <li>
                    {{ $d->menu->namamenu }}
                    <span class="text-muted">x{{ $d->jumlah }}</span>
                  </li>
                @endforeach
              </ul>
            </td>

            {{-- Total Qty --}}
            <td>{{ $totalQty }}</td>

            {{-- Subtotal --}}
            <td class="text-end">
              Rp{{ number_format($subtotal, 0, ',', '.') }}
            </td>
          </tr>

        @empty
          <tr>
            <td colspan="6" class="text-center">Tidak ada data</td>
          </tr>
        @endforelse

      </tbody>
    </table>
  </div>

  <div class="col-12 d-flex gap-2">
    <a href="{{ request('back', route('waiter.dashboard')) }}" class="btn btn-secondary">Batal & Kembali</a>
  </div>


  {{ $orders->links() }}



@endsection