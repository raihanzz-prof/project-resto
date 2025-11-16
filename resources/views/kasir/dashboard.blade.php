{{-- resources/views/kasir/dashboard.blade.php --}}
@extends('layouts.app')

@section('title', 'Dashboard Kasir')
@section('page_title', 'Dashboard Kasir')
@section('page_subtitle')
  <p class="text-muted mb-0">Pantau pesanan belum dibayar & transaksi hari ini.</p>
@endsection
@section('back_url', url('/'))

@section('page_actions')
  <a href="{{ route('kasir.transaksi.index') }}" class="btn btn-gradient btn-sm">Kelola Transaksi</a>
  <a href="{{ route('kasir.laporan', ['start' => now()->toDateString(), 'end' => now()->toDateString()]) }}"
    class="btn btn-outline-secondary btn-sm">Generate Laporan</a>
@endsection

@section('content')
@if(session('success'))
  <div class="alert alert-success d-flex justify-content-between align-items-center">
    <span>{{ session('success') }}</span>
    <span class="d-flex gap-2">
      @if(session('receipt_print_url'))
        <a class="btn btn-sm btn-outline-primary"
           href="{{ session('receipt_print_url') }}" target="_blank" rel="noopener">
          Cetak Struk
        </a>
      @endif
      @if(session('receipt_url'))
        <a class="btn btn-sm btn-outline-dark"
           href="{{ session('receipt_url') }}" target="_blank" rel="noopener">
          Unduh PDF
        </a>
      @endif
    </span>
  </div>
@endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="row g-4 mt-1">
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card kpi-card text-bg-warning border-0 h-100">
        <div class="card-body text-center position-relative">
          <div class="mb-2"><i class="bi bi-hourglass-split fs-3"></i></div>
          <h4 class="mb-1">{{ $unpaidCount ?? 0 }}</h4>
          <p class="mb-0 small opacity-75">Belum Dibayar</p>
          <a href="{{ route('kasir.transaksi.index') }}" class="stretched-link" aria-label="Kelola Transaksi"></a>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card kpi-card text-bg-success border-0 h-100">
        <div class="card-body text-center position-relative">
          <div class="mb-2"><i class="bi bi-receipt fs-3"></i></div>
          <h4 class="mb-1">{{ $todayTxCount ?? 0 }}</h4>
          <p class="mb-0 small opacity-75">Transaksi Hari Ini</p>
        </div>
      </div>
    </div>
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="text-bg-primary">
        <div class="card-body text-center position-relative">
        </div>
      </div>
    </div>
  </div>

  <hr class="my-4">

  <h4 class="mb-3">Manajemen</h4>
  <div class="list-group shadow-sm rounded-3 overflow-hidden mb-4">
    <a href="{{ route('kasir.transaksi.index') }}"
      class="list-group-item list-group-item-action d-flex align-items-center">
      <i class="bi bi-credit-card me-2"></i> 💳 Kelola Transaksi
    </a>
    <a href="{{ route('kasir.laporan', ['start' => now()->toDateString(), 'end' => now()->toDateString()]) }}"
      class="list-group-item list-group-item-action d-flex align-items-center">
      <i class="bi bi-graph-up me-2"></i> 📈 Generate Laporan
    </a>
  </div>

  <h5 class="mb-3">Pesanan Belum Dibayar (Terbaru)</h5>
  <div class="table-responsive">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Waktu</th>
          <th>Pelanggan</th>
          <th>Meja</th>
          <th>Rincian Pesanan</th>
          <th>Total</th>
          <th width="120">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($unpaids as $i => $o)
          @php
            $rowNo = ($unpaids->firstItem() ?? 1) + $i;
            $totalBaris = $o->details->sum(function($d){
                $harga = (int) optional($d->menu)->harga;
                $qty   = (int) ($d->quantity ?? $d->jumlah ?? 0); // dukung nama kolom quantity/jumlah
                return $harga * $qty;
            });
          @endphp
          <tr>
            <td>{{ $rowNo }}</td>
            <td>{{ $o->created_at->format('d/m/Y') }}</td>
            <td>{{ optional($o->pelanggan)->namapelanggan ?? '—' }}</td>
            <td>{{ optional($o->meja)->nomormeja ?? '—' }}</td>
            <td>
              <ul class="mb-0">
                @foreach ($o->details as $d)
                  <li>
                    {{ optional($d->menu)->namamenu ?? '—' }}
                    (x{{ $d->quantity ?? $d->jumlah ?? 0 }})
                  </li>
                @endforeach
              </ul>
            </td>
            <td>Rp{{ number_format($totalBaris, 0, ',', '.') }}</td>
            <td>
              <a href="{{ route('kasir.transaksi.pay.form', ['idpesanan' => $o->idpesanan, 'back' => url()->current()]) }}"
                class="btn btn-sm btn-gradient">Bayar</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center">Tidak ada pesanan menunggu pembayaran</td>
          </tr>
        @endforelse
      </tbody>

    </table>
  </div>
  {{ $unpaids->links() }}
@endsection

@push('styles')
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    .kpi-card {
      border-radius: 18px;
      box-shadow: 0 16px 34px rgba(16, 22, 47, .12);
      transition: .15s;
      filter: saturate(.92);
    }

    .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 22px 46px rgba(16, 22, 47, .18);
      filter: saturate(1);
    }

    .list-group-item {
      padding: .85rem 1rem;
    }

    .list-group-item:hover {
      background: rgba(0, 0, 0, .03);
    }
  </style>
@endpush