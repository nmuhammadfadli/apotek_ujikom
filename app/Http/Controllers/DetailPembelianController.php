<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Obat;
use Illuminate\Http\Request;

class DetailPembelianController extends Controller
{
    public function index(Pembelian $pembelian)
    {
        return response()->json(
            $pembelian->detail()->with('obat')->get()
        );
    }

    public function store(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'kd_obat' => 'required|exists:obat,kd_obat',
            'jumlah'  => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric'
        ]);

        $detail = DetailPembelian::create([
            'nota' => $pembelian->nota,
            'kd_obat' => $request->kd_obat,
            'jumlah' => $request->jumlah,
            'harga_satuan' => $request->harga_satuan,
        ]);

        // tambah stok
        Obat::where('kd_obat', $request->kd_obat)
            ->increment('stok', $request->jumlah);

        return response()->json($detail, 201);
    }

    public function update(Request $request, DetailPembelian $detail)
    {
        $request->validate([
            'jumlah' => 'required|integer|min:1'
        ]);

        $obat = Obat::findOrFail($detail->kd_obat);

        // rollback stok lama
        $obat->decrement('stok', $detail->jumlah);

        // update detail
        $detail->update([
            'jumlah' => $request->jumlah
        ]);

        // tambah stok baru
        $obat->increment('stok', $request->jumlah);

        return response()->json($detail);
    }

    public function destroy(DetailPembelian $detail)
    {
        $obat = Obat::findOrFail($detail->kd_obat);

        // kurangi stok
        $obat->decrement('stok', $detail->jumlah);

        $detail->delete();

        return response()->json(['success' => true]);
    }
}
