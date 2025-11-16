<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <style>
    body {
      font-family: DejaVu Sans, Arial, sans-serif;
      font-size: 12px;
      color: #222;
    }

    h2,
    h3 {
      margin: 0 0 6px;
    }

    .muted {
      color: #666;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
      font-size: 12px;
    }

    th,
    td {
      border: 1px solid #ccc;
      padding: 6px 8px;
      vertical-align: top;
    }

    th {
      background: #f3f5f9;
      text-align: left;
    }

    .text-right {
      text-align: right;
    }

    .mb-2 { margin-bottom: 8px; }
    .mb-3 { margin-bottom: 12px; }

    /* Kartu ringkasan */
    .grid {
      display: flex;
      gap: 16px;
      margin-bottom: 12px;
    }

    .card {
      border: 1px solid #ddd;
      padding: 8px 10px;
      border-radius: 6px;
      background: #fafafa;
    }
  </style>
</head>

<body>

  <h2>Laporan Order — Waiter</h2>
  <div class="mb-3">Rentang: {{ $start }} s/d {{ $end }}</div>

  <div class="grid">
    <div class="card">Total Order: <strong>{{ $totalOrder }}</strong></div>
    <div class="card">Total Item: <strong>{{ $totalItem }}</strong></div>
    <div class="card">Omzet: <strong>Rp{{ number_format($omzet, 0, ',', '.') }}</strong></div>
  </div>

  <h3 class="mb-2">Daftar Order</h3>

  <table>
    <thead>
      <tr>
        <th style="width:40px">#</th>
        <th>Waktu</th>
        <th>Pelanggan</th>
        <th>Menu</th>
        <th style="width:60px">Qty</th>
        <th style="width:120px" class="text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>

      @forelse($orders as $i => $o)

        @php
          // Total qty dari semua item
          $totalQty = $o->details->sum('jumlah');

          // Total subtotal dari semua item
          $subtotal = $o->details->sum(function($d) {
              return (int)$d->menu->harga * (int)$d->jumlah;
          });
        @endphp

        <tr>
          <td>{{ $i + 1 }}</td>

          <td>{{ \Carbon\Carbon::parse($o->created_at)->format('d/m/Y') }}</td>

          <td>{{ optional($o->pelanggan)->namapelanggan ?? '—' }}</td>

          {{-- Multi Menu --}}
          <td>
            <ul style="margin:0; padding-left: 18px;">
              @foreach($o->details as $d)
                <li>
                  {{ $d->menu->namamenu }}
                  <span class="muted">x{{ $d->jumlah }}</span>
                </li>
              @endforeach
            </ul>
          </td>

          <td>{{ $totalQty }}</td>

          <td class="text-right">
            Rp{{ number_format($subtotal, 0, ',', '.') }}
          </td>
        </tr>

      @empty

        <tr>
          <td colspan="6" style="text-align:center">Tidak ada data</td>
        </tr>

      @endforelse
    </tbody>
  </table>

</body>

</html>
