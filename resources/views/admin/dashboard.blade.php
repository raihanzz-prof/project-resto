@extends('layouts.app')

@section('title', 'Dashboard Admin')
@section('page_title', 'Dashboard Admin')
@section('page_subtitle')   

    <p class="text-muted mb-0">Ringkasan singkat operasional hari ini.</p>
@endsection

@section('content')
<div class="row g-4 mt-1">
    <!-- KPI: Menu -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card kpi-card text-bg-success border-0 h-100">
            <div class="card-body text-center position-relative">
                <div class="mb-2"><i class="bi bi-egg-fried fs-3"></i></div>
                <h4 class="mb-1">{{ $totalMenu ?? 0 }}</h4>
                <p class="mb-0 small opacity-75">Menu Tersedia</p>
                @if(Route::has('menu.index'))
                    <a href="{{ route('menu.index') }}" class="stretched-link" aria-label="Kelola Menu"></a>
                @endif
            </div>
        </div>
    </div>

    <!-- KPI: Pelanggan -->
    <div class="col-12 col-sm-6 col-lg-3">
        <div class="card kpi-card text-bg-warning border-0 h-100">
            <div class="card-body text-center position-relative">
                <div class="mb-2"><i class="bi bi-person-lines-fill fs-3"></i></div>
                <h4 class="mb-1">{{ $totalPelanggan ?? 0 }}</h4>
                <p class="mb-0 small opacity-75">Pelanggan Terdaftar</p>
                @if(Route::has('customers.index'))
                    <a href="{{ route('customers.index') }}" class="stretched-link" aria-label="Kelola Pelanggan"></a>
                @endif
            </div>
        </div>
    </div>
    {{-- KPI: Meja --}}
<div class="col-12 col-sm-6 col-lg-3">
    <div class="card kpi-card text-bg-info border-0 h-100">
        <div class="card-body text-center position-relative">
            <div class="mb-2"><i class="bi bi-table fs-3"></i></div>
            <h4 class="mb-1">{{ $totalMeja ?? 0 }}</h4>
            <p class="mb-0 small opacity-75">Meja Tersedia</p>
            @if(Route::has('meja.index'))
                <a href="{{ route('meja.index') }}" class="stretched-link" aria-label="Kelola Meja"></a>
            @endif
        </div>
    </div>
</div>

<hr class="my-4">

<h4 class="mb-3">Manajemen Data</h4>
<div class="list-group shadow-sm rounded-3 overflow-hidden">
    <a href="{{ Route::has('menu.index') ? route('menu.index') : '#' }}" class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="bi bi-egg-fried me-2"></i> 🍔 Kelola Menu
    </a>
        <a href="{{ Route::has('meja.index') ? route('meja.index') : '#' }}"
       class="list-group-item list-group-item-action d-flex align-items-center">
        <i class="bi bi-table me-2"></i> 🪑 Kelola Meja
    </a>
</div>
@endsection

@push('styles')
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .kpi-card{
            border-radius: 18px;
            box-shadow: 0 16px 34px rgba(16,22,47,.12);
            transition: transform .15s ease, box-shadow .2s ease, filter .2s ease;
            filter: saturate(.92);
        }
        .kpi-card:hover{
            transform: translateY(-2px);
            box-shadow: 0 22px 46px rgba(16,22,47,.18);
            filter: saturate(1);
        }
        .list-group-item{
            padding:.85rem 1rem;
        }
        .list-group-item:hover{
            background: rgba(0,0,0,.03);
        }
    </style>
@endpush
