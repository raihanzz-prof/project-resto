@extends('layouts.app')
@section('title','Pembayaran')
@section('page_title','Pembayaran Pesanan')
@section('back_url', request('back', route('kasir.transaksi.index')))

@section('content')
@if(session('error')) <div class="alert alert-danger">{{ session('error') }}</div> @endif

<div class="row g-3">
  <div class="col-md-6">
    <div class="border rounded p-3">
      <h6 class="mb-3">Detail Pesanan</h6>
      <dl class="row">
        <dt class="col-4">Waktu</dt>
        <dd class="col-8">{{ $order->created_at->format('d/m/Y') }}</dd>

        <dt class="col-4">Pelanggan</dt>
        <dd class="col-8">{{ optional($order->pelanggan)->namapelanggan ?? '—' }}</dd>

        <dt class="col-4">Meja</dt>
        <dd class="col-8">{{ optional($order->meja)->nomormeja ?? '—' }}</dd>
      </dl>

      <div class="table-responsive mt-2">
        <table class="table table-sm align-middle">
          <thead class="table-light">
            <tr>
              <th>Menu</th>
              <th class="text-end">Harga</th>
              <th class="text-center">Qty</th>
              <th class="text-end">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            @forelse($order->details as $d)
              @php
                $harga = (int) optional($d->menu)->harga;
                $qty   = (int) ($d->quantity ?? $d->jumlah ?? 0);
                $sub   = $harga * $qty;
              @endphp
              <tr>
                <td>{{ optional($d->menu)->namamenu ?? '—' }}</td>
                <td class="text-end">Rp{{ number_format($harga,0,',','.') }}</td>
                <td class="text-center">{{ $qty }}</td>
                <td class="text-end">Rp{{ number_format($sub,0,',','.') }}</td>
              </tr>
            @empty
              <tr>
                <td colspan="4" class="text-center">Tidak ada item.</td>
              </tr>
            @endforelse
          </tbody>
          <tfoot>
            <tr>
              <th colspan="3" class="text-end">Total</th>
              <th class="text-end"><strong>Rp{{ number_format($total,0,',','.') }}</strong></th>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
  </div>

  <div class="col-md-6">
    <form action="{{ route('kasir.transaksi.pay.store', $order->idpesanan) }}" method="POST" class="border rounded p-3">
      @csrf
      <h6 class="mb-3">Pembayaran</h6>

      <div class="mb-3">
        <label class="form-label">Nominal Bayar</label>
        <input type="number" name="bayar" class="form-control"
               min="{{ $total }}" value="{{ old('bayar', $total) }}" required>
        @error('bayar') <small class="text-danger">{{ $message }}</small> @enderror
      </div>

      <div class="d-flex gap-2">
        <button class="btn btn-gradient">Proses Bayar</button>
        <a href="{{ request('back', route('kasir.transaksi.index')) }}" class="btn btn-secondary">Batal & Kembali</a>
      </div>
    </form>
  </div>
</div>
@endsection
