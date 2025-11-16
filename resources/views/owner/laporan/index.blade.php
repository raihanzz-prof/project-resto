@extends('layouts.app')
@section('title', 'Laporan Owner')
@section('page_title', 'Laporan Owner')
@section('back_url', route('owner.dashboard'))

@section('page_actions')
  <form method="GET" action="{{ route('owner.laporan') }}" class="d-inline-flex align-items-end gap-2">
    <div>
      <label class="form-label mb-1 small" for="startTop">Dari</label>
      <input id="startTop" type="date" name="start" value="{{ $start }}" class="form-control form-control-sm" required>
    </div>
    <div>
      <label class="form-label mb-1 small" for="endTop">Sampai</label>
      <input id="endTop" type="date" name="end" value="{{ $end }}" class="form-control form-control-sm" required>
    </div>
    <button class="btn btn-outline-secondary btn-sm">Terapkan</button>

    <a class="btn btn-gradient btn-sm" href="{{ route('owner.laporan.pdf', ['start' => $start, 'end' => $end]) }}"
      target="_blank" rel="noopener">
      Unduh PDF
    </a>
  </form>
@endsection

@section('content')
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
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card text-bg-primary border-0 h-100">
        <div class="card-body text-center">
          <div class="mb-2"><i class="bi bi-cash-coin fs-3"></i></div>
          <h4 class="mb-1">Rp{{ number_format($omzet, 0, ',', '.') }}</h4>
          <p class="mb-0 small opacity-75">Omzet</p>
        </div>
      </div>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Waktu</th>
          <th>Pelanggan</th>
          <th>Menu</th>
          <th>Qty</th>
          <th class="text-end">Total</th>
        </tr>
      </thead>

      <tbody>
        @forelse($tx as $i => $t)
          @php
            $o = $t->pesanan;

            // Total Qty dari seluruh detail
            $totalQty = $o->details->sum('jumlah');

            // Total harga tetap dari transaksi
            $totalHarga = $t->total;
          @endphp

          <tr>
            <td>{{ $tx->firstItem() + $i }}</td>
            <td>{{ $t->created_at->format('d/m/Y') }}</td>

            {{-- Nama pelanggan --}}
            <td>{{ optional($o->pelanggan)->namapelanggan ?? '—' }}</td>

            {{-- Multi-menu list --}}
            <td>
              <ul style="margin:0; padding-left:18px;">
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

            {{-- Total harga --}}
            <td class="text-end">Rp{{ number_format($totalHarga, 0, ',', '.') }}</td>
          </tr>

        @empty
          <tr>
            <td colspan="6" class="text-center">Tidak ada data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $tx->links() }}

@endsection