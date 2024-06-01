@extends('layouts.app')


@section('content')
    @include('partials.alert')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Jadwal Tugas Akhir</h3>
            <div>
                @can('jadwal-ta.store')
                    <button type="button" id="btn-create-modal" class="btn btn-primary"
                            data-action="{{ route('jadwal-ta.store') }}"
                            onClick="openCreateModal(this)">
                        <i class="fa fa-plus"></i> Tambah Jadwal
                    </button>
                @endcan
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
            <p>Informasi mengenai jadwal pelaksanaan tugas akhir termasuk jadwal seminar dan jadwal sidang.</p>
                <div class="table-responsive">
                    <x-jadwal-table
                        :columns="$columns"
                        :jadwals="$jadwals"/>
                </div>

            </div>
        </div>

    </div>

    <x-modal-jadwal
        :id="'modal'"
        :method="'POST'"
        :action="route('jadwal-ta.store')">
        @include('jadwal-ta.form')
    </x-modal-jadwal>

    <div class="modal" id="modal-delete" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="form-delete">
                    @csrf
                    @method('DELETE')
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Hapus Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin ingin menghapus data ini?
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak
                        </button>
                        <button type="submit" class="btn btn-primary">Ya</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('script')
    @stack('jadwal-form-script')

    <script type="module">
        $(document).ready(function () {
            @error('create')
            $('#btn-create-modal').trigger('click');
            @enderror

            @error('edit')
            const action = '{{ route('jadwal-ta.update', $errors->first('id'))}}';
            $('button[data-url="' + action + '"]').trigger('click');
            @enderror

            $('#modal').on('show.bs.modal', function () {
                if (jadwal == null) {
                    return;
                }

                let detailJadwal = jadwal.detail_jadwal;

                $('#modal').find('select[name="tipe"]').val(detailJadwal.tipe).trigger('change');
                $('#modal').find('input[name="nama_mahasiswa"]').val(detailJadwal.nama_mahasiswa);
                $('#modal').find('input[name="nim"]').val(detailJadwal.nim);
                $('#modal').find('input[name="dosen_pembimbing_1"]').val(detailJadwal.dosen_pembimbing_1);
                $('#modal').find('input[name="dosen_pembimbing_2"]').val(detailJadwal.dosen_pembimbing_2);
                $('#modal').find('input[name="judul"]').val(detailJadwal.judul);
                $('#modal').find('input[name="dosen_penguji_1"]').val(detailJadwal.dosen_penguji_1);
                $('#modal').find('input[name="dosen_penguji_2"]').val(detailJadwal.dosen_penguji_2);
                $('#modal').find('input[name="tautan"]').val(detailJadwal.tautan);
                $('#modal').find('textarea[name="deskripsi"]').val(detailJadwal.deskripsi);
            });
        })


    </script>

@endsection


