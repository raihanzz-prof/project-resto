<?php

namespace App\Http\Controllers;

use App\Models\detailPesanan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Menu;
use App\Models\Meja;
use App\Models\Pelanggan;
use App\Models\Pesanan;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;

class WaiterController extends Controller
{
    public function index()
    {
        $totalPesananHariIni = Pesanan::whereDate('created_at', now()->toDateString())
            ->where('iduser', auth()->id())->count();

        $belumDibayar = Pesanan::where('iduser', auth()->id())
            ->doesntHave('transaksi')->count();

        // daftar pesanan terbaru waiter ini
        $pesanans = Pesanan::with(['menu', 'pelanggan', 'meja', 'transaksi'])
            ->where('iduser', auth()->id())
            ->latest()->paginate(10);

        return view('waiter.dashboard', compact('totalPesananHariIni', 'belumDibayar', 'pesanans'));
    }

    /* =========================
       Kelola Order
       ========================= */
    public function orderIndex()
    {
        $pesanans = Pesanan::with(['menu', 'pelanggan', 'meja', 'transaksi'])
            ->where('iduser', auth()->id())
            ->latest()->paginate(10);

        return view('waiter.order.index', compact('pesanans'));
    }

    public function orderCreate(Request $request)
    {
        return view('waiter.order.create', [
            'menus' => Menu::orderBy('namamenu')->get(),
            'mejas' => Meja::orderBy('nomormeja')->get(), // jika belum ada meja, kirim collect([])
            'pelanggans' => Pelanggan::orderBy('namapelanggan')->take(30)->get(),
            'back' => $request->query('back'),
        ]);
    }

    public function orderStore(Request $request)
    {
        $request->validate([
            'menu' => 'required|array|min:1',
            'menu.*' => 'required|exists:menus,idmenu',
            'jumlah' => 'required|array|min:1',
            'jumlah.*' => 'integer|min:1',
            'idmeja' => 'nullable|exists:meja,idmeja',
            'idpelanggan' => 'nullable|exists:pelanggans,idpelanggan',
            'namapelanggan' => 'nullable|required_without:idpelanggan|string|max:255',
            'jeniskelamin' => 'nullable|required_without:idpelanggan|boolean',
            'nohp' => 'nullable|string|max:20',
            'alamat' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // tentukan pelanggan
            $pelangganId = $request->idpelanggan;
            if (!$pelangganId) {
                $pelanggan = Pelanggan::firstOrCreate(
                    ['nohp' => $request->nohp],
                    [
                        'namapelanggan' => $request->namapelanggan,
                        'jeniskelamin' => (int) $request->jeniskelamin,
                        'alamat' => $request->alamat,
                    ]
                );
                $pelangganId = $pelanggan->idpelanggan;
            }

            $pesanan = Pesanan::create([
                'idmeja' => $request->idmeja,
                'idpelanggan' => $pelangganId,
                'iduser' => Auth::id(),
            ]);
            foreach ($request->menu as $index => $productId) {
                detailPesanan::create([
                    'idpesanan' => $pesanan->idpesanan,
                    'idmenu' => $productId,
                    'jumlah' => $request->jumlah[$index],
                ]);
            }

            if ($request->filled('idmeja')) {
                Meja::where('idmeja', $request->idmeja)->update(['status' => 'terisi']);
            }
        });

        return redirect()->route('order.index')->with('success', 'Pesanan dibuat!');
    }

    public function orderCancel($id)
    {
        $pesanan = Pesanan::with('transaksi')->where('iduser', auth()->id())->findOrFail($id);
        if ($pesanan->transaksi) {
            return back()->with('error', 'Tidak bisa dibatalkan: sudah ada transaksi.');
        }
        if (!empty($pesanan->idmeja)) {
            Meja::where('idmeja', $pesanan->idmeja)->update(['status' => 'kosong']);
        }
        $pesanan->delete();
        return back()->with('success', 'Pesanan dibatalkan.');
    }

