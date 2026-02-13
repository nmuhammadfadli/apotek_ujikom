<?php

namespace App\Http\Controllers;

use App\Models\Pembelian;
use Illuminate\Http\Request;

class PembelianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         $q = $request->query('q');
            $pembelians = Pembelian::with('supplier')
                ->when($q, fn($qb) => $qb->where('nm_obat', 'like', "%{$q}%"))
                ->paginate(15);
            return view('pembelian.index', compact('pembelians'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
         $suppliers = Supplier::paginate(15);
        return view('pembelian.create', compact('suppliers'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tgl_pembelian' => 'required|date',
            'total_harga' => 'required|numeric'
        ]);
        Pembelian::create($request->all());
        return redirect()->route('pembelian.index')->with('success', 'Pembelian ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pembelian $pembelian)
    {
        return view('pembelian.show', compact('pembelian'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pembelian $pembelian)
    {
        $suppliers = Supplier::all();
        return view('pembelian.edit', compact('pembelian','suppliers'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pembelian $pembelian)
    {
        $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'tgl_pembelian' => 'required|date',
            'total_harga' => 'required|numeric'
        ]);
        $pembelian->update($request->all());
        return redirect()->route('pembelian.index')->with('success', 'Pembelian diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pembelian $pembelian)
    {
        $pembelian->delete();
        return back()->with('success','Pembelian dihapus');
    }
}
