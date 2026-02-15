<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use App\Models\DetailPembelian;
use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class PembelianController extends Controller
{
    public function index(Request $request)
    {
        $q = $request->query('q');
        $pembelians = Pembelian::with('supplier')
            ->when($q, fn($qb) => $qb->where('nota','like', "%{$q}%")
                ->orWhereHas('supplier', fn($q2) => $q2->where('nm_supplier','like', "%{$q}%")))
            ->orderBy('created_at','desc')
            ->paginate(15);

        return view('pembelian.index', compact('pembelians'));
    }

    // show POS form
    public function create()
    {
        $obats = Obat::orderBy('nm_obat')->get(['kd_obat','nm_obat','harga_beli','stok']);
        $suppliers = Supplier::orderBy('nm_supplier')->get(['id','nm_supplier']);
        return view('pembelian.pos', compact('obats','suppliers'));
    }

    // store header + details in one transaction
    public function store(Request $request)
    {
       
        $itemsRaw = $request->input('items');
        if (is_string($itemsRaw)) {
            $itemsDecoded = json_decode($itemsRaw, true);
            $request->merge(['items' => $itemsDecoded]);
        }

        $request->validate([
            'kd_supplier' => ['required', Rule::exists((new Supplier)->getTable(), 'id')],
            'tgl_nota' => ['required','date'],
            'diskon' => ['nullable','numeric','min:0'],
            'items' => ['required','array','min:1'],
            'items.*.kd_obat' => ['required', Rule::exists((new Obat)->getTable(), 'kd_obat')],
            'items.*.jumlah' => ['required','integer','min:1'],
            'items.*.harga_satuan' => ['required','numeric','min:0'],
        ]);

        $items = $request->input('items');
        $diskon = $request->input('diskon', 0);
        $kd_supplier = $request->input('kd_supplier');
        $tgl_nota = $request->input('tgl_nota');

        DB::beginTransaction();
        try {
           
            $nota = 'PB-' . now()->format('YmdHis') . '-' . Str::upper(Str::random(4));
          
            $totalBefore = 0;
            foreach ($items as $it) {
                $totalBefore += ($it['jumlah'] * $it['harga_satuan']);
            }

            // create pembelian header
            $pembelian = Pembelian::create([
                'nota' => $nota,
                'tgl_nota' => $tgl_nota,
                'kd_supplier' => $kd_supplier,
                'diskon' => $diskon,
                
            ]);

            // create details & increase stock 
            foreach ($items as $it) {
                $obat = Obat::lockForUpdate()->findOrFail($it['kd_obat']);

                
                DetailPembelian::create([
                    'nota' => $nota,
                    'kd_obat' => $obat->kd_obat,
                    'jumlah' => $it['jumlah'],
                    'harga_satuan' => $it['harga_satuan'],
                ]);

                
                $obat->increment('stok', $it['jumlah']);
            }

            DB::commit();

            return redirect()->route('pembelian.show', $pembelian)->with('success','Pembelian berhasil disimpan.');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // show pembelian header + details
    public function show(Pembelian $pembelian)
    {
        $pembelian->load('detail.obat','supplier');
        $subtotal = $pembelian->detail->sum(fn($d)=> $d->jumlah * $d->harga_satuan);
        $diskon = (float) ($pembelian->diskon ?? 0);
        $discountAmount = ($diskon>0 && $diskon<=100) ? ($subtotal * $diskon / 100) : $diskon;
        $total = max(0, $subtotal - $discountAmount);

        return view('pembelian.show', compact('pembelian','subtotal','discountAmount','total'));
    }

    public function edit(Pembelian $pembelian)
    {
        $suppliers = Supplier::orderBy('nm_supplier')->get();
        return view('pembelian.edit', compact('pembelian','suppliers'));
    }

    public function update(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'kd_supplier' => ['required', Rule::exists((new Supplier)->getTable(), 'id')],
            'tgl_nota' => ['required','date'],
            'diskon' => ['nullable','numeric'],
        ]);
        $pembelian->update($request->only(['kd_supplier','tgl_nota','diskon']));
        return redirect()->route('pembelian.index')->with('success', 'Pembelian diperbarui');
    }

    public function destroy(Pembelian $pembelian)
    {
       
        $pembelian->delete();
        return back()->with('success','Pembelian dihapus');
    }
}