    /* =========================
       Laporan (berdasarkan pesanan waiter ini)
       ========================= */
    public function laporan(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
        $end = $request->query('end', now()->toDateString());

        // Ambil daftar pesanan milik waiter + semua detail item
        $orders = Pesanan::with(['details.menu', 'pelanggan', 'meja'])
            ->where('iduser', auth()->id())
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->latest()
            ->paginate(10);

        // Hitung total order (jumlah pesanan)
        $totalOrder = Pesanan::where('iduser', auth()->id())
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->count();

        // Hitung total item dengan mengambil dari detail_pesanans
        $totalItem = DetailPesanan::whereHas('pesanan', function ($q) use ($start, $end) {
            $q->where('iduser', auth()->id())
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
        })
            ->sum('jumlah');

        // Hitung omzet
        $omzet = DetailPesanan::with('menu')
            ->whereHas('pesanan', function ($q) use ($start, $end) {
                $q->where('iduser', auth()->id())
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
            })
            ->get()
            ->sum(function ($d) {
                return (int) $d->menu->harga * (int) $d->jumlah;
            });

        return view('waiter.laporan.index', compact(
            'start',
            'end',
            'orders',
            'totalOrder',
            'totalItem',
            'omzet'
        ));
    }


    public function laporanPdf(Request $request)
    {
        $start = $request->query('start', now()->toDateString());
        $end = $request->query('end', now()->toDateString());

        // Ambil semua pesanan + detail item
        $orders = Pesanan::with(['details.menu', 'pelanggan', 'meja'])
            ->where('iduser', auth()->id())
            ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end])
            ->latest()
            ->get();

        // Total order
        $totalOrder = $orders->count();

        // Total item
        $totalItem = DetailPesanan::whereHas('pesanan', function ($q) use ($start, $end) {
            $q->where('iduser', auth()->id())
                ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
        })
            ->sum('jumlah');

        // Omzet
        $omzet = DetailPesanan::with('menu')
            ->whereHas('pesanan', function ($q) use ($start, $end) {
                $q->where('iduser', auth()->id())
                    ->whereBetween(DB::raw('DATE(created_at)'), [$start, $end]);
            })
            ->get()
            ->sum(function ($d) {
                return (int) $d->menu->harga * (int) $d->jumlah;
            });

        $pdf = Pdf::loadView('waiter.laporan.pdf', [
            'start' => $start,
            'end' => $end,
            'orders' => $orders,
            'totalOrder' => $totalOrder,
            'totalItem' => $totalItem,
            'omzet' => $omzet,
            'namaWaiter' => auth()->user()->namauser ?? auth()->user()->email,
            'generatedAt' => now(),
        ])->setPaper('a4', 'portrait');

        return $pdf->download("laporan-waiter-{$start}-{$end}.pdf");
    }



    // =========================
    // Kelola Menu (Waiter)
    // =========================
    public function menuIndex()
    {
        $menus = Menu::orderBy('namamenu')->paginate(10);
        return view('waiter.menu.index', compact('menus'));
    }

    public function menuCreate(Request $request)
    {
        $back = $request->query('back');
        return view('waiter.menu.create', compact('back'));
    }

    public function menuStore(Request $request)
    {
        $request->validate([
            'namamenu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
        ]);

        Menu::create($request->only('namamenu', 'harga'));

        return redirect()->route('waiter.menu.index')->with('success', 'Menu berhasil ditambahkan.');
    }

    public function menuEdit(Request $request, $idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        $back = $request->query('back');
        return view('waiter.menu.edit', compact('menu', 'back'));
    }

    public function menuUpdate(Request $request, $idmenu)
    {
        $menu = Menu::findOrFail($idmenu);

        $request->validate([
            'namamenu' => 'required|string|max:255',
            'harga' => 'required|integer|min:0',
        ]);

        $menu->update($request->only('namamenu', 'harga'));

        return redirect()->route('waiter.menu.index')->with('success', 'Menu berhasil diperbarui.');
    }

    public function menuDestroy($idmenu)
    {
        Menu::where('idmenu', $idmenu)->delete();
        return redirect()->route('waiter.menu.index')->with('success', 'Menu berhasil dihapus.');
    }

}
