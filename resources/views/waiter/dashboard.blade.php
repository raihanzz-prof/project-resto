@extends('layouts.app')
@section('title', 'Dashboard Waiter')
@section('page_title', 'Dashboard Waiter')
@section('page_subtitle')
  <p class="text-muted mb-0">Ringkasan singkat pesanan Anda hari ini.</p>
@endsection

@section('page_actions')
  <a href="{{ route('order.create', ['back' => url()->current()]) }}" class="btn btn-gradient btn-sm">+ Buat Pesanan</a>
@endsection

@section('content')
  <div class="row g-4 mt-1">
    <!-- KPI: Pesanan Hari Ini -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card kpi-card text-bg-success border-0 h-100">
        <div class="card-body text-center position-relative">
          <div class="mb-2"><i class="bi bi-receipt fs-3"></i></div>
          <h4 class="mb-1">{{ $totalPesananHariIni ?? 0 }}</h4>
          <p class="mb-0 small opacity-75">Pesanan Hari Ini</p>
          <a href="{{ route('order.index') }}" class="stretched-link" aria-label="Kelola Order"></a>
        </div>
      </div>
    </div>

    <!-- KPI: Belum Dibayar -->
    <div class="col-12 col-sm-6 col-lg-3">
      <div class="card kpi-card text-bg-warning border-0 h-100">
        <div class="card-body text-center position-relative">
          <div class="mb-2"><i class="bi bi-wallet2 fs-3"></i></div>
          <h4 class="mb-1">{{ $belumDibayar ?? 0 }}</h4>
          <p class="mb-0 small opacity-75">Belum Dibayar</p>
          <a href="{{ route('order.index') }}" class="stretched-link" aria-label="Kelola Order"></a>
        </div>
      </div>
    </div>
  </div>

  <hr class="my-4">

  <h4 class="mb-3">Manajemen</h4>
  <div class="list-group shadow-sm rounded-3 overflow-hidden">
    <a href="{{ route('order.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
      <i class="bi bi-basket2 me-2"></i> 🍽️ Kelola Order
    </a>
    <a href="{{ route('waiter.menu.index') }}" class="list-group-item list-group-item-action d-flex align-items-center">
      <i class="bi bi-egg-fried me-2"></i> 🍔 Kelola Menu
    </a>
    <a href="{{ route('waiter.laporan', ['start' => now()->toDateString(), 'end' => now()->toDateString()]) }}"
      class="list-group-item list-group-item-action d-flex align-items-center">
      <i class="bi bi-graph-up me-2"></i> 📈 Generate Laporan
    </a>
  </div>

  {{-- Daftar pesanan terbaru --}}
  <div class="table-responsive mt-4">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th style="width:70px">#</th>
          <th>Waktu</th>
          <th>Pelanggan</th>
          <th>Meja</th>
          <th>Menu</th>
          <th>Total Qty</th>
          <th>Status</th>
        </tr>
      </thead>
      <tbody>

        @forelse($pesanans as $i => $p)

          @php
            // Total jumlah item dalam pesanan
            $totalQty = $p->details->sum('jumlah');
          @endphp

          <tr>
            <td>{{ $pesanans->firstItem() + $i }}</td>
            <td>{{ $p->created_at->format('d/m/Y') }}</td>

            {{-- Pelanggan --}}
            <td>{{ optional($p->pelanggan)->namapelanggan ?? '-' }}</td>

            {{-- Meja --}}
            <td>{{ optional($p->meja)->nomormeja ?? '-' }}</td>

            {{-- Menu Multi-item --}}
            <td>
              <ul class="mb-0 ps-3">
                @foreach($p->details as $d)
                  <li>
                    {{ $d->menu->namamenu }}
                    <span class="text-muted">x{{ $d->jumlah }}</span>
                  </li>
                @endforeach
              </ul>
            </td>

            {{-- Total Qty --}}
            <td>{{ $totalQty }}</td>

            {{-- Status Pembayaran --}}
            <td>
              @if($p->transaksi)
                <span class="badge text-bg-success">Sudah Dibayar</span>
              @else
                <span class="badge text-bg-secondary">Menunggu Bayar</span>
              @endif
            </td>
          </tr>

        @empty
          <tr>
            <td colspan="7" class="text-center text-muted">Belum ada pesanan</td>
          </tr>
        @endforelse

      </tbody>
    </table>
  </div>

  {{ $pesanans->links() }}
@endsection

@push('styles')
  <style>
    .kpi-card {
      border-radius: 18px;
      box-shadow: 0 16px 34px rgba(16, 22, 47, .12);
      transition: .15s;
    }

    .kpi-card:hover {
      transform: translateY(-2px);
      box-shadow: 0 22px 46px rgba(16, 22, 47, .18);
    }
  </style>
@endpush