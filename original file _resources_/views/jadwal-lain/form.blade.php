@include('partials.jadwal-form-component')

<div class="row mb-3">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="tipe">Jenis</label>
            <input class="form-control" name="tipe" value="{{ old('tipe') }}"/>
            @error('tipe')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="dosen">Dosen</label>
            <input type="text" class="form-control @error('dosen') is-invalid @enderror"
                   name="dosen" value="{{ old('dosen') }}" required/>
            @error('dosen')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12 col-sm-12">
        <div class="form-group">
            <label for="mata_kuliah">Deskripsi</label>
            <textarea class="form-control @error('deskripsi') is-invalid @enderror" name="deskripsi">{{ old('deskripsi') }}</textarea>
            @error('mata_kuliah')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>
