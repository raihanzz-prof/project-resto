<?php
// app/Http/Controllers/KasirController.php

namespace App\Http\Controllers;

use App\Models\Pesanan;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\Meja; // opsional kalau pakai meja

class KasirController extends Controller
{
    /* =========================
       DASHBOARD KASIR
       ========================= */
    public function index()
    {
        $today        = now()->toDateString();
        $unpaidCount  = Pesanan::doesntHave('transaksi')->count();
        $todayTx      = Transaksi::whereDate('created_at', $today)->get();
        $todayTxCount = $todayTx->count();
        $todayRevenue = $todayTx->sum('Total');

        $unpaids = Pesanan::with(['menu','pelanggan','meja'])
            ->doesntHave('transaksi')
            ->latest()
            ->paginate(10);

        return view('kasir.dashboard', compact('unpaidCount','todayTxCount','todayRevenue','unpaids'));
    }

    /* =========================
       KELOLA TRANSAKSI
       ========================= */
    public function transaksiIndex()
    {
        $unpaids = Pesanan::with(['menu','pelanggan','meja'])
            ->doesntHave('transaksi')
            ->latest()
            ->paginate(12);

        // Transaksi terbaru (opsional, untuk list ringkas)
        $paidLatest = Transaksi::with(['pesanan.menu','pesanan.pelanggan'])
            ->latest()->take(8)->get();

        return view('kasir.transaksi.index', compact('unpaids','paidLatest'));
    }

    public function transaksiPayForm($idpesanan, Request $request)
    {
        // load details.menu + pelanggan + meja + transaksi
        $order = Pesanan::with(['details.menu', 'pelanggan', 'meja', 'transaksi'])
            ->findOrFail($idpesanan);

        if ($order->transaksi) {
            return redirect()
                ->route('kasir.transaksi.index')
                ->with('error','Pesanan sudah dibayar.');
        }

        // hitung total dari semua detail
        $total = $order->details->sum(function($d){
            $harga = (int) optional($d->menu)->harga;
            $qty   = (int) ($d->quantity ?? $d->jumlah ?? 0);
            return $harga * $qty;
        });

        return view('kasir.transaksi.pay', [
            'order' => $order,
            'total' => $total,
            'back'  => $request->query('back', route('kasir.transaksi.index')),
        ]);
    }


    public function transaksiPayStore(Request $request, $idpesanan)
    {
        $order = Pesanan::with(['details.menu','transaksi'])->findOrFail($idpesanan);
        if ($order->transaksi) {
            return redirect()->route('kasir.transaksi.index')->with('error','Pesanan sudah dibayar.');
        }

        $total = $order->details->sum(function($d){
            $harga = (int) optional($d->menu)->harga;
            $qty   = (int) ($d->quantity ?? $d->jumlah ?? 0);
            return $harga * $qty;
        });

        $data = $request->validate([
            'bayar' => ['required','integer','min:'.$total],
        ]);

        DB::transaction(function() use ($order, $total, $data) {
            $transaksi = Transaksi::create([
                'idpesanan' => $order->idpesanan,   // sesuaikan PK-nya
                'total'     => $total,
                'bayar'     => (int) $data['bayar'],
                'kembalian' => (int) $data['bayar'] - $total,
                'iduser'    => Auth::id(),
            ]);

            // (opsional) set session untuk tombol di dashboard
            session()->flash('receipt_url', route('kasir.receipt.pdf', $transaksi->idtransaksi ?? $transaksi->id));
            session()->flash('receipt_print_url', route('kasir.receipt.print', $transaksi->idtransaksi ?? $transaksi->id));
        });

        return redirect()->route('kasir.dashboard')->with('success', 'Pembayaran berhasil diproses.');
    }


    /* =========================
       LAPORAN
       ========================= */
    public function laporan(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
        $end   = $request->query('end',   now()->toDateString());

        $tx = Transaksi::with(['pesanan.menu','pesanan.pelanggan'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->latest()
            ->paginate(15);

        // KPI laporan
        $allTx = Transaksi::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->get();
        $totalTransaksi = $allTx->count();
        $omzet          = $allTx->sum('Total');

        return view('kasir.laporan.index', compact('start','end','tx','totalTransaksi','omzet'));
    }

    public function laporanPdf(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
        $end   = $request->query('end',   now()->toDateString());

        $tx = Transaksi::with(['pesanan.menu','pesanan.pelanggan'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->latest()
            ->get();

        $totalTransaksi = $tx->count();
        $omzet          = $tx->sum('Total');

        $pdf = Pdf::loadView('kasir.laporan.pdf', [
            'start'        => $start,
            'end'          => $end,
            'tx'           => $tx,
            'totalTransaksi'=> $totalTransaksi,
            'omzet'        => $omzet,
            'kasirName'    => auth()->user()->namauser ?? auth()->user()->email,
            'generated'    => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-kasir-{$start}-{$end}.pdf");
    }

    /* =========================
       STRUK PDF (opsional)
       ========================= */
    public function receiptPdf($idtransaksi)
    {
        $tx = Transaksi::with(['pesanan.menu','pesanan.pelanggan','pesanan.meja'])->findOrFail($idtransaksi);

        $order   = $tx->pesanan;
        $harga   = (int) optional($order->menu)->harga;
        $total   = $tx->total;
        $bayar   = $tx->bayar;
        $kembali = $bayar - $total;

        $pdf = Pdf::loadView('kasir.receipt.pdf', [
            'tx'        => $tx,
            'order'     => $order,
            'harga'     => $harga,
            'total'     => $total,
            'bayar'     => $bayar,
            'kembali'   => $kembali,
            'kasirName' => auth()->user()->namauser ?? auth()->user()->email,
            'generated' => now(),
        ])->setPaper('a5', 'portrait');

        return $pdf->download("struk-{$tx->idtransaksi}.pdf");
    }

        public function receiptPrint($idtransaksi)
    {
        $tx = Transaksi::with(['pesanan.menu','pesanan.pelanggan','pesanan.meja'])
                ->findOrFail($idtransaksi);

        $order   = $tx->pesanan;
        $harga   = (int) optional($order->menu)->harga;
        $total   = (int) $tx->total;  // pastikan lowercase sesuai kolom DB
        $bayar   = (int) $tx->bayar;
        $kembali = $bayar - $total;

        return view('kasir.receipt.print', [
            'tx'        => $tx,
            'order'     => $order,
            'harga'     => $harga,
            'total'     => $total,
            'bayar'     => $bayar,
            'kembali'   => $kembali,
            'kasirName' => auth()->user()->namauser ?? auth()->user()->email,
            'generated' => now(),
        ]);
    }
}
