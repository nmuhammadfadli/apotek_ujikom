@extends('layouts.admin')

@section('title','Daftar Obat')

@section('content')
<div class="row row-cards">
  <div class="col-12">
    <div class="card">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h3 class="card-title">Daftar Obat</h3>
        <div>
          <a href="{{ route('obat.create') }}" class="btn btn-primary">+ Tambah Obat</a>
        </div>
      </div>

      <div class="card-body">
        <form method="GET" class="mb-3" action="{{ route('obat.index') }}">
          <div class="row g-2">
            <div class="col-auto">
              <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Cari nama obat...">
            </div>
            <div class="col-auto">
              <button class="btn btn-outline-secondary">Cari</button>
              <a href="{{ route('obat.index') }}" class="btn btn-light">Reset</a>
            </div>
          </div>
        </form>

        <div class="table-responsive">
          <table id="tbl-obat" class="table table-vcenter table-nowrap">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama</th>
                <th>Jenis</th>
                <th>Satuan</th>
                <th>Harga Jual</th>
                <th>Stok</th>
                <th>Aksi</th>
              </tr>
            </thead>
            <tbody>
              @foreach($obats as $o)
              <tr>
                <td>{{ $o->kd_obat }}</td>
                <td>{{ $o->nm_obat }}</td>
                <td>{{ $o->jenis }}</td>
                <td>{{ $o->satuan }}</td>
                <td>Rp {{ number_format($o->harga_jual,0,',','.') }}</td>
                <td>{{ $o->stok }}</td>
                <td class="table-actions">
                  <a href="{{ route('obat.edit', $o) }}" class="btn btn-sm btn-warning">Edit</a>

                  <form action="{{ route('obat.destroy', $o) }}" method="POST" style="display:inline" onsubmit="return confirm('Hapus obat ini?')">
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
          <div>Menampilkan {{ $obats->firstItem() ?? 0 }} - {{ $obats->lastItem() ?? 0 }} dari {{ $obats->total() }} data</div>
          <div>{{ $obats->withQueryString()->links() }}</div>
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
    $('#tbl-obat').DataTable({
      paging: false,
      info: false,
      searching: false,
      columnDefs: [{ orderable: false, targets: -1 }]
    });
  });
</script>
@endpush
