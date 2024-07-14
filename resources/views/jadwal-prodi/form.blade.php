@include('partials.jadwal-form-component')

<div class="row mb-3">
    <div class="col-md-4 col-sm-12">
        <div class="form-group">
            <label for="tipe">Kode Mata Kuliah</label>
            <input class="form-control" name="tipe" value="{{ old('tipe') }}" @cannot('profile.edit') readonly @endcannot/>
            @error('tipe')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-8 col-sm-12">
        <div class="form-group">
            <label for="mata-kuliah">Mata Kuliah</label>
            <input type="text" class="form-control @error('mata_kuliah') is-invalid @enderror"
                   name="mata_kuliah" value="{{ old('mata_kuliah') }}" @cannot('profile.edit') readonly @endcannot required/>
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
               name="kelas" value="{{ old('kelas') }}" @cannot('profile.edit') readonly @endcannot required/>
        @error('kelas')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>

    <div class="col-md-6 col-sm-12">
        <label for="sks">SKS</label>
        <input type="number" min="1" max="4" class="form-control @error('sks') is-invalid @enderror"
            name="sks" id="sks" value="{{ old('sks') }}" @cannot('profile.edit') readonly @endcannot required/>
        @error('sks')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>


    <div class="col-md-6 col-sm-12">
        <label for="semester">Semester</label>
        <input type="text" class="form-control @error('semester') is-invalid @enderror"
               name="semester" value="{{ old('semester') }}" @cannot('profile.edit') readonly @endcannot required/>
        @error('semester')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-12 col-sm-12">
        <label for="dosen">Dosen</label>
        <input type="text" class="form-control @error('dosen') is-invalid @enderror"
               name="dosen" value="{{ old('dosen') }}" @cannot('profile.edit') readonly @endcannot required/>
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

<!-- @push('scripts')
<script>
        document.addEventListener('DOMContentLoaded', function () {
        const waktuMulaiInput = document.getElementById('waktu-mulai');
        const sksInput = document.getElementById('sks');
        const waktuSelesaiInput = document.getElementById('waktu-selesai');
        const routeName = window.location.pathname;

        if (routeName.includes('jadwal-prodi')) {
            function calculateWaktuSelesai() {
                const waktuMulai = waktuMulaiInput.value;
                let sks = parseInt(sksInput.value);

                if (waktuMulai && sks) {
                    // Treat sks value 4 the same as 2
                    if (sks === 4) {
                        sks = 2;
                    }

                    const waktuMulaiDate = new Date(`1970-01-01T${waktuMulai}:00`);
                    const minutesToAdd = sks * 50;
                    waktuMulaiDate.setMinutes(waktuMulaiDate.getMinutes() + minutesToAdd);

                    const hours = String(waktuMulaiDate.getHours()).padStart(2, '0');
                    const minutes = String(waktuMulaiDate.getMinutes()).padStart(2, '0');
                    waktuSelesaiInput.value = `${hours}:${minutes}`;
                }
            }

            // Initial calculation in case of pre-filled values
            calculateWaktuSelesai();

            // Set up event listeners
            waktuMulaiInput.addEventListener('change', calculateWaktuSelesai);
            sksInput.addEventListener('change', calculateWaktuSelesai);
        }
    });
</script>
@endpush -->