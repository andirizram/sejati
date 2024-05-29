@extends('layouts.app')


@section('content')

    <div class="box-body">
        <h4>DAFTAR PENGAJUAN PERUBAHAN JADWAL</h4>
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
                        <th>Status</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach($pengajuans as $pengajuan)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $pengajuan['name'] ?? $pengajuan['tipe'] }}</td> <!-- If name is null, tipe is displayed -->
                            <td>{{ $pengajuan['tanggal_mulai'] }}</td>
                            <td>{{ $pengajuan['waktu_mulai'] }}</td>
                            <td>{{ $pengajuan['waktu_selesai'] }}</td>
                            <td>{{ $pengajuan['pembuat'] }}</td>
                            <td>{{ $pengajuan['ruangan'] }}</td>
                            <td>{{ $pengajuan['alasan'] }}</td>
                            @if($pengajuan['status'] == 0)
                                <td>
                                    <button data-action="{{ route('perubahan-jadwal.setuju', $pengajuan['id']) }}"
                                            class="btn btn-success btn-setuju">Setujui
                                    </button>
                                    <button data-action="{{ route('perubahan-jadwal.tolak', $pengajuan['id']) }}"
                                            class="btn btn-danger btn-tolak">Tolak
                                    </button>
                                </td>
                            @elseif($pengajuan['status'] == 1)
                                <td>
                                    <button class="btn btn-success" disabled>Disetujui</button>
                                </td>
                            @elseif($pengajuan['status'] == -1)
                                <td>
                                    <button class="btn btn-danger" disabled>Ditolak</button>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <hr/>
    </div>


    <div id="myModalSetuju" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="">Alasan menyetujui perubahan</h3>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <div class="col-sm-5">
                                <textarea id="freeform" name="freeform" rows="4" cols="60"></textarea>
                            </div>
                            <br>
                            <br>
                            <br>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-setuju" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="form-setuju" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi menyetujui pengajuan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Alasan Menyetujui</label>
                            <textarea class="form-control" name="alasan" rows="5"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak
                        </button>
                        <button type="submit" class="btn btn-success">Setuju</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="modal" id="modal-tolak" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="" method="POST" id="form-tolak" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi menolak pengajuan</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="">Alasan Menolak</label>
                            <textarea class="form-control" name="alasan" rows="5"></textarea>
                        </div>

                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tidak
                        </button>
                        <button type="submit" class="btn btn-danger">Tolak</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div id="myModalTolak" class="modal fade" tabindex="-1" role="dialog">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal">×</button>
                    <h3 class="">Alasan menolak perubahan</h3>
                </div>
                <div class="modal-body">
                    <form role="form">
                        <div class="form-group">
                            <div class="col-sm-5">
                                <textarea id="freeform" name="freeform" rows="4" cols="60"></textarea>
                            </div>
                            <br>
                            <br>
                            <br>
                        </div>
                        <button type="submit" class="btn btn-default">Submit</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script type="module">
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
            $('.btn-setuju').on('click', function () {
                $('#modal-setuju').modal('show');
                $('#form-setuju').attr('action', $(this).attr('data-action'));
            });

            $('.btn-tolak').on('click', function () {
                $('#modal-tolak').modal("show");
                $('#form-tolak').attr('action', $(this).attr('data-action'));
            })
        })
    </script>
@endsection
