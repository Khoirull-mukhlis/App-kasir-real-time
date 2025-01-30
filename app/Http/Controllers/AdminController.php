<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
class AdminController extends Controller
{
    public function dashboard()
    {
        return view('admin.dashboard');
    }
    public function product()
    {
        return view('admin.product');
    }
    public function Transaksi()
    {
        $transaksi = Transaksi::all();
        return view('admin.Transaksi', compact('transaksi'));
    }
    public function destroy($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $transaksi->delete();
        return redirect()->route('admin.transaksi')->with('success', 'Transaksi berhasil dihapus.');
    }
}
