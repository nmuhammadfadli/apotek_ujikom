@extends('layouts.admin')
@section('title','Penjualan: ' . ($penjualan->nota ?? $penjualan->id))

@section('content')
<div class="row">
  <div class="col-12 mb-3">
    <a href="{{ route('penjualan.index') }}" class="btn btn-light">← Kembali ke Daftar Penjualan</a>
    <a href="{{ route('penjualan.edit', $penjualan) }}" class="btn btn-warning">Edit Header</a>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Rincian Nota</h3></div>
      <div class="card-body">
        <dl class="row">
          <dt class="col-5">Nota</dt>
          <dd class="col-7">{{ $penjualan->nota }}</dd>

          <dt class="col-5">Tanggal</dt>
          <dd class="col-7">{{ \Carbon\Carbon::parse($penjualan->tgl_nota)->format('Y-m-d') }}</dd>

          <dt class="col-5">Pelanggan</dt>
          <dd class="col-7">{{ $penjualan->pelanggan->nm_pelanggan ?? 'Umum / Tunai' }}</dd>

          <dt class="col-5">Diskon</dt>
          <dd class="col-7">{{ $penjualan->diskon ? $penjualan->diskon . ( $penjualan->diskon <= 100 ? '%' : ' (nominal)') : '0' }}</dd>

          <dt class="col-5">Subtotal</dt>
          <dd class="col-7">Rp {{ number_format($subtotal,0,',','.') }}</dd>

          <dt class="col-5">Diskon (Rp)</dt>
          <dd class="col-7">Rp {{ number_format($discountAmount,0,',','.') }}</dd>

          <dt class="col-5"><strong>Total</strong></dt>
          <dd class="col-7"><strong>Rp {{ number_format($total,0,',','.') }}</strong></dd>
        </dl>
      </div>
    </div>
  </div>

  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Detail Item</h3>
        <div class="text-muted small">Nota: <strong>{{ $penjualan->nota }}</strong></div>
      </div>

      <div class="card-body">
        <div class="table-responsive">
          <table class="table table-sm table-hover">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Obat</th>
                <th class="text-end">Harga</th>
                <th class="text-center">Jumlah</th>
                <th class="text-end">Subtotal</th>
              </tr>
            </thead>
            <tbody>
              @forelse($penjualan->detail as $i => $d)
                <tr>
                  <td>{{ $i + 1 }}</td>
                  <td>{{ $d->obat->nm_obat ?? ('#'.$d->kd_obat) }}</td>
                  <td class="text-end">Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                  <td class="text-center">{{ $d->jumlah }}</td>
                  <td class="text-end">Rp {{ number_format($d->jumlah * $d->harga_satuan,0,',','.') }}</td>
                </tr>
              @empty
                <tr>
                  <td colspan="5" class="text-center">Belum ada item</td>
                </tr>
              @endforelse
            </tbody>
          </table>
        </div>

        <div class="mt-3 d-flex justify-content-end">
          <a href="{{ route('penjualan.index') }}" class="btn btn-secondary me-2">Tutup</a>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
