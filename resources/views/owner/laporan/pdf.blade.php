<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<style>
  body{ font-family: DejaVu Sans, Arial, sans-serif; font-size:12px; color:#222; }
  h3{ margin:0 0 8px; }
  .muted{ color:#666; }
  table{ width:100%; border-collapse:collapse; margin-top:10px; }
  th,td{ border:1px solid #ccc; padding:6px 8px; }
  th{ background:#f3f5f9; text-align:left; }
  .text-right{ text-align:right; }
  .grid{ display:flex; gap:10px; margin:6px 0 10px; }
  .card{ border:1px solid #ddd; padding:6px 8px; border-radius:4px; }
  ul{ margin:0; padding-left:16px; }
</style>
</head>
<body>

  <h3>Laporan Owner</h3>

  <div class="muted">
    Periode: {{ \Carbon\Carbon::parse($start)->format('d/m/Y') }}
    s/d
    {{ \Carbon\Carbon::parse($end)->format('d/m/Y') }}<br>
    
    Dibuat:
    {{ \Carbon\Carbon::parse($generated)->format('d/m/Y') }}
    — Owner: {{ $ownerName }}
  </div>

  <div class="grid">
    <div class="card">Total Transaksi: <strong>{{ $totalTransaksi }}</strong></div>
    <div class="card">Omzet: <strong>Rp{{ number_format($omzet,0,',','.') }}</strong></div>
  </div>

  <table>
    <thead>
      <tr>
        <th style="width:40px">#</th>
        <th>Waktu</th>
        <th>Pelanggan</th>
        <th>Menu</th>
        <th style="width:60px">Qty</th>
        <th style="width:120px" class="text-right">Total</th>
      </tr>
    </thead>

    <tbody>
      @forelse($tx as $i => $t)
        @php
          $o = $t->pesanan;

          // Total banyak item
          $totalQty = $o->details->sum('jumlah');

          // Total harga dari tabel transaksi
          $totalHarga = $t->total;
        @endphp

        <tr>
          <td>{{ $i + 1 }}</td>
          <td>{{ \Carbon\Carbon::parse($t->created_at)->format('d/m/Y') }}</td>

          <td>{{ optional($o->pelanggan)->namapelanggan ?? '—' }}</td>

          <td>
            <ul>
              @foreach($o->details as $d)
                <li>
                  {{ $d->menu->namamenu ?? 'Menu dihapus' }}
                  <span class="muted">x{{ $d->jumlah }}</span>
                </li>
              @endforeach
            </ul>
          </td>

          <td>{{ $totalQty }}</td>

          <td class="text-right">
            Rp{{ number_format($totalHarga,0,',','.') }}
          </td>
        </tr>

      @empty
        <tr><td colspan="6" style="text-align:center">Tidak ada data</td></tr>
      @endforelse
    </tbody>
  </table>

</body>
</html>
