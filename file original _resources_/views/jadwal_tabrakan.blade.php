@extends('layouts.app')


@section('content')
    <h4>Jadwal Bertabrakan</h4>
    <p>Mengidentifikasi dan mengelola semua jenis jadwal yang saling bertabrakan.</p>
    <div class="box-body">
        <div class="row">
            <div class="table-responsive">
                <table class="table table-bordered" id="table">
                    <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Jadwal</th>
                        <th>Hari/Tanggal</th>
                        <th>Waktu Mulai</th>
                        <th>Waktu Akhir</th>
                        <th>Dosen</th>
                        <th>Ruangan</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($jadwals as $jadwal)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $jadwal['name'] }}</td>
                            <td>{{ $jadwal['tanggal_mulai'] }}</td>
                            <td>{{ $jadwal['waktu_mulai'] }}</td>
                            <td>{{ $jadwal['waktu_selesai'] }}</td>
                            <td>
                                @if (empty($jadwal['detail_jadwal']['dosen']))
                                @if (!empty($jadwal['detail_jadwal']['dosen_pembimbing_1']))
                                    <strong>Pembimbing 1:</strong> {{ $jadwal['detail_jadwal']['dosen_pembimbing_1'] }}<br>
                                @endif
                                @if (!empty($jadwal['detail_jadwal']['dosen_pembimbing_2']))
                                    <strong>Pembimbing 2:</strong> {{ $jadwal['detail_jadwal']['dosen_pembimbing_2'] }}<br>
                                @endif
                                @if (!empty($jadwal['detail_jadwal']['dosen_penguji_1']))
                                    <strong>Penguji 1:</strong> {{ $jadwal['detail_jadwal']['dosen_penguji_1'] }}<br>
                                @endif
                                @if (!empty($jadwal['detail_jadwal']['dosen_penguji_2']))
                                    <strong>Penguji 2:</strong> {{ $jadwal['detail_jadwal']['dosen_penguji_2'] }}<br>
                                @endif
                                @else
                                {{ $jadwal['detail_jadwal']['dosen'] }}
                                @endif
                            </td>
                            <td>{{ $jadwal['ruangan'] }}</td>
                            <td>
                                @if(strtolower($jadwal['tipe']) == 'ta')
                                    <strong>Judul:</strong> {{ $jadwal['detail_jadwal']['judul'] }}
                                    @else
                                    @php
                                        $deskripsi = $jadwal['detail_jadwal']['deskripsi'];
                                        $deskripsi = preg_replace(
                                            "/\b(http:\/\/|https:\/\/|www\.)[^ \f\n\r\t\v\"<>]*[^ \f\n\r\t\v\"<>\.,!?\[\]{}()*;:'\"!&$]+/iu",
                                            '<a href="$0" target="_blank">$0</a>',
                                            $deskripsi
                                        );
                                    @endphp
                                    {!! $deskripsi !!}
                                @endif
                            </td>
                            <td>
                                @can('jadwal-'. strtolower($jadwal['tipe']) . '.update')
                                    <button type="button" class="btn btn-default btn-sm"
                                            data-modal-id="{{ '#modal-'. strtolower($jadwal['tipe']) }}"
                                            data-modal-title="Edit Jadwal"
                                            data-url="{{ route('jadwal-'. strtolower($jadwal['tipe']) . '.show', $jadwal['id']) }}"
                                            data-action="{{ route('jadwal-'. strtolower($jadwal['tipe']) . '.update', $jadwal['id']) }}"
                                            onClick="openEditModal(this)"
                                    >
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                @endcan

                                @can('jadwal'. strtolower($jadwal['tipe']). '.destroy')
                                    <button type="button" class="btn btn-default btn-danger btn-sm"
                                            data-action="{{ route($routeName. '.destroy', $jadwal['id']) }}"
                                            onClick="openDeleteModal(this)">
                                        <i class="fa fa-trash"></i>
                                    </button>
                                @endcan
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <x-modal-jadwal
        :id="'modal-ta'"
        :method="'POST'"
        :action="route('jadwal-ta.store')">
        @include('jadwal-ta.form')
    </x-modal-jadwal>

    <x-modal-jadwal
        :id="'modal-prodi'"
        :method="'POST'"
        :action="route('jadwal-prodi.store')">
        @include('jadwal-prodi.form')
    </x-modal-jadwal>

    <x-modal-jadwal
        :id="'modal-tpb'"
        :method="'POST'"
        :action="route('jadwal-tpb.store')">
        @include('jadwal-tpb.form')
    </x-modal-jadwal>

    <x-modal-jadwal
        :id="'modal-lain'"
        :method="'POST'"
        :action="route('jadwal-lain.store')">
        @include('jadwal-lain.form')
    </x-modal-jadwal>

