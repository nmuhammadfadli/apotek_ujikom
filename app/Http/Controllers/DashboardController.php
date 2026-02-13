<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\Pelanggan;
use App\Models\Obat;
use App\Models\Penjualan;
use App\Models\DetailPenjualan;
use App\Models\Pembelian;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // counts
        $totalSupplier = Supplier::count();
        $totalPelanggan = Pelanggan::count();

        // penjualan hari ini (sum jumlah*harga_satuan via join penjualan_detail -> penjualan.tgl_nota)
        $today = Carbon::today()->toDateString();

        $penjualanHariIni = DB::table('penjualan_detail')
            ->join('penjualan', 'penjualan.nota', '=', 'penjualan_detail.nota')
            ->whereDate('penjualan.tgl_nota', $today)
            ->selectRaw('COALESCE(SUM(penjualan_detail.jumlah * penjualan_detail.harga_satuan),0) as total')
            ->value('total') ?? 0;

        // low stock obat (threshold)
        $lowStockThreshold = 5;
        $lowStock = Obat::where('stok', '<=', $lowStockThreshold)
            ->orderBy('stok','asc')
            ->limit(8)
            ->get(['kd_obat','nm_obat','stok','harga_jual']);

        // recent penjualan & pembelian
        $recentPenjualan = Penjualan::with('pelanggan','detail')->orderBy('created_at','desc')->limit(6)->get();
        $recentPembelian = Pembelian::with('supplier','detail')->orderBy('created_at','desc')->limit(6)->get();

        // --- Sales this week (last 7 days, including today)
        $labels = [];
        $totals = [];
        $start = Carbon::today()->subDays(6); // 7 days window: start..today
        for ($d = $start->copy(); $d->lte(Carbon::today()); $d->addDay()) {
            $labels[] = $d->format('d M'); // e.g. 08 Feb
            $date = $d->toDateString();
            $sum = DB::table('penjualan_detail')
                ->join('penjualan', 'penjualan.nota', '=', 'penjualan_detail.nota')
                ->whereDate('penjualan.tgl_nota', $date)
                ->selectRaw('COALESCE(SUM(penjualan_detail.jumlah * penjualan_detail.harga_satuan),0) as total')
                ->value('total') ?? 0;
            $totals[] = (float) $sum;
        }

        return view('dashboard', compact(
            'totalSupplier',
            'totalPelanggan',
            'penjualanHariIni',
            'lowStock',
            'recentPenjualan',
            'recentPembelian',
            'lowStockThreshold',
            'labels',
            'totals'
        ));
    }
}
