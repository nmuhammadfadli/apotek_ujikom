@extends('layouts.admin')
@section('title','POS - Penjualan')

@section('content')
<div class="row">
  <div class="col-lg-8">
    <div class="card">
      <div class="card-header d-flex justify-content-between">
        <h3 class="card-title">Point of Sale</h3>
        <div>
          <a href="{{ route('penjualan.index') }}" class="btn btn-light">Daftar Nota</a>
        </div>
      </div>

      <div class="card-body">
        <!-- Input obat -->
        <div class="row g-2 align-items-end">
          <div class="col-md-6">
            <label class="form-label">Cari / Pilih Obat</label>
            <select id="select-obat" class="form-select">
              <option value="">-- Pilih Obat --</option>
              @foreach($obats as $o)
                <option value="{{ $o->kd_obat }}" data-stok="{{ $o->stok }}" data-harga="{{ $o->harga_jual }}">
                  {{ $o->nm_obat }} — Rp {{ number_format($o->harga_jual,0,',','.') }} (stok: {{ $o->stok }})
                </option>
              @endforeach
            </select>
          </div>

          <div class="col-md-2">
            <label class="form-label">Harga</label>
            <input id="harga" class="form-control" readonly>
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

        <!-- Cart table -->
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
      <div class="card-header"><h3 class="card-title">Checkout</h3></div>
      <div class="card-body">

        <form id="form-checkout" method="POST" action="{{ route('penjualan.store') }}">
          @csrf
          <div class="mb-3">
            <label class="form-label">Pelanggan</label>
            <select name="kd_pelanggan" id="kd_pelanggan" class="form-select">
              <option value="">-- Umum / Tunai --</option>
              @foreach($pelanggans as $p)
                <option value="{{ $p->kd_pelanggan }}">{{ $p->nm_pelanggan }}</option>
              @endforeach
            </select>
          </div>

          <div class="mb-3">
            <label class="form-label">Tanggal</label>
            <input type="date" name="tgl_nota" class="form-control" value="{{ now()->format('Y-m-d') }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label">Diskon (%)</label>
            <input id="diskon_form" type="hidden" name="diskon" value="0">
          </div>

          <!-- items akan di-serialize ke input hidden sebelum submit -->
          <input type="hidden" name="items" id="items_input">

          <div class="d-grid gap-2">
            <button id="btn-checkout" class="btn btn-success" type="submit">Simpan & Cetak (Checkout)</button>
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
  let cart = []; // array of {kd_obat, nm_obat, harga_satuan, jumlah}

  function formatRp(n){
    return 'Rp ' + Number(n || 0).toLocaleString('id-ID');
  }

  function findObat(kd){
    return obats.find(o => String(o.kd_obat) === String(kd));
  }

  function refreshCart(){
    const $tb = $('#cart-table tbody').empty();
    let subtotal = 0;
    cart.forEach((it, idx) => {
      const sub = it.jumlah * it.harga_satuan;
      subtotal += sub;
      $tb.append(`<tr data-kd="${it.kd_obat}">
        <td>${idx+1}</td>
        <td>${it.nm_obat}</td>
        <td>${formatRp(it.harga_satuan)}</td>
        <td><input class="form-control form-control-sm qty-input" data-kd="${it.kd_obat}" value="${it.jumlah}" style="width:80px"></td>
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
    // when obat selected -> fill harga
    $('#select-obat').on('change', function(){
      const kd = $(this).val();
      const item = findObat(kd);
      if(item){
        $('#harga').val(item.harga_jual);
      } else {
        $('#harga').val('');
      }
    });

    // add to cart
    $('#btn-add').on('click', function(e){
      e.preventDefault();
      const kd = $('#select-obat').val();
      if(!kd){ alert('Pilih obat'); return; }
      const item = findObat(kd);
      const jumlah = parseInt($('#jumlah').val() || 1);
      if(item.stok < jumlah){ alert('Stok tidak cukup'); return; }
      const harga = parseFloat($('#harga').val() || item.harga_jual);

      const existing = cart.find(c => String(c.kd_obat) === String(kd));
      if(existing){
        existing.jumlah = parseInt(existing.jumlah) + jumlah;
      } else {
        cart.push({kd_obat: kd, nm_obat: item.nm_obat, harga_satuan: harga, jumlah: jumlah});
      }
      refreshCart();
    });

    // change qty in cart (delegate)
    $('#cart-table').on('change', '.qty-input', function(){
      const kd = $(this).data('kd');
      const val = parseInt($(this).val() || 1);
      const c = cart.find(x => String(x.kd_obat) === String(kd));
      if(c){
        if(val <=0) { $(this).val(c.jumlah); return; }
        c.jumlah = val;
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
      // set hidden input items as JSON string; backend will accept JSON if you decode manually
      $('#items_input').val(JSON.stringify(cart));
      // let the form submit normally (POST). Controller expects items as array (Laravel auto-decodes if content-type application/json
      // but we are submitting as form-data string -> decode in controller: json_decode($request->input('items'), true)
    });

    // initial refresh
    refreshCart();
  });
</script>
@endpush