@endsection

@section('script')
    <script type="module">
        window.jadwal = null;


        window.openCreateModal = (element) => {
            let modalId = element.attr('data-modal-id');
            parent.jadwal = null;
            $(modalId).find('form').trigger('reset');
            $(modalId).modal('show');
            $(modalId).find('.modal-title').html('Tambah Jadwal');
            $(modalId).find('form').attr('action', element.getAttribute('data-action'));
            $(modalId).find('input[name="_method"]').val('POST');
        }

        window.openEditModal = async (element) => {
            let modalId = element.getAttribute('data-modal-id')
            $(modalId).find('form').trigger('reset');
            const response = await fetch(element.getAttribute('data-url'));

            jadwal = await response.json();

            $(modalId).find('.modal-title').html('Edit Jadwal');
            $(modalId).find('form').attr('action', element.getAttribute('data-action'));
            $(modalId).find('input[name="_method"]').val('PUT');
            await $(modalId).modal('show');
            await $(modalId + ' form').attr('action', element.getAttribute('data-action'));
        }


        window.openDeleteModal = (element) => {
            $('#modal-delete').modal('show');
            $('#modal-delete').find('form').attr('action', element.getAttribute('data-action'));
        }


        function showContainerHari(modalId) {
            $(modalId + ' #tanggal-container').addClass('d-none');
            $(modalId + ' #hari-container').removeClass('d-none');
        }

        function showContainerTanggal(modalId) {
            $(modalId + ' #tanggal-container').removeClass('d-none');
            $(modalId + ' #hari-container').addClass('d-none');
        }


        $(document).ready(function () {
            $('#table').dataTable({
                dom: 'flrtip',
                columnDefs: [
                    {orderable: false, targets: -1}
                ],
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
            $('#modal-ta, #modal-prodi, #modal-tpb, #modal-lain').on('show.bs.modal', function (event) {
                let modalId = '#' + event.target.id


                if (jadwal == null) {
                    return;
                }
                let dataJadwal = jadwal;
                let detailJadwal = jadwal.detail_jadwal

                dataJadwal.pengulangan ?
                    $(modalId).find('input[name="pengulangan"]').prop('checked', true) :
                    $(modalId).find('input[name="pengulangan"]').prop('checked', false);

                if (dataJadwal.pengulangan) {
                    showContainerHari(modalId)
                    $(modalId).find('select[name="hari"]').val(dataJadwal.hari).trigger('change')
                } else {
                    showContainerTanggal(modalId)
                    $(modalId + ' #tanggal').val(dataJadwal.tanggal).trigger('change')
                }

                $(modalId).find('input[name="waktu_mulai"]').val(dataJadwal.waktu_mulai);
                $(modalId).find('input[name="waktu_selesai"]').val(dataJadwal.waktu_selesai);
                $(modalId).find('input[name="ruangan"]').val(dataJadwal.ruangan);

                // Jadwal TPB & Prodi
                $(modalId).find('input[name="tipe"]').val(detailJadwal.tipe);
                $(modalId).find('input[name="mata_kuliah"]').val(detailJadwal.mata_kuliah);
                $(modalId).find('input[name="sks"]').val(detailJadwal.sks);
                $(modalId).find('input[name="kelas"]').val(detailJadwal.kelas);
                $(modalId).find('input[name="semester"]').val(detailJadwal.semester);
                $(modalId).find('input[name="dosen"]').val(detailJadwal.dosen);
                $(modalId).find('textarea[name="deskripsi"]').val(detailJadwal.deskripsi);

                // Jadwal TA
                $(modalId).find('select[name="tipe"]').val(detailJadwal.tipe).trigger('change');
                $(modalId).find('input[name="nama_mahasiswa"]').val(detailJadwal.nama_mahasiswa);
                $(modalId).find('input[name="nim"]').val(detailJadwal.nim);
                $(modalId).find('input[name="dosen_pembimbing_1"]').val(detailJadwal.dosen_pembimbing_1);
                $(modalId).find('input[name="dosen_pembimbing_2"]').val(detailJadwal.dosen_pembimbing_2);
                $(modalId).find('input[name="judul"]').val(detailJadwal.judul);
                $(modalId).find('input[name="dosen_penguji_1"]').val(detailJadwal.dosen_penguji_1);
                $(modalId).find('input[name="dosen_penguji_2"]').val(detailJadwal.dosen_penguji_2);
                $(modalId).find('input[name="tautan"]').val(detailJadwal.tautan);
                $(modalId).find('textarea[name="deskripsi"]').val(detailJadwal.deskripsi);

            });
        })
    </script>
@endsection
