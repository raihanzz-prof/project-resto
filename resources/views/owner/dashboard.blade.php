@extends('layouts.app')

@section('title','Dashboard Owner')
@section('page_title','Dashboard Owner')
@section('page_subtitle')
  <p class="text-muted mb-0">Ringkasan performa transaksi & link ke laporan.</p>
@endsection
@section('back_url', url('/'))

@section('page_actions')
  <a href="{{ route('owner.laporan', ['start'=>now()->toDateString(), 'end'=>now()->toDateString()]) }}"
     class="btn btn-gradient btn-sm">Generate Laporan</a>
@endsection

@section('content')
<div class="row g-4 mt-1">
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card kpi-card text-bg-success border-0 h-100">
      <div class="card-body text-center position-relative">
        <div class="mb-2"><i class="bi bi-receipt fs-3"></i></div>
        <h4 class="mb-1">{{ $todayTxCount ?? 0 }}</h4>
        <p class="mb-0 small opacity-75">Transaksi Hari Ini</p>
        <a href="{{ route('owner.laporan', ['start'=>now()->toDateString(),'end'=>now()->toDateString()]) }}"
           class="stretched-link" aria-label="Laporan Hari Ini"></a>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card kpi-card text-bg-primary border-0 h-100">
      <div class="card-body text-center position-relative">
        <div class="mb-2"><i class="bi bi-cash-coin fs-3"></i></div>
        <h4 class="mb-1">Rp{{ number_format($todayRevenue ?? 0,0,',','.') }}</h4>
        <p class="mb-0 small opacity-75">Omzet Hari Ini</p>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card kpi-card text-bg-warning border-0 h-100">
      <div class="card-body text-center position-relative">
        <div class="mb-2"><i class="bi bi-hourglass-split fs-3"></i></div>
        <h4 class="mb-1">{{ $unpaidCount ?? 0 }}</h4>
        <p class="mb-0 small opacity-75">Pesanan Belum Dibayar</p>
      </div>
    </div>
  </div>
  <div class="col-12 col-sm-6 col-lg-3">
    <div class="card kpi-card text-bg-info border-0 h-100">
      <div class="card-body text-center position-relative">
        <div class="mb-2"><i class="bi bi-list-ul fs-3"></i></div>
        <h4 class="mb-1">{{ $menuCount ?? 0 }}</h4>
        <p class="mb-0 small opacity-75">Jumlah Menu</p>
      </div>
    </div>
  </div>
</div>

<hr class="my-4">
<h4 class="mb-3">Aksi</h4>
<div class="list-group shadow-sm rounded-3 overflow-hidden">
  <a href="{{ route('owner.laporan', ['start'=>now()->toDateString(), 'end'=>now()->toDateString()]) }}"
     class="list-group-item list-group-item-action d-flex align-items-center">
    <i class="bi bi-graph-up me-2"></i> 📈 Generate Laporan
  </a>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<style>
  .kpi-card{ border-radius:18px; box-shadow:0 16px 34px rgba(16,22,47,.12); transition:.15s; filter:saturate(.92) }
  .kpi-card:hover{ transform:translateY(-2px); box-shadow:0 22px 46px rgba(16,22,47,.18); filter:saturate(1) }
  .list-group-item{ padding:.85rem 1rem } .list-group-item:hover{ background:rgba(0,0,0,.03) }
</style>
@endpush
