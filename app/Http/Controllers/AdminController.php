<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use App\Models\User;
use App\Models\Pelanggan;
use App\Models\Transaksi;
use App\Models\Meja; // ⬅️ pastikan ini ada

class AdminController extends Controller
{
    public function index()
    {
        return view('admin.dashboard', [
            'totalUser'       => User::count(),
            'totalMenu'       => Menu::count(),
            'totalPelanggan'  => Pelanggan::count(), // boleh dihapus jika tidak ditampilkan
            'totalTransaksi'  => Transaksi::count(),
            'totalMeja'       => Meja::count(),       // ⬅️ untuk KPI Meja
        ]);
    }

    /** =========================
     *  Kelola Meja (CRUD ringan)
     *  ========================= */

    // List meja
    public function mejaIndex()
    {
        $mejas = Meja::orderBy('nomormeja')->paginate(10);
        return view('admin.meja.index', compact('mejas'));
    }

    // Form create
    public function mejaCreate()
    {
        return view('admin.meja.create');
    }

    // Simpan meja baru
    public function mejaStore(Request $request)
    {
        $request->validate([
            'nomormeja'  => 'required|string|max:50|unique:meja,nomormeja',
            'status'     => 'required|in:kosong,terisi,booking',
            'keterangan' => 'nullable|string|max:255',
        ]);

        Meja::create($request->only('nomormeja','status','keterangan'));

        return redirect()->route('meja.index')->with('success', 'Meja berhasil ditambahkan.');
    }

    // Form edit
    public function mejaEdit($idmeja)
    {
        $meja = Meja::findOrFail($idmeja);
        return view('admin.meja.edit', compact('meja'));
    }

    // Update meja
    public function mejaUpdate(Request $request, $idmeja)
    {
        $meja = Meja::findOrFail($idmeja);

        $request->validate([
            'nomormeja'  => 'required|string|max:50|unique:meja,nomormeja,' . $meja->idmeja . ',idmeja',
            'status'     => 'required|in:kosong,terisi,booking',
            'keterangan' => 'nullable|string|max:255',
        ]);

        $meja->update($request->only('nomormeja','status','keterangan'));

        return redirect()->route('meja.index')->with('success', 'Meja berhasil diperbarui.');
    }

    // Hapus meja
    public function mejaDestroy($idmeja)
    {
        Meja::where('idmeja', $idmeja)->delete();
        return redirect()->route('meja.index')->with('success', 'Meja berhasil dihapus.');
    }

    /** =========================
     *  (Opsional) Kelola Menu/User
     *  ========================= */
    public function manageMenu()
    {
        $menus = Menu::all();
        return view('admin.menu.index', compact('menus'));
    }

    public function manageUser()
    {
        $users = User::all();
        return view('admin.user.index', compact('users'));
    }

     /** =========================
     *  Kelola Menu (CRUD)
     *  ========================= */
    // LIST
    public function menuIndex()
    {
        $menus = Menu::orderBy('namamenu')->paginate(10);
        return view('admin.menu.index', compact('menus'));
    }

    // CREATE FORM
    public function menuCreate(Request $request)
    {
        $back = $request->query('back'); // untuk tombol kembali (opsional)
        return view('admin.menu.create', compact('back'));
    }

    // STORE
    public function menuStore(Request $request)
    {
        $request->validate([
            'namamenu' => 'required|string|max:255',
            'harga'    => 'required|integer|min:0',
        ]);

        Menu::create($request->only('namamenu','harga'));

        return redirect()->route('menu.index')->with('success','Menu berhasil ditambahkan.');
    }

    // EDIT FORM
    public function menuEdit(Request $request, $idmenu)
    {
        $menu = Menu::findOrFail($idmenu);
        $back = $request->query('back');
        return view('admin.menu.edit', compact('menu','back'));
    }

    // UPDATE
    public function menuUpdate(Request $request, $idmenu)
    {
        $menu = Menu::findOrFail($idmenu);

        $request->validate([
            'namamenu' => 'required|string|max:255',
            'harga'    => 'required|integer|min:0',
        ]);

        $menu->update($request->only('namamenu','harga'));

        return redirect()->route('menu.index')->with('success','Menu berhasil diperbarui.');
    }

    // DESTROY
    public function menuDestroy($idmenu)
    {
        Menu::where('idmenu',$idmenu)->delete();
        return redirect()->route('menu.index')->with('success','Menu berhasil dihapus.');
    }

}
