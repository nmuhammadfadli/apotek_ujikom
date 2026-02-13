@extends('layouts.admin')

@section('title','Dashboard')

@section('content')
<div class="row g-3">
  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Total Supplier</h6>
        <div class="display-6">{{ $totalSupplier ?? 0 }}</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Total Pelanggan</h6>
        <div class="display-6">{{ $totalPelanggan ?? 0 }}</div>
      </div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-lg-4">
    <div class="card">
      <div class="card-body">
        <h6 class="card-title">Penjualan Hari Ini</h6>
        <div class="display-6">Rp {{ number_format($penjualanHariIni ?? 0,0,',','.') }}</div>
      </div>
    </div>
  </div>
</div>

<!-- tambahan: Low stock + Recent transactions -->
<div class="row mt-3 g-3">
  <div class="col-lg-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Obat - Low Stock (≤ {{ $lowStockThreshold }})</h3>
        <a href="{{ route('obat.index') }}" class="btn btn-sm btn-light">Lihat Semua</a>
      </div>
      <div class="card-body">
        @if($lowStock->isEmpty())
          <div class="text-muted">Semua stok obat aman.</div>
        @else
          <div class="table-responsive">
            <table class="table table-sm table-vcenter">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>Nama</th>
                  <th class="text-end">Stok</th>
                  <th class="text-end">Harga jual</th>
                </tr>
              </thead>
              <tbody>
                @foreach($lowStock as $o)
                  <tr>
                    <td>{{ $o->kd_obat }}</td>
                    <td>{{ $o->nm_obat }}</td>
                    <td class="text-end"><span class="low-stock">{{ $o->stok }}</span></td>
                    <td class="text-end">Rp {{ number_format($o->harga_jual,0,',','.') }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="col-lg-6">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Transaksi Terbaru</h3>
        <div>
          <a href="{{ route('penjualan.index') }}" class="btn btn-sm btn-light">Penjualan</a>
          <a href="{{ route('pembelian.index') }}" class="btn btn-sm btn-light">Pembelian</a>
        </div>
      </div>

      <div class="card-body">
        <h6 class="card-subtitle mb-2 text-muted">Penjualan</h6>
        @if($recentPenjualan->isEmpty())
          <div class="text-muted mb-3">Belum ada penjualan.</div>
        @else
          <div class="list-group mb-3">
            @foreach($recentPenjualan as $p)
              <a href="{{ route('penjualan.show', $p) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-600">{{ $p->nota }}</div>
                  <small class="text-muted">{{ optional($p->tgl_nota)->format ? $p->tgl_nota : (optional($p->created_at)->format('Y-m-d H:i')) }}</small>
                  <div class="text-muted small">{{ $p->pelanggan->nm_pelanggan ?? 'Umum' }}</div>
                </div>
                <div class="text-end">
                  <div class="text-muted small">Items: {{ $p->detail->count() }}</div>
                </div>
              </a>
            @endforeach
          </div>
        @endif

        <h6 class="card-subtitle mb-2 text-muted">Pembelian</h6>
        @if($recentPembelian->isEmpty())
          <div class="text-muted">Belum ada pembelian.</div>
        @else
          <div class="list-group">
            @foreach($recentPembelian as $b)
              <a href="{{ route('pembelian.show', $b) }}" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center">
                <div>
                  <div class="fw-600">{{ $b->nota }}</div>
                  <small class="text-muted">{{ optional($b->tgl_nota)->format ? $b->tgl_nota : (optional($b->created_at)->format('Y-m-d H:i')) }}</small>
                  <div class="text-muted small">{{ $b->supplier->nm_supplier ?? '-' }}</div>
                </div>
                <div class="text-end">
                  <div class="text-muted small">Items: {{ $b->detail->count() }}</div>
                </div>
              </a>
            @endforeach
          </div>
        @endif

      </div>
    </div>
  </div>
</div>
@endsection
