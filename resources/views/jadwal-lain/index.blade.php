@extends('layouts.app')


@section('content')
    @include('partials.alert')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center">
            <h3>Jadwal Lainnya</h3>
            <div>
                @can('jadwal-lain.store')
                    <button type="button" class="btn btn-primary" id="btn-create-modal"
                            data-action="{{ route('jadwal-lain.store') }}"
                            onClick="openCreateModal(this)">
                        <i class="fa fa-plus"></i> Tambah Jadwal
                    </button>
                @endcan
            </div>
        </div>
        <div class="panel panel-default">
            <div class="panel-body">
            <p>Jadwal kegiatan tambahan seperti sosialisasi, seminar umum, dan lainnya.</p>
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
        :action="route('jadwal-lain.store')">
        @include('jadwal-lain.form')
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

                $('#modal').find('input[name="tipe"]').val(detailJadwal.tipe);
                $('#modal').find('input[name="mata_kuliah"]').val(detailJadwal.mata_kuliah);
                $('#modal').find('input[name="sks"]').val(detailJadwal.sks);
                $('#modal').find('input[name="semester"]').val(detailJadwal.semester);
                $('#modal').find('input[name="dosen"]').val(detailJadwal.dosen);
                $('#modal').find('textarea[name="deskripsi"]').val(detailJadwal.deskripsi);

            });

        });

    </script>

@endsection


