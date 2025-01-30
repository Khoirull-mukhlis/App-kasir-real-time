<?php

namespace App\Http\Controllers;
use App\Models\Produk;
use Illuminate\Http\Request;

class ProdukController extends Controller
{
    public function index()
    {
        $produks = Produk::paginate(10);
        return view('admin.product', compact('produks'));
    }
    public function store(Request $request)
    {
            $request->validate([
                'code' => 'required|unique:produks|max:255',
                'nama' => 'required',
                'harga' => 'required|integer',
                'stok' => 'required|integer',
            ]);
    
            Produk::create($request->all());
            return redirect()->route('produks.index')->with('success', 'Produk berhasil ditambahkan.');
        }
    public function edit($id)
    {
    $produks = Produk::findOrFail($id);
    return view('produks.index', compact('produks')); // Mengirimkan data produk untuk di tampilkan pada modal
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'code' => 'required|max:255|unique:produks,code,' . $id,
            'nama' => 'required',
            'harga' => 'required|integer',
            'stok' => 'required|integer',
        ]);

        $produk = Produk::findOrFail($id);
        $produk->update($request->all());
        return redirect()->route('produks.index')->with('success', 'Produk berhasil diperbarui.');
    }
        public function destroy($id)
    {
        $produk = Produk::findOrFail($id);
        $produk->delete();
        return redirect()->route('produks.index')->with('success', 'Produk berhasil dihapus.');
    }
}
