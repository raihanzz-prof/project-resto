@extends('layouts.app')
@section('title','Kelola Order')
@section('page_title','Kelola Order')
@section('back_url', route('waiter.dashboard'))

@section('page_actions')
             <a class="btn btn-outline-secondary btn-sm"
     href="{{ route('waiter.dashboard')}}">← Kembali</a>
  <a href="{{ route('order.create', ['back'=>url()->current()]) }}" class="btn btn-gradient btn-sm">+ Buat Pesanan</a>
@endsection


@section('content')
@if(session('success')) <div class="alert alert-success">{{ session('success') }}</div> @endif
@if(session('error'))   <div class="alert alert-danger">{{ session('error') }}</div> @endif

<div class="table-responsive">
  <table class="table table-bordered align-middle">
    <thead class="table-light">
      <tr>
        <th style="width:70px">#</th>
        <th>Waktu</th>
        <th>Pelanggan</th>
        <th>Meja</th>
        <th>Rincian Pesanan</th>
        <th>Status</th>
        <th style="width:160px">Aksi</th>
      </tr>
    </thead>
    <tbody>
      @forelse($pesanans as $i => $p)
        <tr>
          <td>{{ $pesanans->firstItem() + $i }}</td>
          <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
          <td>{{ $p->pelanggan->namapelanggan ?? '-' }}</td>
          <td>{{ $p->meja->nomormeja ?? '-' }}</td>
          <td><ul>
            @foreach($p->details as $detail)
              <li>{{ $detail->menu->namamenu .'(x' . $detail->menu->jumlah . ')' ?? '-' }}</li>
            @endforeach
          </ul></td>
          <td>
            @if($p->transaksi)
              <span class="badge text-bg-success">Sudah Dibayar</span>
            @else
              <span class="badge text-bg-secondary">Menunggu Bayar</span>
            @endif
          </td>
          <td>
            @if(!$p->transaksi)
              <form action="{{ route('order.cancel', $p->idpesanan) }}" method="POST" class="d-inline" onsubmit="return confirm('Batalkan pesanan?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Batalkan</button>
              </form>
            @else
              <button class="btn btn-sm btn-outline-secondary" disabled>—</button>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="8" class="text-center">Belum ada pesanan</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{ $pesanans->links() }}
@endsection
