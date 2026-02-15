@extends('layouts.admin')
@section('title','Daftar Pembelian')

@section('content')
<div class="card">
  <div class="card-header d-flex justify-content-between align-items-center">
    <h3 class="card-title">Daftar Pembelian</h3>
    <div>
      <a href="{{ route('pembelian.create') }}" class="btn btn-primary">+ Buat Pembelian</a>
    </div>
  </div>

  <div class="card-body">
    <form method="GET" class="mb-3">
      <div class="row g-2">
        <div class="col-md-4">
          <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nota / supplier...">
        </div>
        <div class="col-auto">
          <button class="btn btn-outline-secondary">Cari</button>
          <a href="{{ route('pembelian.index') }}" class="btn btn-light">Reset</a>
        </div>
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-vcenter table-striped">
        <thead>
          <tr>
            <th>Nota</th>
            <th>Tanggal</th>
            <th>Supplier</th>
            <th class="text-end">Total</th>
            <th class="text-center">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pembelians as $p)
            @php
              // jika kamu menyimpan total di kolom, pakai itu. Jika tidak, hitung dari detail:
              $subtotal = $p->detail->sum(function($d){ return $d->jumlah * $d->harga_satuan; });
              $diskon = (float) ($p->diskon ?? 0);
              $discountAmount = ($diskon > 0 && $diskon <= 100) ? ($subtotal * $diskon / 100) : $diskon;
              $total = max(0, $subtotal - $discountAmount);
            @endphp
            <tr>
              <td>{{ $p->nota ?? $p->id }}</td>
              <td>{{ optional($p->tgl_nota)->format ? $p->tgl_nota->format('Y-m-d') : ($p->tgl_nota ?? optional($p->created_at)->format('Y-m-d')) }}</td>
              <td>{{ $p->supplier->nm_supplier ?? '-' }}</td>
              <td class="text-end">Rp {{ number_format($total ?? 0,0,',','.') }}</td>
              <td class="text-center">
                <a href="{{ route('pembelian.show', $p) }}" class="btn btn-sm btn-info">Lihat</a>
                <a href="{{ route('pembelian.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>

                <form action="{{ route('pembelian.destroy', $p) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Hapus pembelian ini? Stok tidak akan otomatis dikembalikan kecuali kamu menangani rollback. Lanjutkan?')">
                  @csrf
                  @method('DELETE')
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

    <div class="mt-3 d-flex justify-content-between align-items-center">
      <div>Menampilkan {{ $pembelians->firstItem() ?? 0 }} - {{ $pembelians->lastItem() ?? 0 }} dari {{ $pembelians->total() ?? 0 }}</div>
      <div>{{ $pembelians->withQueryString()->links() }}</div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
</script>
@endpush
