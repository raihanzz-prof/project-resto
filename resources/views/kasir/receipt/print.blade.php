<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <title>Struk Pembayaran #{{ $tx->idtransaksi }}</title>

  <style>
    /* ====== Layout thermal (58mm/80mm) ====== */
    :root {
      --paper-width: 58mm;
      /* ganti jadi 80mm kalau pakai printer 80mm */
      --font-size: 12px;
      /* sesuaikan preferensi */
    }

    html,
    body {
      margin: 0;
      padding: 0;
      font-family: ui-monospace, Menlo, Consolas, "DejaVu Sans Mono", monospace;
      font-size: var(--font-size);
      color: #111;
    }

    .ticket {
      width: var(--paper-width);
      margin: 0 auto;
      padding: 8px 10px;
    }

    .center {
      text-align: center;
    }

    .right {
      text-align: right;
    }

    .muted {
      color: #555;
    }

    .title {
      font-weight: 700;
      margin-bottom: 2px;
    }

    .row {
      display: flex;
      justify-content: space-between;
      gap: 8px;
    }

    hr {
      border: 0;
      border-top: 1px dashed #999;
      margin: 6px 0;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    td {
      padding: 2px 0;
      vertical-align: top;
    }

    /* ====== Controls (hanya tampil di layar) ====== */
    .actions {
      width: var(--paper-width);
      margin: 10px auto;
      display: flex;
      gap: 8px;
      justify-content: space-between;
    }

    @media print {
      .actions {
        display: none !important;
      }

      @page {
        size: var(--paper-width) auto;
        margin: 0;
      }

      body {
        margin: 0;
      }
    }
  </style>
</head>

<body>

  <div class="actions">
    <a href="{{ route('kasir.receipt.pdf', $tx->idtransaksi) }}" target="_blank" class="btn">Unduh PDF</a>
    <button onclick="window.print()">Cetak</button>
    <a href="{{ url()->previous() }}">Kembali</a>
  </div>

  <div class="ticket">
    <div class="center">
      <div class="title">RestoRehan</div>
      <div class="muted">Jl. Contoh No. 123, Kota</div>
    </div>
    <hr>
    <div>No. Transaksi: <strong>#{{ $tx->idtransaksi }}</strong></div>
    <div>Tanggal: {{ \Carbon\Carbon::parse($generated)->format('d/m/Y') }}</div>
    <div>Kasir: {{ $kasirName }}</div>
    <hr>

    <table>
      <tbody>

        @php
          // Hitung total semua menu
          $total = $order->details->sum(function ($d) {
            return $d->menu->harga * $d->jumlah;
          });
        @endphp

        <!-- HEADER PELANGGAN & MEJA (1x saja) -->
        <tr>
          <td colspan="2">
            <strong>Pelanggan: {{ optional($order->pelanggan)->namapelanggan ?? '—' }}</strong><br>
            @if($order->meja)
              <span class="muted">Meja: {{ $order->meja->nomormeja }}</span><br>
            @endif
          </td>
        </tr>

        <tr>
          <td colspan="2">
            <hr>
          </td>
        </tr>

        <!-- LIST MENU -->
        @foreach($order->details as $d)
          @php
            $harga = $d->menu->harga;
            $qty = $d->jumlah;
            $subtotal = $harga * $qty;
          @endphp

          <tr>
            <td colspan="2"><strong>{{ $d->menu->namamenu }}</strong></td>
          </tr>

          <tr>
            <td>{{ $qty }} x Rp{{ number_format($harga, 0, ',', '.') }}</td>
            <td class="right">Rp{{ number_format($subtotal, 0, ',', '.') }}</td>
          </tr>
        @endforeach

      </tbody>
    </table>

    <hr>

    <!-- TOTAL AKHIR -->
    <table>
      <tbody>
        <tr>
          <td><strong>Total</strong></td>
          <td class="right"><strong>Rp{{ number_format($total, 0, ',', '.') }}</strong></td>
        </tr>

        <tr>
          <td>Bayar</td>
          <td class="right">Rp{{ number_format($bayar, 0, ',', '.') }}</td>
        </tr>

        <tr>
          <td>Kembalian</td>
          <td class="right">Rp{{ number_format($kembali, 0, ',', '.') }}</td>
        </tr>
      </tbody>
    </table>

    <hr>


    <div class="center muted">Terima kasih atas kunjungan Anda.</div>
  </div>

  <script>
    // Auto-buka dialog print? uncomment kalau mau otomatis
    // window.addEventListener('load', () => window.print());
  </script>
</body>

</html>