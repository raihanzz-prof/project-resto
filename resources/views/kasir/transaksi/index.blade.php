@extends('layouts.app')
@section('title', 'Kelola Transaksi')
@section('page_title', 'Kelola Transaksi')
@section('back_url', route('kasir.dashboard'))

@section('content')
  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div> @endif
  @if(session('error'))
  <div class="alert alert-danger">{{ session('error') }}</div> @endif

  <h5 class="mb-2">Pesanan Belum Dibayar</h5>
  <div class="table-responsive mb-4">
    <table class="table table-bordered align-middle">
      <thead class="table-light">
        <tr>
          <th>#</th>
          <th>Waktu</th>
          <th>Pelanggan</th>
          <th>Meja</th>
          <th>Menu</th>
          <th>Qty</th>
          <th>Total</th>
          <th width="120">Aksi</th>
        </tr>
      </thead>
      <tbody>
        @forelse($unpaids as $i => $o)
              @php
                $menusText = $o->details->map(function ($d) {
                  return $d->menu->namamenu . ' x' . $d->jumlah;
                })->join(', ');

                $total = $o->details->sum(function ($d) {
                  return $d->menu->harga * $d->jumlah;
                });
              @endphp

              <tr>
                <td>{{ $unpaids->firstItem() + $i }}</td>
                <td>{{ $o->created_at->format('d/m/Y') }}</td>
                <td>{{ optional($o->pelanggan)->namapelanggan ?? '—' }}</td>
                <td>{{ optional($o->meja)->nomormeja ?? '—' }}</td>

                <!-- MENU MULTI ITEM -->
                <td>{{ $menusText }}</td>

                <!-- TOTAL QTY SEMUA ITEM -->
                <td>{{ $o->details->sum('jumlah') }}</td>

                <!-- TOTAL HARGA SEMUA ITEM -->
                <td>Rp{{ number_format($total, 0, ',', '.') }}</td>

                <td>
                  <a class="btn btn-sm btn-gradient" href="{{ route(
            'kasir.transaksi.pay.form',
            ['idpesanan' => $o->idpesanan, 'back' => url()->current()]
          ) }}">
                    Bayar
                  </a>
                </td>
              </tr>

        @empty
          <tr>
            <td colspan="8" class="text-center">Tidak ada data</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
  {{ $unpaids->links() }}

  @if(isset($paidLatest) && $paidLatest->count())
    <h5 class="mt-4 mb-2">Transaksi Terbaru</h5>
    <div class="table-responsive">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>#</th>
            <th>Waktu</th>
            <th>Pelanggan</th>
            <th>Menu</th>
            <th>Qty</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>

          @foreach($paidLatest as $i => $t)
            @php
              $o = $t->pesanan;

              // Buat daftar menu dalam bentuk: Nasi Goreng x1, Ayam Bakar x2, ...
              $menusText = $o->details->map(function ($d) {
                return $d->menu->namamenu . ' x' . $d->jumlah;
              })->join(', ');

              // Total qty semua item
              $qtyTotal = $o->details->sum('jumlah');

              // Total harga
              $total = $o->details->sum(function ($d) {
                return $d->menu->harga * $d->jumlah;
              });
            @endphp

            <tr>
              <td>{{ $i + 1 }}</td>
              <td>{{ $t->created_at->format('d/m/Y') }}</td>
              <td>{{ optional($o->pelanggan)->namapelanggan ?? '—' }}</td>

              <!-- Tampilkan semua menu -->
              <td>{{ $menusText }}</td>

              <!-- Total qty -->
              <td>{{ $qtyTotal }}</td>

              <!-- Total harga per pesanan -->
              <td>Rp{{ number_format($total, 0, ',', '.') }}</td>
            </tr>
          @endforeach

        </tbody>

      </table>
    </div>
    <div class="col-12 d-flex gap-2">
      <a href="{{ request('back', route('kasir.dashboard')) }}" class="btn btn-secondary">Batal & Kembali</a>
    </div>
  @endif
@endsection