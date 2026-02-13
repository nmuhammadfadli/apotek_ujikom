@extends('layouts.admin')
@section('title','Tambah Obat')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Tambah Obat</h3>
        <a href="{{ route('obat.index') }}" class="btn btn-light">Kembali</a>
      </div>
      <div class="card-body">
        <form action="{{ route('obat.store') }}" method="POST">
          @include('obat._form')
          <div class="mt-3">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('obat.index') }}" class="btn btn-link">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
