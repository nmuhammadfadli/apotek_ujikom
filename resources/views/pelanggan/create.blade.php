@extends('layouts.admin')
@section('title','Tambah Pelanggan')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Tambah Pelanggan</h3>
        <a href="{{ route('pelanggan.index') }}" class="btn btn-light">Kembali</a>
      </div>
      <div class="card-body">
        <form action="{{ route('pelanggan.store') }}" method="POST">
          @include('pelanggan._form')
          <div class="mt-3">
            <button class="btn btn-primary">Simpan</button>
            <a href="{{ route('pelanggan.index') }}" class="btn btn-link">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
