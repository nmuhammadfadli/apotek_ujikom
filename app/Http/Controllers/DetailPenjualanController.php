<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Obat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DetailPenjualanController extends Controller
{
    /**
     * Tambah item (detail) ke penjualan yang sudah ada.
     * Route contoh: POST /penjualan/{penjualan}/detail
     */
    public function store(Request $request, Penjualan $penjualan)
    {
        $data = $request->validate([
            'kd_obat' => 'required|exists:obat,kd_obat',
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            // kunci baris obat untuk mencegah race condition
            $obat = Obat::lockForUpdate()->findOrFail($data['kd_obat']);

            if ($obat->stok < $data['jumlah']) {
                throw new \Exception("Stok tidak cukup untuk obat: {$obat->nm_obat}");
            }

            $detail = $penjualan->detail()->create([
                'nota' => $penjualan->nota,
                'kd_obat' => $obat->kd_obat,
                'jumlah' => $data['jumlah'],
                'harga_satuan' => $data['harga_satuan'],
            ]);

            // kurangi stok
            $obat->decrement('stok', $data['jumlah']);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'detail' => $detail], 201);
            }

            return redirect()->route('penjualan.show', $penjualan)->with('success', 'Item berhasil ditambahkan.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Update jumlah / harga satuan detail.
     * Jika jumlah berubah, stok akan disesuaikan (selisih).
     * Route contoh (shallow): PUT /penjualan/detail/{detail}
     */
    public function update(Request $request, DetailPenjualan $detail)
    {
        $data = $request->validate([
            'jumlah' => 'required|integer|min:1',
            'harga_satuan' => 'required|numeric|min:0',
        ]);

        DB::beginTransaction();
        try {
            $obat = Obat::lockForUpdate()->findOrFail($detail->kd_obat);

            $oldJumlah = (int)$detail->jumlah;
            $newJumlah = (int)$data['jumlah'];
            $delta = $newJumlah - $oldJumlah; // + berarti perlu kurangi stok lagi; - berarti kembalikan stok

            if ($delta > 0 && $obat->stok < $delta) {
                throw new \Exception("Stok tidak cukup untuk menambah {$delta} pada obat: {$obat->nm_obat}");
            }

            // Sesuaikan stok
            if ($delta > 0) {
                $obat->decrement('stok', $delta);
            } elseif ($delta < 0) {
                $obat->increment('stok', abs($delta));
            }

            $detail->update([
                'jumlah' => $newJumlah,
                'harga_satuan' => $data['harga_satuan'],
            ]);

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true, 'detail' => $detail]);
            }

            // redirect ke halaman penjualan terkait
            return redirect()->route('penjualan.show', $detail->nota)->with('success', 'Item diperbarui.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    /**
     * Hapus detail (dan kembalikan stok).
     * Route contoh (shallow): DELETE /penjualan/detail/{detail}
     */
    public function destroy(Request $request, DetailPenjualan $detail)
    {
        DB::beginTransaction();
        try {
            $obat = Obat::lockForUpdate()->findOrFail($detail->kd_obat);

            // kembalikan stok
            $obat->increment('stok', $detail->jumlah);

            $detail->delete();

            DB::commit();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['success' => true]);
            }

            return back()->with('success', 'Item dihapus.');
        } catch (\Throwable $e) {
            DB::rollBack();

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json(['error' => $e->getMessage()], 422);
            }

            return back()->withErrors(['error' => $e->getMessage()]);
        }
    }
}
