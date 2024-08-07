@extends('layouts.app')

@section('style')
    <style>
        .fc-state-intersect {
            background: #F44336 !important;
            border-color: #F44336 !important;
            color: white;
        }

        .fc-state-intersect:hover {
            background: rgb(238, 105, 93) !important;
            color: white;
        }

        .fc-event-prodi {
            background: #1e90ff;
            border-color: #1E90FF;
            color: white;
        }

        .fc-event-tpb {
            background: #32CD32;
            border-color: #32CD32;
            color: white;
        }

        .fc-event-ta {
            background: #DD761C;
            border-color: #DD761C;
            color: white;
        }

        .fc-event-lainnya {
            background: #524C42;
            border-color: #524C42;
            color: white;
        }
        /* Updated CSS for the horizontal legend */
        .color-legend ul {
                list-style: none;
                padding: 0;
                display: flex;
            }

            .color-legend li {
                display: flex;
                align-items: center;
                margin-right: 15px;
            }

            .legend-color {
                width: 20px;
                height: 20px;
                display: inline-block;
                margin-right: 5px;
            }

            .legend-color.fc-event-prodi {
                background: #1e90ff;
            }

            .legend-color.fc-event-tpb {
                background: #32CD32;
            }

            .legend-color.fc-event-ta {
                background: #DD761C;
            }

            .legend-color.fc-event-lainnya {
                background: #524C42;
            }

            .legend-color.fc-event-tabrakan {
                background: #F44336;
            }
    </style>
@endsection

