<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;

class AgentController extends Controller
{
    public function dashboard()
    {

        return view('agent.dashboard');
    }
    public function kasir()
    {
        return view('agent.kasir');
    }

    public function getProduk(Request $request)
    {
        $searchTerm = $request->input('search');

        // Search for products by code or name (using LIKE for partial matches)
        $products = Produk::where('code', 'like', "%$searchTerm%")
            ->orWhere('nama', 'like', "%$searchTerm%")
            ->get();

        if ($products->isNotEmpty()) {
            return response()->json($products);
        } else {
            return response()->json([]);
        }
    }

    public function simpanTransaksi(Request $request)
    {

        foreach ($request->dataTransaksi as $item) {
            $produk = Produk::where('code', $item['code'])->first();
            if ($produk && $produk->stok < $item['jumlah']) {
                return response()->json(['error' => 'Stok tidak mencukupi untuk produk: ' . $produk->nama], 400);
            }
        }

        $request->validate([
            'dataTransaksi' => 'required|array|min:1',
            'bayar' => 'required|numeric|min:0',
            'totalHarga' => 'required|numeric|min:0',
            'kembalian' => 'required|numeric|min:0',
        ]);

        // Simpan transaksi
        $transaksi = new Transaksi();
        $transaksi->total_harga = $request->totalHarga;
        $transaksi->bayar = $request->bayar;
        $transaksi->kembalian = $request->kembalian;
        $transaksi->detail_transaksi = json_encode($request->dataTransaksi); // Simpan produk sebagai JSON
        $transaksi->save();

        // Kurangi stok produk
        foreach ($request->dataTransaksi as $item) {
            $produk = Produk::where('code', $item['code'])->first();
            if ($produk) {
                // Pastikan stok tidak negatif
                $produk->stok = max(0, $produk->stok - $item['jumlah']);
                $produk->save();
            }
        }

        return response()->json([
            'transaksi_id' => $transaksi->id // Pastikan ID transaksi terkirim ke frontend
        ]);
    }

    public function lihatTransaksi($id)
    {
        $transaksi = Transaksi::findOrFail($id);

        $detailProduk = json_decode($transaksi->detail_transaksi, true);

        return view('Transaksi', compact('transaksi', 'detailProduk'));
    }
    public function semuaTransaksi()
    {
        $transaksi = Transaksi::all();
        return view('agent.listTransaksi', compact('transaksi'));
    }
    public function cetakPDF($id)
    {
        $transaksi = Transaksi::findOrFail($id);
        $detailTransaksi = json_decode($transaksi->detail_transaksi, true);

        // Generate PDF
        $pdf = PDF::loadView('agent.struk_pdf', compact('transaksi', 'detailTransaksi'));

        // Kembalikan response PDF
        return $pdf->download('struk_transaksi_' . $transaksi->id . '.pdf');
    }
}
