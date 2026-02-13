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
@endsection