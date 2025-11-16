<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Transaksi;
use App\Models\Pesanan;
use App\Models\Menu;
use App\Models\Pelanggan;
use Barryvdh\DomPDF\Facade\Pdf;

class OwnerController extends Controller
{
    // Dashboard Owner: KPI ringkas + pintu ke laporan
    public function index()
    {
        $today = now()->toDateString();

        $todayTx       = Transaksi::whereDate('created_at', $today)->get();
        $todayTxCount  = $todayTx->count();
        $todayRevenue  = $todayTx->sum('total'); // pastikan kolom lowercase

        $unpaidCount   = Pesanan::doesntHave('transaksi')->count();
        $menuCount     = Menu::count();
        $custCount     = Pelanggan::count();

        return view('owner.dashboard', compact(
            'todayTxCount','todayRevenue','unpaidCount','menuCount','custCount'
        ));
    }

    // Halaman laporan (filter tanggal)
    public function laporan(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
        $end   = $request->query('end',   now()->toDateString());

        $tx = Transaksi::with(['pesanan.menu','pesanan.pelanggan'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->latest()
            ->paginate(15);

        $allTx          = Transaksi::whereBetween(DB::raw('DATE(created_at)'), [$start, $end])->get();
        $totalTransaksi = $allTx->count();
        $omzet          = $allTx->sum('total');

        return view('owner.laporan.index', compact('start','end','tx','totalTransaksi','omzet'));
    }

    // Unduh PDF laporan
    public function laporanPdf(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
        $end   = $request->query('end',   now()->toDateString());

        $tx = Transaksi::with(['pesanan.menu','pesanan.pelanggan'])
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->latest()
            ->get();

        $totalTransaksi = $tx->count();
        $omzet          = $tx->sum('total');

        $pdf = Pdf::loadView('owner.laporan.pdf', [
            'start' => $start,
            'end'   => $end,
            'tx'    => $tx,
            'totalTransaksi' => $totalTransaksi,
            'omzet' => $omzet,
            'ownerName' => auth()->user()->namauser ?? auth()->user()->email,
            'generated' => now(),
        ])->setPaper('a4','portrait');

        return $pdf->download("laporan-owner-{$start}-{$end}.pdf");
    }
}
