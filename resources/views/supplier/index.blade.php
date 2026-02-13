@extends('layouts.admin')

@section('title','Daftar Supplier')

@section('content')
<div class="row row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Supplier</h3>
        <div>
          <a href="{{ route('supplier.create') }}" class="btn btn-primary">+ Tambah Supplier</a>
        </div>
      </div>

      <div class="card-body">
        <form method="GET" class="mb-3" action="{{ route('supplier.index') }}">
          <div class="row g-2">
            <div class="col-auto">
              <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama supplier...">
            </div>
            <div class="col-auto">
              <button class="btn btn-outline-secondary">Cari</button>
              <a href="{{ route('supplier.index') }}" class="btn btn-light">Reset</a>
            </div>
          </div>
        </form>

        <div class="table-responsive">
          <table id="tbl-supplier" class="table table-vcenter table-nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Alamat</th>
                <th>Kota</th>
                <th>Telepon</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($suppliers as $o)
              <tr>
                <td>{{ $o->kd_supplier }}</td>
                <td>{{ $o->nm_supplier }}</td>
                <td>{{ $o->alamat }}</td>
                <td>{{ $o->kota }}</td>
                <td>{{ $o->telpon }}</td>
                <td class="table-actions">
                  <a href="{{ route('supplier.edit', $o) }}" class="btn btn-sm btn-warning">Edit</a>

                  <form action="{{ route('supplier.destroy', $o) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus supplier ini?')">
                    @csrf
                    @method('DELETE')
                    <button class="btn btn-sm btn-danger">Hapus</button>
                  </form>
                </td>
              </tr>
              @endforeach
            </tbody>
          </table>
        </div>

        <div class="mt-3 d-flex justify-content-between align-items-center">
          <div>Menampilkan {{ $suppliers->firstItem() ?? 0 }} - {{ $suppliers->lastItem() ?? 0 }} dari {{ $suppliers->total() }} data</div>
          <div>{{ $suppliers->withQueryString()->links() }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  $(document).ready(function(){
    // Optional enhancement: DataTables untuk client-search / sort
    $('#tbl-supplier').DataTable({
      paging: false,
      info: false,
      searching: false,
      columnDefs: [{ orderable: false, targets: -1 }]
    });
  });
</script>
@endpush
