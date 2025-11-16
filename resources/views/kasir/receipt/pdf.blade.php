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

    h3 {
      margin: 0 0 8px;
    }

    .muted {
      color: #666;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 10px;
    }

    th,
    td {
      border: 1px solid #ccc;
      padding: 6px 8px;
    }

    th {
      background: #f5f7fb;
      text-align: left;
    }

    .text-right {
      text-align: right;
    }
  </style>
</head>

<body>
  <h3>RestoRehan — Struk Pembayaran</h3>
  <div class="muted">
    No. Transaksi: {{ $tx->idtransaksi }}<br>
    Tanggal: {{ \Carbon\Carbon::parse($generated)->format('d/m/Y') }}<br>
    Kasir: {{ $kasirName }}
  </div>

  <table>
    <thead>
      <tr>
        <th>Menu / Pelanggan</th>
        <th class="text-right">Harga</th>
        <th class="text-right">Qty</th>
        <th class="text-right">Subtotal</th>
      </tr>
    </thead>
    <tbody>

      @php
        // Hitung total semua menu
        $total = $order->details->sum(function ($d) {
          return $d->menu->harga * $d->jumlah;
        });
      @endphp

      <!-- Header pelanggan & meja (1x saja) -->
      <tr>
        <td colspan="4" style="background:#fafafa;">
          <strong>Pelanggan: {{ optional($order->pelanggan)->namapelanggan ?? '—' }}</strong>
          @if($order->meja)
            <span class="muted"> | Meja: {{ $order->meja->nomormeja }}</span>
          @endif
        </td>
      </tr>

      <!-- Daftar menu -->
      @foreach($order->details as $d)
        @php
          $harga = $d->menu->harga;
          $qty = $d->jumlah;
          $subtotal = $harga * $qty;
        @endphp

        <tr>
          <td>
            {{ $d->menu->namamenu }}
          </td>

          <td class="text-right">
            Rp{{ number_format($harga, 0, ',', '.') }}
          </td>

          <td class="text-right">
            {{ $qty }}
          </td>

          <td class="text-right">
            Rp{{ number_format($subtotal, 0, ',', '.') }}
          </td>
        </tr>
      @endforeach

      <!-- Total -->
      <tr>
        <td colspan="3"><strong>Total</strong></td>
        <td class="text-right"><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></td>
      </tr>

      <!-- Bayar -->
      <tr>
        <td colspan="3">Bayar</td>
        <td class="text-right">Rp{{ number_format($bayar, 0, ',', '.') }}</td>
      </tr>

      <!-- Kembalian -->
      <tr>
        <td colspan="3">Kembalian</td>
        <td class="text-right">Rp{{ number_format($kembali, 0, ',', '.') }}</td>
      </tr>

    </tbody>

  </table>

  <p class="muted" style="margin-top:10px">Terima kasih telah berkunjung.</p>
</body>

</html>