@csrf
<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nama Pelanggan <span class="text-danger">*</span></label>
    <input type="text" name="nm_pelanggan" class="form-control @error('nm_pelanggan') is-invalid @enderror"
           value="{{ old('nm_pelanggan', $pelanggan->nm_pelanggan ?? '') }}" required>
    @error('nm_pelanggan') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Kota</label>
    <input type="text" name="kota" class="form-control @error('kota') is-invalid @enderror"
           value="{{ old('kota', $pelanggan->kota ?? '') }}">
    @error('kota') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-12">
    <label class="form-label">Alamat</label>
    <textarea name="alamat" class="form-control @error('alamat') is-invalid @enderror" rows="3">{{ old('alamat', $pelanggan->alamat ?? '') }}</textarea>
    @error('alamat') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Telepon</label>
    <input type="text" name="telpon" class="form-control @error('telpon') is-invalid @enderror"
           value="{{ old('telpon', $pelanggan->telpon ?? '') }}">
    @error('telpon') <div class="invalid-feedback">{{ $message }}</div> @enderror
  </div>
</div>
