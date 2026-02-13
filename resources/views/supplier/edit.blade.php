@extends('layouts.admin')
@section('title','Edit Supplier')

@section('content')
<div class="row">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Edit Supplier</h3>
        <a href="{{ route('supplier.index') }}" class="btn btn-light">Kembali</a>
      </div>
      <div class="card-body">
        <form action="{{ route('supplier.update', $supplier) }}" method="POST">
          @method('PUT')
          @include('supplier._form')
          <div class="mt-3">
            <button class="btn btn-primary">Perbarui</button>
            <a href="{{ route('supplier.index') }}" class="btn btn-link">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
