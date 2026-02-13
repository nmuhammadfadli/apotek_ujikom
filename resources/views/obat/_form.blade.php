{{-- partial form untuk create & edit obat --}}
@csrf

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nama Obat <span class="text-danger">*</span></label>
    <input type="text" name="nm_obat" class="form-control @error('nm_obat') is-invalid @enderror"
           value="{{ old('nm_obat', $obat->nm_obat ?? '') }}" required>
    @error('nm_obat') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Jenis</label>
    <input type="text" name="jenis" class="form-control @error('jenis') is-invalid @enderror"
           value="{{ old('jenis', $obat->jenis ?? '') }}">
    @error('jenis') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-3">
    <label class="form-label">Satuan</label>
    <input type="text" name="satuan" class="form-control @error('satuan') is-invalid @enderror"
           value="{{ old('satuan', $obat->satuan ?? '') }}">
    @error('satuan') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Harga Beli</label>
    <input type="number" step="0.01" name="harga_beli" class="form-control @error('harga_beli') is-invalid @enderror"
           value="{{ old('harga_beli', isset($obat) ? $obat->harga_beli : '') }}">
    @error('harga_beli') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Harga Jual <span class="text-danger">*</span></label>
    <input type="number" step="0.01" name="harga_jual" class="form-control @error('harga_jual') is-invalid @enderror"
           value="{{ old('harga_jual', isset($obat) ? $obat->harga_jual : '') }}" required>
    @error('harga_jual') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-2">
    <label class="form-label">Stok</label>
    <input type="number" name="stok" class="form-control @error('stok') is-invalid @enderror"
           value="{{ old('stok', isset($obat) ? $obat->stok : 0) }}">
    @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Supplier</label>
    <select name="kd_supplier" class="form-select @error('kd_supplier') is-invalid @enderror">
      <option value="">-- Pilih Supplier --</option>
      @foreach(($suppliers ?? []) as $s)
<option value="{{ $s->id }}"
    {{ (string) old('kd_supplier', $obat->kd_supplier ?? '') === (string) $s->id ? 'selected' : '' }}>
    {{ $s->nm_supplier }}
</option>

      @endforeach
    </select>
    @error('kd_supplier') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>
