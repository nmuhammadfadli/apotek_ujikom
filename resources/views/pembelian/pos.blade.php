@extends('layouts.admin')
@section('title','POS - Pembelian')

@section('content')
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">POS Pembelian</h3>
        <div><a href="{{ route('pembelian.index') }}" class="btn btn-light">Daftar Pembelian</a></div>
      </div>

      <div class="card-body">
        <div class="row g-2 align-items-end">
          <div class="col-md-6">
            <label class="form-label">Obat</label>
            <select id="select-obat" class="form-select">
              <option value="">-- Pilih Obat --</option>
              @foreach($obats as $o)
                <option value="{{ $o->kd_obat }}" data-stok="{{ $o->stok }}" data-harga="{{ $o->harga_beli }}">
                  {{ $o->nm_obat }} — Rp {{ number_format($o->harga_beli,0,',','.') }} (stok: {{ $o->stok }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Harga Beli</label>
            <!-- editable now -->
            <input id="harga" type="number" step="0.01" class="form-control" />
          </div>

          <div class="col-md-2">
            <label class="form-label">Jumlah</label>
            <input id="jumlah" type="number" class="form-control" value="1" min="1">
          </div>

          <div class="col-md-2">
            <button id="btn-add" class="btn btn-primary w-100">Tambah</button>
          </div>
        </div>

        <hr>

        <div class="table-responsive">
          <table class="table table-sm" id="cart-table">
            <thead>
              <tr>
                <th>#</th>
                <th>Nama Obat</th>
                <th>Harga</th>
                <th>Jumlah</th>
                <th>Subtotal</th>
                <th></th>
              </tr>
            </thead>
            <tbody></tbody>
            <tfoot>
              <tr>
                <td colspan="4" class="text-end">Subtotal</td>
                <td id="subtotal">Rp 0</td>
                <td></td>
              </tr>
              <tr>
                <td colspan="4" class="text-end">Diskon (%)</td>
                <td><input type="number" id="diskon" class="form-control form-control-sm" value="0" min="0" max="100"></td>
                <td></td>
              </tr>
              <tr>
                <td colspan="4" class="text-end"><strong>Grand Total</strong></td>
                <td id="grandtotal"><strong>Rp 0</strong></td>
                <td></td>
              </tr>
            </tfoot>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4">
    <div class="card">
      <div class="card-header"><h3 class="card-title">Simpan Pembelian</h3></div>
      <div class="card-body">
        <form id="form-checkout" method="POST" action="{{ route('pembelian.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Supplier</label>
            <select name="kd_supplier" id="kd_supplier" class="form-select" required>
              <option value="">-- Pilih Supplier --</option>
              @foreach($suppliers as $s)
                <option value="{{ $s->id }}">{{ $s->nm_supplier }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tgl_nota" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
          </div>

          <input type="hidden" name="diskon" id="diskon_form" value="0">
          <input type="hidden" name="items" id="items_input">

          <div class="d-grid gap-2">
            <button id="btn-checkout" class="btn btn-success" type="submit">Simpan Pembelian</button>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  const obats = @json($obats);
  let cart = [];

  function formatRp(n){ return 'Rp ' + Number(n || 0).toLocaleString('id-ID'); }
  function findObat(kd){ return obats.find(o => String(o.kd_obat) === String(kd)); }

  function refreshCart(){
    const $tb = $('#cart-table tbody').empty();
    let subtotal = 0;
    cart.forEach((it, idx) => {
      // ensure numeric
      it.harga_satuan = parseFloat(it.harga_satuan) || 0;
      it.jumlah = parseInt(it.jumlah) || 0;
      const sub = it.jumlah * it.harga_satuan;
      subtotal += sub;

      // render row with editable price input
      $tb.append(`<tr data-kd="${it.kd_obat}">
        <td>${idx+1}</td>
        <td>${it.nm_obat}</td>
        <td>
          <input type="number" step="0.01" class="form-control form-control-sm price-input" data-kd="${it.kd_obat}" value="${it.harga_satuan}" style="width:120px">
        </td>
        <td>
          <input class="form-control form-control-sm qty-input" data-kd="${it.kd_obat}" value="${it.jumlah}" style="width:80px" type="number" min="1">
        </td>
        <td class="text-end">${formatRp(sub)}</td>
        <td><button class="btn btn-sm btn-danger btn-remove" data-kd="${it.kd_obat}">Hapus</button></td>
      </tr>`);
    });
    $('#subtotal').text(formatRp(subtotal));
    const diskon = parseFloat($('#diskon').val() || 0);
    const discountAmount = (diskon>0 && diskon<=100) ? (subtotal * diskon / 100) : 0;
    const grand = Math.max(0, subtotal - discountAmount);
    $('#grandtotal').text(formatRp(grand));
  }

  $(function(){
    // when obat selected -> fill harga (editable)
    $('#select-obat').on('change', function(){
      const kd = $(this).val();
      const item = findObat(kd);
      if(item){
        // set numeric value so user can edit
        $('#harga').val(Number(item.harga_beli));
      } else {
        $('#harga').val('');
      }
    });

    // allow manual editing of #harga; nothing extra required, it's read by add button

    // add to cart
    $('#btn-add').on('click', function(e){
      e.preventDefault();
      const kd = $('#select-obat').val();
      if(!kd){ alert('Pilih obat'); return; }
      const item = findObat(kd);
      if(!item){ alert('Obat tidak ditemukan'); return; }

      const jumlah = parseInt($('#jumlah').val() || 1);
      const harga = parseFloat($('#harga').val());
      if(isNaN(harga) || harga < 0){
        alert('Harga tidak valid');
        return;
      }

      const existing = cart.find(c => String(c.kd_obat) === String(kd));
      if(existing){
        existing.jumlah = (parseInt(existing.jumlah) || 0) + jumlah;
        existing.harga_satuan = harga; // update harga if changed
      } else {
        cart.push({
          kd_obat: kd,
          nm_obat: item.nm_obat,
          harga_satuan: harga,
          jumlah: jumlah
        });
      }
      refreshCart();
    });

    // delegate qty change
    $('#cart-table').on('change', '.qty-input', function(){
      const kd = $(this).data('kd');
      const val = parseInt($(this).val() || 1);
      const c = cart.find(x => String(x.kd_obat) === String(kd));
      if(c){
        c.jumlah = val;
        refreshCart();
      }
    });

    // delegate price change
    $('#cart-table').on('change', '.price-input', function(){
      const kd = $(this).data('kd');
      const val = parseFloat($(this).val() || 0);
      const c = cart.find(x => String(x.kd_obat) === String(kd));
      if(c){
        c.harga_satuan = isNaN(val) ? 0 : val;
        refreshCart();
      }
    });

    // remove
    $('#cart-table').on('click', '.btn-remove', function(){
      const kd = $(this).data('kd');
      cart = cart.filter(x => String(x.kd_obat) !== String(kd));
      refreshCart();
    });

    // diskon
    $('#diskon').on('input change', function(){
      const v = $(this).val();
      $('#diskon_form').val(v);
      refreshCart();
    });

    // on checkout: serialize items to hidden input as JSON
    $('#form-checkout').on('submit', function(e){
      if(cart.length === 0){
        e.preventDefault();
        alert('Cart kosong');
        return;
      }
      // ensure numbers are proper types
      const payload = cart.map(it => ({
        kd_obat: it.kd_obat,
        jumlah: parseInt(it.jumlah) || 0,
        harga_satuan: parseFloat(it.harga_satuan) || 0
      }));
      $('#items_input').val(JSON.stringify(payload));
      // allow submit
    });

    // initial
    refreshCart();
  });
</script>
@endpush
