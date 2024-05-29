@include('partials.jadwal-form-component')

<div class="row mb-3">
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
            <label for="tipe">Kode Mata Kuliah</label>
            <input class="form-control" name="tipe" value="{{ old('tipe') }}"/>
            @error('tipe')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-8 col-sm-12">
        <div class="form-group">
            <label for="mata-kuliah">Mata Kuliah</label>
            <input type="text" class="form-control @error('mata_kuliah') is-invalid @enderror"
                   name="mata_kuliah" value="{{ old('mata_kuliah') }}" required/>
            @error('mata_kuliah')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6 col-sm-12">
        <label for="kelas">Kelas</label>
        <input type="text" class="form-control @error('kelas') is-invalid @enderror"
               name="kelas" value="{{ old('kelas') }}" required/>
        @error('kelas')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-3 col-sm-12">
        <label for="sks">SKS</label>
        <input type="number" min="0" class="form-control @error('sks') is-invalid @enderror"
               name="sks" value="{{ old('sks') }}" required/>
        @error('sks')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>


    <div class="col-md-3 col-sm-12">
        <label for="semester">Semester</label>
        <input type="number" class="form-control @error('semester') is-invalid @enderror"
               name="semester" value="{{ old('semester') }}" required/>
        @error('semester')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12 col-sm-12">
        <label for="dosen">Dosen</label>
        <input type="text" class="form-control @error('dosen') is-invalid @enderror"
               name="dosen" value="{{ old('dosen') }}" required/>
        @error('dosen')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12 col-sm-12">
        <label for="deskripsi">Deskripsi</label>
        <textarea class="form-control @error('deskripsi') is-invalid @enderror"
                  name="deskripsi">{{ old('deskripsi') }}</textarea>
        @error('deskripsi')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
