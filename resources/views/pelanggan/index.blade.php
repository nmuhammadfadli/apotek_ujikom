@extends('layouts.admin')

@section('title','Daftar Pelanggan')

@section('content')
<div class="row row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Pelanggan</h3>
        <div>
          <a href="{{ route('pelanggan.create') }}" class="btn btn-primary">+ Tambah Pelanggan</a>
        </div>
      </div>

      <div class="card-body">
        <form method="GET" class="mb-3" action="{{ route('pelanggan.index') }}">
          <div class="row g-2">
            <div class="col-auto">
              <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama pelanggan...">
            </div>
            <div class="col-auto">
              <button class="btn btn-outline-secondary">Cari</button>
              <a href="{{ route('pelanggan.index') }}" class="btn btn-light">Reset</a>
            </div>
          </div>
        </form>

        <div class="table-responsive">
          <table id="tbl-pelanggan" class="table table-vcenter table-nowrap">
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
              @foreach($pelanggans as $p)
              <tr>
                <td>{{ $p->kd_pelanggan }}</td>
                <td>{{ $p->nm_pelanggan }}</td>
                <td>{{ $p->alamat }}</td>
                <td>{{ $p->kota }}</td>
                <td>{{ $p->telpon }}</td>
                <td class="table-actions">
                  <a href="{{ route('pelanggan.edit', $p) }}" class="btn btn-sm btn-warning">Edit</a>

                  <form action="{{ route('pelanggan.destroy', $p) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus pelanggan ini?')">
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
          <div>Menampilkan {{ $pelanggans->firstItem() ?? 0 }} - {{ $pelanggans->lastItem() ?? 0 }} dari {{ $pelanggans->total() }} data</div>
          <div>{{ $pelanggans->withQueryString()->links() }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
//   $(document).ready(function(){
//     // Optional enhancement: DataTables untuk client-search / sort
//     $('#tbl-pelanggan').DataTable({
//       paging: false,
//       info: false,
//       searching: false,
//       columnDefs: [{ orderable: false, targets: -1 }]
//     });
//   });
</script>
@endpush
