@include('partials.jadwal-form-component')

<div class="row mb-3">
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
            <label for="tipe">Tipe</label>
            <select class="form-control @error('tipe') is-invalid @enderror" id="tipe" name="tipe" required>
                @foreach(App\Models\Jadwal\TA::TIPE as $tipe)
                    <option value="{{ $tipe }}">{{ $tipe }}</option>
                @endforeach
            </select>
            @error('tipe')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
        <label for="nim">NIM</label>
        <input type="text" class="form-control @error('nim') is-invalid @enderror" id="nim"
            name="nim" value="{{ old('nim') }}" required pattern="\d{8,9}" title="NIM harus 8 atau 9 angka."
            maxlength="9" minlength="8">
        @error('nim')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
    </div>
    <div class="col-md-5 col-sm-12">
        <div class="form-group">
            <label for="nama-mahasiswa">Nama Mahasiswa</label>
            <input type="text" class="form-control @error('nama_mahasiswa') is-invalid @enderror" id="nama-mahasiswa"
                   name="nama_mahasiswa" value="{{ old('nama_mahasiswa') }}" required>
            @error('nama_mahasiswa')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-6 col-sm-12">
        <label for="dosen-pembimbing-1">Dosen Pembimbing 1</label>
        <input type="text" class="form-control @error('dosen_pembimbing_1') is-invalid @enderror"
               name="dosen_pembimbing_1" value="{{ old('dosen_pembimbing_1') }}" required/>
        @error('dosen_pembimbing_1')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>


    <div class="col-md-6 col-sm-12">
        <label for="dosen-pembimbing-2">Dosen Pembimbing 2</label>
        <input type="text" class="form-control @error('dosen_pembimbing_2') is-invalid @enderror"
               name="dosen_pembimbing_2" value="{{ old('dosen_pembimbing_2') }}"/>
        @error('dosen_pembimbing_2')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

</div>

<div class="row mb-3">
    <div class="col-md-12 col-sm-12">
        <label for="judul">Judul</label>
        <input type="text" class="form-control @error('judul') is-invalid @enderror" name="judul"
               value="{{ old('judul') }}" required/>
        @error('judul')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

</div>

<div class="row mb-3">
    <div class="col-md-6 col-sm-12">
        <label for="dosen-penguji-1">Dosen Penguji 1</label>
        <input type="text" class="form-control @error('dosen_pengji_1') is-invalid @enderror" name="dosen_penguji_1"
               value="{{ old('dosen_penguji_1') }}" required/>
        @error('dosen_penguji_1')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-6 col-sm-12">
        <label for="dosen-penguji-2">Dosen Penguji 2</label>
        <input type="text" class="form-control @error('dosen_pengji_2') is-invalid @enderror" name="dosen_penguji_2"
               value="{{ old('dosen_penguji_2') }}"/>
        @error('dosen_penguji_2')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

</div>

<div class="row mb-3">
    <div class="col-md-12 col-sm-12">
        <label for="tautan">Tautan</label>
        <input type="text" class="form-control @error('tautan') is-invalid @enderror" name="tautan"
               value="{{ old('tautan') }}"/>
        @error('tautan')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12 col-sm-12">
        <label for="deskripsi">Deskripsi</label>
        <textarea name="deskripsi" id="" cols="30" rows="5"
                  class="form-control @error('deskripsi') is-invalid @enderror">{{ old('deskripsi') }}</textarea>
        @error('deskripsi')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>
