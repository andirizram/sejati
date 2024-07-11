<!DOCTYPE html>
<html lang="en">

<head>
    @include('partials.top')
    <style>
        .modal {
            width: 100px;
            height: 100px;
            margin: 0 auto;
            display: table;
            position: absolute;
            left: 0;
            right: 0;
            top: 50%;
            -webkit-transform: translateY(-50%);
            -moz-transform: translateY(-50%);
            -ms-transform: translateY(-50%);
            -o-transform: translateY(-50%);
            transform: translateY(-50%);
        }

        /* Fullscreen specific styling */
        .fullscreen {
            width: 100%;
            height: 100%;
            position: fixed;
            top: 0;
            left: 0;
            background: white !important;
            z-index: 9999;
        }

        /* Hide sidebar and other non-table elements when in fullscreen mode */
        .fullscreen-mode .sidebar,
        .fullscreen-mode .navbar,
        .fullscreen-mode .other-elements-to-hide {
            display: none;
        }
    </style>
</head>

<body>
@include('partials.container')

<div class="container content">
    <div class="row">
        @include('partials.navbar')
        <div class="col-md-9">
            <div class="box box-success">
                <div class="box-body">
                    <center>
                        <h4>JADWAL SAYA</h4>
                    </center>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="toggleFullscreenTable1" class="btn btn-primary">Toggle Fullscreen</button>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Hari/Tanggal</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Akhir</th>
                                    <th>Dosen</th>
                                    <th>Ruangan</th>
                                    <th>Keterangan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($jadwalUser as $jadwal)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $jadwal['detail_jadwal']['tipe'] }}</td>
                                        <td>{{ $jadwal['tanggal_mulai'] }}</td>
                                        <td>{{ $jadwal['waktu_mulai'] }}</td>
                                        <td>{{ $jadwal['waktu_selesai'] }}</td>
                                        <td>{{ $jadwal['detail_jadwal']['dosen'] }}</td>
                                        <td>{{ $jadwal['ruangan'] }}</td>
                                        <td>{{ $jadwal['detail_jadwal']['deskripsi'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr/>
                </div>
            </div>

            <!-- Second Table with Fullscreen Button -->
            <div class="box box-success">
                <div class="box-body">
                    <center>
                        <h4>DAFTAR JADWAL YANG BERTABRAKAN</h4>
                    </center>
                    <br/>
                    <br/>
                    <div class="row">
                        <div class="col-md-12">
                            <button id="toggleFullscreenTable2" class="btn btn-primary">Toggle Fullscreen</button>
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Jenis</th>
                                    <th>Hari/Tanggal</th>
                                    <th>Waktu Mulai</th>
                                    <th>Waktu Akhir</th>
                                    <th>Dosen</th>
                                    <th>Ruangan</th>
                                    <th>Keterangan</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($jadwalBertabrakan as $jadwal)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $jadwal['detail_jadwal']['tipe'] }}</td>
                                        <td>{{ $jadwal['tanggal_mulai'] }}</td>
                                        <td>{{ $jadwal['waktu_mulai'] }}</td>
                                        <td>{{ $jadwal['waktu_selesai'] }}</td>
                                        <td>{{ $jadwal['detail_jadwal']['dosen'] }}</td>
                                        <td>{{ $jadwal['ruangan'] }}</td>
                                        <td>{{ $jadwal['detail_jadwal']['deskripsi'] }}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <hr/>
                </div>
            </div>
        </div>
    </div>
</div>
<div id="myModalTambah" class="modal fade" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body">
                <button type="button" class="close" data-dismiss="modal">×</button>
                <p>
                <center>Jadwal berhasil ditambahkan!</center>
                </p>
            </div>
        </div>
    </div>
</div>
@include('partials.bottom')

<script type="module">
    $('#openTambah').click(function () {
        $('#myModalTambah').modal({
            show: true
        })
    });
</script>

<script type="module">
    $(document).ready(function () {
        var body = document.body;

        function toggleFullscreen(element) {
            if (!document.fullscreenElement) {
                element.requestFullscreen().catch(err => {
                    alert(`Error attempting to enable full-screen mode: ${err.message} (${err.name})`);
                });
                element.classList.add('fullscreen'); // Add fullscreen class
                body.classList.add('fullscreen-mode'); // Add fullscreen-mode class to body
            } else {
                document.exitFullscreen();
            }
        }

        $('#toggleFullscreenTable1').click(function () {
            toggleFullscreen(this.nextElementSibling); // Next element (table)
        });

        $('#toggleFullscreenTable2').click(function () {
            toggleFullscreen(this.nextElementSibling); // Next element (table)
        });

        document.addEventListener('fullscreenchange', () => {
            if (!document.fullscreenElement) {
                $('.fullscreen').removeClass('fullscreen'); // Remove fullscreen class from the element that was in fullscreen
                body.classList.remove('fullscreen-mode'); // Remove fullscreen-mode class from body
            }
        });
    });
</script>
</body>

</html>