@section('content')

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center">
        <h2>Kalender Jadwal Saya</h2>
        <!-- Add user selection dropdown -->
        <select id="userSelect" class="form-control" style="width: 200px;">
            <option value="">Punya Sendiri</option>
            @foreach($users as $user)
                <option value="{{ $user->id }}">{{ $user->name }}</option>
            @endforeach
        </select>
    </div>
    <!-- Add this part for the legend -->
    <div class="color-legend mt-3">
        <h4>Keterangan Warna Jadwal :</h4>
        <ul>
            <li><span class="legend-color fc-event-prodi"></span> Prodi</li>
            <li><span class="legend-color fc-event-tpb"></span> TPB</li>
            <li><span class="legend-color fc-event-ta"></span> TA</li>
            <li><span class="legend-color fc-event-lainnya"></span> Lainnya</li>
            <li><span class="legend-color fc-event-tabrakan"></span> Tabrakan</li>
        </ul>
    </div>

    <div id="calendar"></div>
    <div class="modal" id="modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">

                <div class="modal-header">
                    <h5 class="modal-title">Detail Jadwal</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <h4 id="judul">Bagian Judul</h4>
                    <table class="table">
                    <tbody>
                        <tr>
                            <td style="width:10%">Waktu</td>
                            <td>:</td>
                            <td id="waktu"></td>
                        </tr>
                        <tr>
                            <td>Ruangan</td>
                            <td>:</td>
                            <td id="ruangan"></td>
                        </tr>
                        <tr>
                            <td>Deskripsi</td>
                            <td>:</td>
                            <td id="deskripsi"></td>
                        </tr>
                        <tr class="dosen-field d-none">
                            <td>Dosen</td>
                            <td>:</td>
                            <td id="dosen"></td>
                        </tr>
                        <tr class="dosen-pembimbing-1-field d-none">
                            <td>Dosen Pembimbing 1</td>
                            <td>:</td>
                            <td id="dosen_pembimbing_1"></td>
                        </tr>
                        <tr class="dosen-pembimbing-2-field d-none">
                            <td>Dosen Pembimbing 2</td>
                            <td>:</td>
                            <td id="dosen_pembimbing_2"></td>
                        </tr>
                        <tr class="dosen-penguji-1-field d-none">
                            <td>Dosen Penguji 1</td>
                            <td>:</td>
                            <td id="dosen_penguji_1"></td>
                        </tr>
                        <tr class="dosen-penguji-2-field d-none">
                            <td>Dosen Penguji 2</td>
                            <td>:</td>
                            <td id="dosen_penguji_2"></td>
                        </tr>
                    </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary btn-sm mr-auto d-none" id="button-edit"
                            onClick="openEditModal(this)"><i class=" fa fa-pencil"></i>
                        Edit
                    </button>
                    @can('jadwal.ambil')
                        <form action="" class="p-0 m-0 d-inline" method="post" id="form-lepas-jadwal">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-default btn-sm">
                                <i class="fa fa-bookmark-o"></i> Ambil/Lepas Jadwal
                            </button>
                        </form>
                    @endcan
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                </div>
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
    <script>
        // Function to convert text URLs into clickable links
        function linkify(text) {
            var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            return text.replace(urlRegex, function(url) {
                return '<a href="' + url + '" target="_blank">' + url + '</a>';
            }).replace(/\n/g, '<br>'); // Replace newlines with <br>
        }

        window.openEditModal = async (element) => {
            let modalId = element.getAttribute('data-modal-id');
            $(modalId).find('form').trigger('reset');
            const response = await fetch(element.getAttribute('data-url'));

            let jadwal = await response.json();

            $(modalId).find('.modal-title').html('Edit Jadwal');
            $(modalId).find('form').attr('action', element.getAttribute('data-action'));
            $(modalId).find('input[name="_method"]').val('PUT');
            await $(modalId).modal('show');
            await $(modalId + ' form').attr('action', element.getAttribute('data-action'));
        }

        function openDetailModal(info) {
            $('#modal').modal('show');

            $('#modal .modal-body h4').text(info.event.title);

            const waktuMulai = new Date(info.event.start).toLocaleString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            });

            const waktuSelesai = new Date(info.event.end).toLocaleString('id-ID', {
                day: 'numeric',
                month: 'long',
                year: 'numeric',
                hour: 'numeric',
                minute: 'numeric',
                second: 'numeric'
            });

            $('#modal .modal-body #waktu').text(waktuMulai + ' - ' + waktuSelesai);
            $('#modal .modal-body #ruangan').text(info.event.extendedProps.ruangan);
            const deskripsiText = info.event.extendedProps.deskripsi;
            $('#modal .modal-body #deskripsi').html(linkify(deskripsiText));
            $('#modal .modal-body #dosen').text(info.event.extendedProps.dosen);

            // Reset dosen fields
            $('#modal .modal-body .dosen-field').addClass('d-none');
            $('#modal .modal-body .dosen-pembimbing-1-field').addClass('d-none');
            $('#modal .modal-body .dosen-pembimbing-2-field').addClass('d-none');
            $('#modal .modal-body .dosen-penguji-1-field').addClass('d-none');
            $('#modal .modal-body .dosen-penguji-2-field').addClass('d-none');

            const tipe = info.event.extendedProps.tipe.toLowerCase();
            if (tipe === 'prodi' || tipe === 'tpb' || tipe === 'lain') {
                $('#modal .modal-body .dosen-field').removeClass('d-none');
                $('#modal .modal-body #dosen').text(info.event.extendedProps.dosen);
            } else if (tipe === 'ta') {
                $('#modal .modal-body .dosen-pembimbing-1-field').removeClass('d-none');
                $('#modal .modal-body .dosen-pembimbing-2-field').removeClass('d-none');
                $('#modal .modal-body .dosen-penguji-1-field').removeClass('d-none');
                $('#modal .modal-body .dosen-penguji-2-field').removeClass('d-none');
                $('#modal .modal-body #dosen_pembimbing_1').text(info.event.extendedProps.dosen_pembimbing_1);
                $('#modal .modal-body #dosen_pembimbing_2').text(info.event.extendedProps.dosen_pembimbing_2);
                $('#modal .modal-body #dosen_penguji_1').text(info.event.extendedProps.dosen_penguji_1);
                $('#modal .modal-body #dosen_penguji_2').text(info.event.extendedProps.dosen_penguji_2);
            }

            $('#modal .modal-footer #form-lepas-jadwal').attr('action', '{{URL::to('/')}}' +
                '/jadwal/' + info.event.id + '/ambil'
            );

            @can(['jadwal-ta.update', 'jadwal-tpb.update', 'jadwal-prodi.update', 'jadwal-lain.update'])
            $('#modal .modal-footer #button-edit').removeClass('d-none')
            $('#modal .modal-footer #button-edit').attr('data-modal-id', '#modal-' + tipe)
            $('#modal .modal-footer #button-edit').attr('data-url', '{{URL::to('/')}}' + '/jadwal-' + tipe + '/' + info.event.id)
            $('#modal .modal-footer #button-edit').attr('data-action', '{{URL::to('/')}}' + '/jadwal-' + tipe + '/' + info.event.id)
            @endcan
        }

        function showContainerHari(modalId) {
            $(modalId + ' #tanggal-container').addClass('d-none');
            $(modalId + ' #hari-container').removeClass('d-none');
        }

        function showContainerTanggal(modalId) {
            $(modalId + ' #tanggal-container').removeClass('d-none');
            $(modalId + ' #hari-container').addClass('d-none');
        }

        function calculateWaktuSelesaiProdi(modalId) {
            const waktuMulaiInput = $(modalId).find('input[name="waktu_mulai"]');
            const sksInput = $(modalId).find('input[name="sks"]');
            const waktuSelesaiInput = $(modalId).find('input[name="waktu_selesai"]');

            const waktuMulai = waktuMulaiInput.val();
            let sks = parseInt(sksInput.val());

            if (waktuMulai && sks) {
                if (sks === 4) {
                    sks = 2;
                }

                const waktuMulaiDate = new Date(`1970-01-01T${waktuMulai}:00`);
                const minutesToAdd = sks * 50;
                waktuMulaiDate.setMinutes(waktuMulaiDate.getMinutes() + minutesToAdd);

                const hours = String(waktuMulaiDate.getHours()).padStart(2, '0');
                const minutes = String(waktuMulaiDate.getMinutes()).padStart(2, '0');
                waktuSelesaiInput.val(`${hours}:${minutes}`);
            }
        }

        function calculateWaktuSelesaiTa(modalId) {
            const waktuMulaiInput = $(modalId).find('input[name="waktu_mulai"]');
            const tipeSelect = $(modalId).find('select[name="tipe"]');
            const waktuSelesaiInput = $(modalId).find('input[name="waktu_selesai"]');

            const waktuMulai = waktuMulaiInput.val();
            const tipe = tipeSelect.val();

            if (waktuMulai && tipe) {
                const waktuMulaiDate = new Date(`1970-01-01T${waktuMulai}:00`);
                let minutesToAdd;

                if (tipe === 'Seminar Proposal') {
                    minutesToAdd = 60; // 1 hour
                } else if (tipe === 'Sidang Akhir') {
                    minutesToAdd = 90; // 1.5 hours
                }

                waktuMulaiDate.setMinutes(waktuMulaiDate.getMinutes() + minutesToAdd);

                const hours = String(waktuMulaiDate.getHours()).padStart(2, '0');
                const minutes = String(waktuMulaiDate.getMinutes()).padStart(2, '0');
                waktuSelesaiInput.val(`${hours}:${minutes}`);
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            $('#modal-ta, #modal-prodi, #modal-tpb, #modal-lain').on('show.bs.modal', function (event) {
                let modalId = '#' + event.target.id;

                if (jadwal == null) {
                    return;
                }
                let dataJadwal = jadwal;
                let detailJadwal = jadwal.detail_jadwal;

                dataJadwal.pengulangan ?
                    $(modalId).find('input[name="pengulangan"]').prop('checked', true) :
                    $(modalId).find('input[name="pengulangan"]').prop('checked', false);

                if (dataJadwal.pengulangan) {
                    showContainerHari(modalId);
                    $(modalId).find('select[name="hari"]').val(dataJadwal.hari).trigger('change');
                } else {
                    showContainerTanggal(modalId);
                    $(modalId + ' #tanggal').val(dataJadwal.tanggal).trigger('change');
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

                // Add event listeners to calculate waktu selesai
                $(modalId).find('input[name="waktu_mulai"], input[name="sks"]').on('change', function () {
                    calculateWaktuSelesaiProdi(modalId);
                });

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

                // Add event listeners to calculate waktu selesai for TA
                $(modalId).find('input[name="waktu_mulai"], select[name="tipe"]').on('change', function () {
                    calculateWaktuSelesaiTa(modalId);
                });
            });

            let jadwalKalender = [];

            @foreach($jadwals as $jadwal)
            jadwalKalender.push({
                id: '{{ $jadwal['id'] }}',
                name: '{{ $jadwal['name'] }}',
                tipe: '{{ $jadwal['tipe'] }}',
                tanggal: '{{ $jadwal['tanggal'] }}',
                waktu_mulai: '{{ $jadwal['waktu_mulai'] }}',
                waktu_selesai: '{{ $jadwal['waktu_selesai'] }}',
                ruangan: '{{ $jadwal['ruangan'] }}',
                deskripsi: `{!! nl2br(e($jadwal['deskripsi'])) !!}`, // Changed part
                dosen: '{{ $jadwal['detail_jadwal']['dosen'] ?? '' }}', // Add this line
                dosen_pembimbing_1: '{{ $jadwal['detail_jadwal']['dosen_pembimbing_1'] ?? '' }}', // Add this line
                dosen_pembimbing_2: '{{ $jadwal['detail_jadwal']['dosen_pembimbing_2'] ?? '' }}', // Add this line
                dosen_penguji_1: '{{ $jadwal['detail_jadwal']['dosen_penguji_1'] ?? '' }}', // Add this line
                dosen_penguji_2: '{{ $jadwal['detail_jadwal']['dosen_penguji_2'] ?? '' }}', // Add this line
                tabrakan: '{{ $jadwal['is_tabrakan'] }}',
            })
            @endforeach

            const calendarEl = document.getElementById('calendar');

            const calendar = new Calendar(calendarEl, {
                plugins: [dayGridPlugin, timeGridPlugin],
                locale: 'id',
                headerToolbar: {
                    left: 'prev,next today',
                    center: 'title',
                    right: 'dayGridMonth,timeGridWeek,timeGridDay'
                },
                buttonText: {
                    today: 'Hari Ini',
                    month: 'Bulan',
                    week: 'Minggu',
                    day: 'Hari',
                },
                allDayText: 'Jam',
                initialView: 'dayGridMonth',
                events: jadwalKalender.map(jadwal => {
                    return {
                        id: jadwal.id,
                        title: jadwal.name,
                        start: jadwal.tanggal + 'T' + jadwal.waktu_mulai,
                        end: jadwal.tanggal + 'T' + jadwal.waktu_selesai,
                        ruangan: jadwal.ruangan,
                        deskripsi: jadwal.deskripsi,
                        dosen: jadwal.dosen,
                        dosen_pembimbing_1: jadwal.dosen_pembimbing_1,
                        dosen_pembimbing_2: jadwal.dosen_pembimbing_2,
                        dosen_penguji_1: jadwal.dosen_penguji_1,
                        dosen_penguji_2: jadwal.dosen_penguji_2,
                        tipe: jadwal.tipe,
                        className: jadwal.tabrakan === '1' ? 'fc-state-intersect' : ''
                    }
                }),
                eventClassNames: function(arg) {
                    switch(arg.event.extendedProps.tipe) {
                        case 'Prodi':
                            return ['fc-event-prodi'];
                        case 'TPB':
                            return ['fc-event-tpb'];
                        case 'TA':
                            return ['fc-event-ta'];
                        case 'Lain':
                            return ['fc-event-lainnya'];
                        default:
                            return [];
                    }
                },
                eventClick: function (info) {
                    window.openDetailModal(info)
                }
            })
            calendar.render()

            // Add event listener to the user select dropdown
            document.getElementById('userSelect').addEventListener('change', function() {
                let userId = this.value;
                if (userId) {
                    fetch(`/getUserSchedule/${userId}`)
                        .then(response => response.json())
                        .then(data => {
                            const events = data.map(event => ({
                                id: event.id,
                                title: event.name,
                                start: event.tanggal + 'T' + event.waktu_mulai,
                                end: event.tanggal + 'T' + event.waktu_selesai,
                                ruangan: event.ruangan,
                                deskripsi: event.deskripsi,
                                dosen: event.detail_jadwal.dosen,
                                dosen_pembimbing_1: event.detail_jadwal.dosen_pembimbing_1,
                                dosen_pembimbing_2: event.detail_jadwal.dosen_pembimbing_2,
                                dosen_penguji_1: event.detail_jadwal.dosen_penguji_1,
                                dosen_penguji_2: event.detail_jadwal.dosen_penguji_2,
                                tipe: event.tipe,
                                className: event.is_tabrakan ? 'fc-state-intersect' : ''
                            }));
                            calendar.removeAllEvents();
                            calendar.addEventSource(events);
                        })
                        .catch(error => console.error('Error fetching user schedule:', error));
                } else {
                    calendar.removeAllEvents();
                    calendar.addEventSource(jadwalKalender.map(jadwal => ({
                        id: jadwal.id,
                        title: jadwal.name,
                        start: jadwal.tanggal + 'T' + jadwal.waktu_mulai,
                        end: jadwal.tanggal + 'T' + jadwal.waktu_selesai,
                        ruangan: jadwal.ruangan,
                        deskripsi: jadwal.deskripsi,
                        dosen: jadwal.dosen,
                        dosen_pembimbing_1: jadwal.dosen_pembimbing_1,
                        dosen_pembimbing_2: jadwal.dosen_pembimbing_2,
                        dosen_penguji_1: jadwal.dosen_penguji_1,
                        dosen_penguji_2: jadwal.dosen_penguji_2,
                        tipe: jadwal.tipe,
                        className: jadwal.tabrakan === '1' ? 'fc-state-intersect' : ''
                    })));
                }
            });
        });
    </script>
@endsection