@extends('layouts.admin')
@section('title','Daftar Penjualan')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between">
    <h3 class="card-title">Daftar Penjualan</h3>
    <div>
      <a href="{{ route('penjualan.create') }}" class="btn btn-primary">+ Buat Penjualan</a>
    </div>
  </div>

  <div class="card-body">
    <form method="GET" class="mb-3">
      <div class="row g-2">
        <div class="col-auto">
          <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nota / supplier...">
        </div>
        <div class="col-auto">
          <button class="btn btn-outline-secondary">Cari</button>
          <a href="{{ route('penjualan.index') }}" class="btn btn-light">Reset</a>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-vcenter table-striped">
        <thead>
          <tr>
            <th>Nota</th>
            <th>Tanggal</th>
            <th>Partner</th>
            <th>Total</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($penjualans as $p)
            <tr>
              <td>{{ $p->nota ?? $p->id }}</td>
              <td>{{ $p->tgl_penjualan ?? $p->tgl_nota ?? $p->created_at->format('Y-m-d') }}</td>
              <td>{{ $p->supplier->nm_supplier ?? $p->pelanggan->nm_pelanggan ?? '-' }}</td>
              <td>Rp {{ number_format($p->total_harga ?? ($p->detail->sum(fn($d)=> $d->jumlah * $d->harga_satuan) ?? 0),0,',','.') }}</td>
              <td>
                <a href="{{ route('penjualan.show', $p) }}" class="btn btn-sm btn-info">Lihat</a>
                <a href="{{ route('penjualan.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>
                <form action="{{ route('penjualan.destroy', $p) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus penjualan?')">
                  @csrf @method('DELETE')
                  <button class="btn btn-sm btn-danger">Hapus</button>
                </form>
              </td>
            </tr>
          @empty
            <tr><td colspan="5" class="text-center">Tidak ada data</td></tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3 d-flex justify-content-between">
      <div>Menampilkan {{ $penjualans->firstItem() ?? 0 }} - {{ $penjualans->lastItem() ?? 0 }} dari {{ $penjualans->total() ?? 0 }}</div>
      <div>{{ $penjualans->withQueryString()->links() }}</div>
    </div>
  </div>
</div>
@endsection
