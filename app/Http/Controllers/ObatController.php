<?php
namespace App\Http\Controllers;

use App\Models\Obat;
use App\Models\Supplier;
use Illuminate\Http\Request;

class ObatController extends Controller
{
    public function index(Request $request)
    {
            $q = $request->query('q');
            $obats = Obat::with('supplier')
                ->when($q, fn($qb) => $qb->where('nm_obat', 'like', "%{$q}%"))
                ->paginate(15);
            return view('obat.index', compact('obats'));
        }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('obat.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nm_obat' => 'required|string|max:255',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer|min:0'
        ]);

        Obat::create($request->all());
        return redirect()->route('obat.index')->with('success', 'Obat ditambahkan.');
    }

    public function edit(Obat $obat)
    {
        $suppliers = Supplier::all();
        return view('obat.edit', compact('obat','suppliers'));
    }

    public function update(Request $request, Obat $obat)
    {
        $request->validate([
            'nm_obat' => 'required|string|max:255',
            'harga_jual' => 'required|numeric',
            'stok' => 'required|integer|min:0'
        ]);
        $obat->update($request->all());
        return redirect()->route('obat.index')->with('success','Obat diperbarui');
    }

    public function destroy(Obat $obat)
    {
        $obat->delete();
        return back()->with('success','Obat dihapus');
    }
}
