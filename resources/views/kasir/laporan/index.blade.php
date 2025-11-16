@extends('layouts.app')
@section('title', 'Laporan Kasir')
@section('page_title', 'Laporan Kasir')
@section('back_url', route('kasir.dashboard'))

@section('page_actions')
  <a class="btn btn-gradient btn-sm" href="{{ route('kasir.laporan.pdf', ['start' => $start, 'end' => $end]) }}"
    target="_blank" rel="noopener">
    Unduh PDF
  </a>
@endsection

@section('content')
  <form method="GET" action="{{ route('kasir.laporan') }}" class="row g-3 mb-3">
    <div class="col-md-4">
      <label class="form-label">Dari</label>
      <input type="date" name="start" value="{{ $start }}" class="form-control" required>
    </div>
    <div class="col-md-4">
      <label class="form-label">Sampai</label>
      <input type="date" name="end" value="{{ $end }}" class="form-control" required>
    </div>
    <div class="col-md-4 d-flex align-items-end">
      <button class="btn btn-outline-secondary w-100">Terapkan</button>
    </div>
  </form>

  <div class="row g-3 mb-3">
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card text-bg-success border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2"><i class="bi bi-receipt fs-3"></i></div>
          <h4 class="mb-1">{{ $totalTransaksi }}</h4>
          <p class="mb-0 small opacity-75">Total Transaksi</p>
        </div>
      </div>
    </div>


    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th style="width:60px">#</th>
            <th>Waktu</th>
            <th>Pelanggan</th>
            <th>Menu</th>
            <th style="width:90px">Total Qty</th>
            <th class="text-end" style="width:140px">Total</th>
          </tr>
        </thead>

        <tbody>
          @forelse($tx as $i => $t)
            @php
              $o = $t->pesanan;

              // Total qty
              $totalQty = $o->details->sum('jumlah');

              // Total subtotal dihitung di tabel transaksi (t->total sudah benar)
              $grandTotal = $t->total;
            @endphp

            <tr>
              {{-- Nomor urut --}}
              <td>{{ $tx->firstItem() + $i }}</td>

              {{-- Waktu transaksi --}}
              <td>{{ $t->created_at->format('d/m/Y') }}</td>

              {{-- Pelanggan --}}
              <td>{{ optional($o->pelanggan)->namapelanggan ?? '—' }}</td>

              {{-- Daftar Menu --}}
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

              {{-- Total Harga --}}
              <td class="text-end">Rp{{ number_format($grandTotal, 0, ',', '.') }}</td>
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
      <a href="{{ request('back', route('kasir.dashboard')) }}" class="btn btn-secondary">Batal & Kembali</a>
    </div>

    {{ $tx->links() }}
@endsection