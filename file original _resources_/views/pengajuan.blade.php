@extends('layouts.app')

@php
    use App\Models\PerubahanJadwal;
    function printStatusBadge($status) {
        switch($status) {
            case 0:
                return '<div class="badge bg-primary">'. PerubahanJadwal::STATUS[$status] .'</div>';
            case 1:
                return '<div class="badge bg-success">'. PerubahanJadwal::STATUS[$status] .'</div>';
            case -1:
                return '<div class="badge bg-danger">'. PerubahanJadwal::STATUS[$status] .'</div>';
        }
    }
@endphp
@section('content')
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

        .is-invalid .select2-selection,
        .needs-validation ~ span > .select2-dropdown {
            border-color: red !important;
        }

    </style>
    <div class="box-body">
        <h4>PENGAJUAN PERUBAHAN JADWAL</h4>
        <div class="row">
            <div class="col-md-6">
                <form action="{{ route('perubahan-jadwal.store') }}" method="POST" novalidate>
                    @csrf
                    <div class="form-horizontal">
                        <div class="form-group">
                        <select name="jadwal_id" id="jadwal-id" class="form-control select2">
                            <option value="">--Pilih Jadwal--</option>
                            @foreach($jadwals as $jadwal)
                            <option value="{{ $jadwal['id'] }}" {{ old('jadwal_id') == $jadwal['id'] ? 'selected' : '' }}>
                                {{ $jadwal['tipe'] . ' - ' . $jadwal['name'] }}
                            </option>
                            @endforeach
                        </select>
                            @error('jadwal_id')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6 col-sm-12 column-pengulangan">
                                <div class="form-group">
                                    <label for="pengulangan">Apakah jadwal berulang?</label>
                                    <div>
                                        <input type="checkbox" name="pengulangan" class="switch_1" id="pengulangan"
                                            {{ old('pengulangan') == true ? 'checked' :'' }}>
                                        @error('pengulangan')
                                        <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <div class="form-group" id="tanggal-container">
                                    <label for="tanggal">Tanggal</label>
                                    <input type="date" class="form-control @error('tanggal') is-invalid @enderror"
                                           id="tanggal"
                                           value="{{ old('tanggal') }}"
                                           name="tanggal">
                                    @error('tanggal')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <div class="form-group d-none" id="hari-container">
                                    <label for="hari">Hari</label>
                                    <select type="date" class="form-control @error('hari') is-invalid @enderror"
                                            id="hari"
                                            name="hari">
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
                                    <input type="time" class="form-control @error('waktu_mulai') is-invalid @enderror"
                                           id="waktu-mulai"
                                           name="waktu_mulai" value="{{ old('waktu_mulai') }}" required/>
                                    @error('waktu_mulai')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-3 col-sm-12">
                                <div class="form-group">
                                    <label for="waktu-selesai">Waktu Selesai</label>
                                    <input type="time" class="form-control @error('waktu_selesai') is-invalid @enderror"
                                           id="waktu-selesai"
                                           name="waktu_selesai" value="{{ old('waktu_selesai') }}" required>
                                    @error('waktu_selesai')
                                    <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6 col-sm-12">
                                <label for="ruangan">Ruangan</label>
                                <input type="text" class="form-control @error('ruangan') is-invalid @enderror"
                                       id="ruangan" name="ruangan"
                                       value="{{ old('ruangan') }}"
                                       required/>
                                @error('ruangan')
                                <small class="text-danger">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                        <div class="form-group">
                            <textarea id="alasan" class="form-control @error('alasan') is-invalid @enderror"
                                      name="alasan" rows="4"
                                      cols="69"
                                      placeholder="  Alasan mengajukan perubahan...">{{ old('alasan') }}</textarea>
                            @error('alasan')
                            <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success pull-left"><i class="fa fa-save"></i> Kirim
                                Permintaan
                            </button>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-md-6">

            </div>
        </div>

        <div class="row">
            <div class="col-md-12">
                <h4>Status Pengajuan</h4>
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Jadwal yang dirubah</th>
                        <th>Alasan</th>
                        <th>Status Pengajuan</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pengajuans as $pengajuan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pengajuan['name'] ?? $pengajuan['tipe'] }}</td> <!-- If name is null, tipe is displayed -->
                            <td>{{ $pengajuan['alasan'] }}</td>
                            <td class="text-center">
                                {!! printStatusBadge($pengajuan['status']) !!}
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr/>
    </div>

@endsection

@section('script')
    <script type="module">
        function showContainerHari() {
            $('#tanggal-container').addClass('d-none');
            $('#hari-container').removeClass('d-none');
        }

        function showContainerTanggal() {
            $('#tanggal-container').removeClass('d-none');
            $('#hari-container').addClass('d-none');
        }

        window.fillCurrentValue = async (id) => {
            const response = await fetch(@json(URL::to('/jadwal')) +'/' + id);
            const jadwal = await response.json();

            jadwal.tipe == 'TA' ?
                $('.column-pengulangan').addClass('d-none') :
                $('.column-pengulangan').removeClass('d-none')

            jadwal.pengulangan ?
                $('input[name="pengulangan"]').prop('checked', true) :
                $('input[name="pengulangan"]').prop('checked', false);

            if (jadwal.pengulangan) {
                $('#tanggal-container').addClass('d-none');
                $('#hari-container').removeClass('d-none');
                $('select[name="hari"]').val(jadwal.hari).trigger('change')
            } else {
                $('#tanggal').val(jadwal.tanggal).trigger('change')
                $('#tanggal-container').removeClass('d-none');
                $('#hari-container').addClass('d-none');
            }

            $('input[name="waktu_mulai"]').val(jadwal.waktu_mulai);
            $('input[name="waktu_selesai"]').val(jadwal.waktu_selesai);
            $('input[name="ruangan"]').val(jadwal.ruangan);
        }
        $(document).ready(function () {
            $('#table').dataTable({
                dom: 'flrtip',
                language: {
                    search: "Cari Jadwal:",
                    lengthMenu: "Tampilkan _MENU_ jadwal",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ jadwal",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0 jadwal",
                    infoFiltered: "(difilter dari _MAX_ total jadwal)",
                    paginate: {
                        first: "Pertama",
                        last: "Terakhir",
                        next: "Berikutnya",
                        previous: "Sebelumnya"
                    },
                    zeroRecords: "Tidak ada data yang ditemukan",
                    emptyTable: "Tidak ada data yang tersedia di tabel ini",
                    loadingRecords: "Memuat...",
                    processing: "Memproses...",
                }
            });
            $('#jadwal-id').select2();

            $('#pengulangan').on('change', function () {
                if (this.checked) {
                    showContainerHari()
                } else {
                    showContainerTanggal()
                }
            })

            @error('jadwal_id')
            $('#jadwal-id + span').addClass('is-invalid')
            @enderror


            $('#jadwal-id').on('change', function () {
                fillCurrentValue($(this).val())
            })


        });
    </script>
@endsection


