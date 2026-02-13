<?php

namespace App\Http\Controllers;

use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Obat;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PenjualanController extends Controller
{
    // index: cari by nota or pelanggan
    public function index(Request $request)
    {
        $q = $request->query('q');
        $penjualans = Penjualan::with('pelanggan')
            ->when($q, fn($qb) => $qb->where('nota','like', "%{$q}%")
                ->orWhereHas('pelanggan', fn($q2) => $q2->where('nm_pelanggan','like', "%{$q}%")))
            ->orderBy('created_at','desc')
            ->paginate(15);

        return view('penjualan.index', compact('penjualans'));
    }

    // show POS create form
    public function create()
    {
        // load data yang dibutuhkan oleh POS
        $obats = Obat::orderBy('nm_obat')->get(['kd_obat','nm_obat','harga_jual','stok']);
        $pelanggans = Pelanggan::orderBy('nm_pelanggan')->get(['kd_pelanggan','nm_pelanggan']);
        // render view POS
        return view('penjualan.create', compact('obats','pelanggans'));
    }

  
    public function store(Request $request)
    {
        $itemsRaw = $request->input('items');
        if (is_string($itemsRaw)) {
            $itemsDecoded = json_decode($itemsRaw, true);
            $request->merge(['items' => $itemsDecoded]);
        }
        $request->validate([
            'kd_pelanggan' => ['nullable', Rule::exists((new Pelanggan)->getTable(), 'kd_pelanggan')],
            'tgl_nota' => ['required','date'],
            'diskon' => ['nullable','numeric','min:0'],
            'items' => ['required','array','min:1'],
            'items.*.kd_obat' => ['required', Rule::exists((new Obat)->getTable(), 'kd_obat')],
            'items.*.jumlah' => ['required','integer','min:1'],
            'items.*.harga_satuan' => ['required','numeric','min:0'],
        ]);

        $items = $request->input('items');
        $diskon = $request->input('diskon', 0);
        $kd_pelanggan = $request->input('kd_pelanggan', null);
        $tgl_nota = $request->input('tgl_nota');

        DB::beginTransaction();
        try {
            // generate nota unik (string)
            $nota = 'PJ-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));

            // compute total before discount
            $totalBefore = 0;
            foreach ($items as $it) {
                $totalBefore += ($it['jumlah'] * $it['harga_satuan']);
            }

            // apply discount (we'll treat diskon as percentage if >0 and <=100)
            $discountAmount = 0;
            if ($diskon && $diskon > 0 && $diskon <= 100) {
                $discountAmount = ($totalBefore * $diskon) / 100.0;
            } elseif ($diskon && $diskon > 100) {
                // if user supplied absolute value larger than 100 we interpret as absolute amount
                $discountAmount = floatval($diskon);
            }

            $totalAfter = max(0, $totalBefore - $discountAmount);

            // create penjualan header
            $penjualan = Penjualan::create([
                'nota' => $nota,
                'tgl_nota' => $tgl_nota,
                'kd_pelanggan' => $kd_pelanggan,
                'diskon' => $diskon,
                // you can store total if you have a column, else compute from details when needed
            ]);

            // insert detail rows & update stok (with lock)
            foreach ($items as $it) {
                // lock obat row for update
                $obat = Obat::lockForUpdate()->findOrFail($it['kd_obat']);

                if ($obat->stok < $it['jumlah']) {
                    throw new \Exception("Stok tidak cukup untuk obat: {$obat->nm_obat} (stok: {$obat->stok})");
                }

                // create detail
                DetailPenjualan::create([
                    'nota' => $nota,
                    'kd_obat' => $obat->kd_obat,
                    'jumlah' => $it['jumlah'],
                    'harga_satuan' => $it['harga_satuan'],
                ]);

                // decrement stok
                $obat->decrement('stok', $it['jumlah']);
            }

            DB::commit();

            return redirect()->route('penjualan.index', $penjualan)->with('success','Penjualan berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // show detail page (nota)
public function show(Penjualan $penjualan)
{
    // pastikan relasi yang dibutuhkan di-load: detail + hubungan obat pada tiap detail + pelanggan
    $penjualan->load(['detail.obat', 'pelanggan']);

    // hitung total (dihitung dari detail)
    $subtotal = $penjualan->detail->sum(function ($d) {
        return $d->jumlah * $d->harga_satuan;
    });

    // hitung diskon (anggap diskon kolom menyimpan persentase 0-100; jika absolute,
    // sesuaikan logika)
    $diskon = (float) ($penjualan->diskon ?? 0);
    $discountAmount = 0;
    if ($diskon > 0 && $diskon <= 100) {
        $discountAmount = ($subtotal * $diskon) / 100;
    } elseif ($diskon > 100) {
        $discountAmount = $diskon; // already absolute
    }
    $total = max(0, $subtotal - $discountAmount);

    return view('penjualan.show', compact('penjualan', 'subtotal', 'discountAmount', 'total'));
}


    public function edit(Penjualan $penjualan)
    {
        $suppliers = Supplier::all();
        return view('penjualan.edit', compact('penjualan','suppliers'));
    }


    public function update(Request $request, Penjualan $penjualan)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tgl_nota' => 'required|date',
            'total_harga' => 'required|numeric'
        ]);
        $penjualan->update($request->all());
        return redirect()->route('penjualan.index')->with('success', 'Penjualan diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Penjualan $penjualan)
    {
        $penjualan->delete();
        return back()->with('success','Penjualan dihapus');
    }
}
