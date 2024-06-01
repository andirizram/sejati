<style>
    .switch_box {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        max-width: 200px;
        min-width: 200px;
        height: 200px;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }


    input[type="checkbox"].switch_1 {
        font-size: 14px;
        -webkit-appearance: none;
        -moz-appearance: none;
        appearance: none;
        width: 3.5em;
        height: 1.5em;
        background: #ddd;
        border-radius: 3em;
        position: relative;
        cursor: pointer;
        outline: none;
        -webkit-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;
    }

    input[type="checkbox"].switch_1:checked {
        background: #007dea;
    }

    input[type="checkbox"].switch_1:after {
        position: absolute;
        content: "";
        width: 1.5em;
        height: 1.5em;
        border-radius: 50%;
        background: #fff;
        -webkit-box-shadow: 0 0 .25em rgba(0, 0, 0, .3);
        box-shadow: 0 0 .25em rgba(0, 0, 0, .3);
        -webkit-transform: scale(.7);
        transform: scale(.7);
        left: 0;
        -webkit-transition: all .2s ease-in-out;
        transition: all .2s ease-in-out;
    }

    input[type="checkbox"].switch_1:checked:after {
        left: calc(100% - 1.5em);
    }

</style>

<div class="row mb-3">
    <div class="col-md-6 col-sm-12">
        <div class="form-group">
            <label for="pengulangan">Apakah jadwal berulang?</label>
            <div>
                <input type="checkbox" name="pengulangan" class="switch_1" id="pengulangan"
                    {{ old('pengulangan') == true ? 'checked' :'' }} @cannot('profile.edit') disabled @endcannot>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <div class="form-group" id="tanggal-container">
            <label for="tanggal">Tanggal</label>
            <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal"
                   name="tanggal" value="{{ old('tanggal') }}" @cannot('profile.edit') readonly @endcannot>
            @error('tanggal')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
        <div class="form-group d-none" id="hari-container">
            <label for="hari">Hari</label>
            <select class="form-control @error('hari') is-invalid @enderror" id="hari"
                    name="hari" @cannot('profile.edit') disabled @endcannot>
                <option value="">--Pilih Hari--</option>
                @foreach(\App\Models\Jadwal\Jadwal::DAY as $key => $businnes_day)
                    <option
                        value="{{ $businnes_day }}" {{ old('hari') == $businnes_day ? 'selected' :'' }}>{{ $businnes_day }}</option>
                @endforeach
            </select>
            @error('hari')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="waktu-mulai">Waktu Mulai</label>
            <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror" id="waktu-mulai"
                   name="waktu_mulai" value="{{ old('waktu_mulai') }}" @cannot('profile.edit') readonly @endcannot required/>
            @error('waktu_mulai')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-3 col-sm-12">
        <div class="form-group">
            <label for="waktu-selesai">Waktu Selesai</label>
            <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror" id="waktu-selesai"
                   name="waktu_selesai" value="{{ old('waktu_selesai') }}" @cannot('profile.edit') readonly @endcannot required>
            @error('waktu_selesai')
            <small class="text-danger">{{ $message }}</small>
            @enderror
        </div>
    </div>

    <div class="col-md-6 col-sm-12">
        <label for="ruangan">Ruangan</label>
        <input type="text" class="form-control @error('ruangan') is-invalid @enderror" id="ruangan" name="ruangan"
               value="{{ old('ruangan') }}" @cannot('profile.edit') readonly @endcannot required/>
        @error('ruangan')
        <small class="text-danger">{{ $message }}</small>
        @enderror
    </div>
</div>


@pushonce('jadwal-form-script')
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

    <script type="module">
        function showContainerHari() {
            $('#tanggal-container').addClass('d-none');
            $('#hari-container').removeClass('d-none');
        }

        function showContainerTanggal() {
            $('#tanggal-container').removeClass('d-none');
            $('#hari-container').addClass('d-none');
        }

        $(document).ready(function () {
            const initialPengulangan = @json(old('pengulangan') == true);

            if (initialPengulangan) {
                showContainerHari()
            } else {
                showContainerTanggal()
            }

            $('#pengulangan').change(function () {
                if (this.checked) {
                    showContainerHari()
                } else {
                    showContainerTanggal()
                }
            });

            $('#modal').on('show.bs.modal', function (event) {
                let button = $(event.relatedTarget);
                let url = button.data('url');
                let action = button.data('action');


                if (jadwal == null) {
                    return;
                }
                let dataJadwal = jadwal;

                $('#modal').find('form').attr('action', action);

                dataJadwal.pengulangan ?
                    $('#modal').find('input[name="pengulangan"]').prop('checked', true) :
                    $('#modal').find('input[name="pengulangan"]').prop('checked', false);

                if (dataJadwal.pengulangan) {
                    $('#modal #tanggal-container').addClass('d-none');
                    $('#modal #hari-container').removeClass('d-none');
                    $('#modal').find('select[name="hari"]').val(dataJadwal.hari).trigger('change')
                } else {
                    $('#tanggal').val(dataJadwal.tanggal).trigger('change')
                    $('#modal #tanggal-container').removeClass('d-none');
                    $('#hari-container').addClass('d-none');
                }

                $('#modal').find('input[name="waktu_mulai"]').val(dataJadwal.waktu_mulai);
                $('#modal').find('input[name="waktu_selesai"]').val(dataJadwal.waktu_selesai);
                $('#modal').find('input[name="ruangan"]').val(dataJadwal.ruangan);
            });

        });
    </script>
@endpushonce
