@extends('layouts.admin')
@section('title','Pembelian: ' . ($pembelian->nota ?? $pembelian->id))

@section('content')
<div class="row">
  <div class="col-md-4">
    <div class="card">
      <div class="card-body">
        <h5>Nota: {{ $pembelian->nota }}</h5>
        <p>Tanggal: {{ $pembelian->tgl_nota }}</p>
        <p>Supplier: {{ $pembelian->supplier->nm_supplier ?? '-' }}</p>
        <p>Diskon: {{ $pembelian->diskon }}</p>
      </div>
    </div>
  </div>

  <div class="col-md-8">
    <div class="card">
      <div class="card-body">
        <table class="table">
          <thead><tr><th>#</th><th>Obat</th><th>Harga</th><th>Jumlah</th><th>Subtotal</th></tr></thead>
          <tbody>
            @foreach($pembelian->detail as $i => $d)
              <tr>
                <td>{{ $i+1 }}</td>
                <td>{{ $d->obat->nm_obat ?? $d->kd_obat }}</td>
                <td>Rp {{ number_format($d->harga_satuan,0,',','.') }}</td>
                <td>{{ $d->jumlah }}</td>
                <td>Rp {{ number_format($d->harga_satuan * $d->jumlah,0,',','.') }}</td>
              </tr>
            @endforeach
          </tbody>
        </table>
        <div class="text-end">
          <strong>Subtotal: Rp {{ number_format($subtotal,0,',','.') }}</strong><br>
          <strong>Total: Rp {{ number_format($total,0,',','.') }}</strong>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
