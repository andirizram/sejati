@extends('layouts.app')

@section('style')
    <style>
        .fc-state-intersect {
            background: #F44336;
            border-color: #F44336;
            color: white;
        }

        .fc-state-intersect:hover {
            background: rgb(238, 105, 93);
            color: white;
        }

        .fc-event-prodi {
            background: #1E90FF;
            border-color: #1E90FF;
            color: white;
        }

        .fc-event-tpb {
            background: #32CD32;
            border-color: #32CD32;
            color: white;
        }

        .fc-event-ta {
            background: #FFD700;
            border-color: #FFD700;
            color: white;
        }

        .fc-event-lainnya {
            background: #F97B4F;
            border-color: #F97B4F;
            color: white;
        }
    </style>
@endsection
@section('content')
    <div class="d-flex justify-content-between align-items-center">
        <h2>Kalender Jadwal Saya</h2>
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
                            onClick="openEditModal(this)"><i class=" fa fa-pencil
                    "></i>
                        Edit
                    </button>
                    @can('jadwal.ambil')
                        <form action="" class="p-0 m-0 d-inline" method="post" id="form-lepas-jadwal">
                            @csrf
                            @method('PUT')
                            <button type="submit" class="btn btn-default btn-sm">
                                <i class="fa fa-bookmark-o"></i> Lepas Jadwal
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
    <!-- Include dosenMapping.js -->
    <script type="module" src="{{ asset('js/dosenMapping.js') }}"></script>

    <script>

        // Function to convert text URLs into clickable links
        function linkify(text) {
            var urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
            return text.replace(urlRegex, function(url) {
                return '<a href="' + url + '" target="_blank">' + url + '</a>';
            }).replace(/\n/g, '<br>'); // Replace newlines with <br>
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

            // Reset dosen fields
            $('#modal .modal-body .dosen-field').addClass('d-none');
            $('#modal .modal-body .dosen-pembimbing-1-field').addClass('d-none');
            $('#modal .modal-body .dosen-pembimbing-2-field').addClass('d-none');
            $('#modal .modal-body .dosen-penguji-1-field').addClass('d-none');
            $('#modal .modal-body .dosen-penguji-2-field').addClass('d-none');

            // Replace dosen initials with full names
            const dosen = dosenMapping[info.event.extendedProps.dosen] || info.event.extendedProps.dosen;
            const tipe = info.event.extendedProps.tipe.toLowerCase();
            if (tipe === 'prodi' || tipe === 'tpb' || tipe === 'lain') {
                $('#modal .modal-body .dosen-field').removeClass('d-none');
                $('#modal .modal-body #dosen').text(dosen);
            } else if (tipe === 'ta') {
                const dosenPembimbing1 = dosenMapping[info.event.extendedProps.dosen_pembimbing_1] || info.event.extendedProps.dosen_pembimbing_1;
                const dosenPembimbing2 = dosenMapping[info.event.extendedProps.dosen_pembimbing_2] || info.event.extendedProps.dosen_pembimbing_2;
                const dosenPenguji1 = dosenMapping[info.event.extendedProps.dosen_penguji_1] || info.event.extendedProps.dosen_penguji_1;
                const dosenPenguji2 = dosenMapping[info.event.extendedProps.dosen_penguji_2] || info.event.extendedProps.dosen_penguji_2;

                $('#modal .modal-body .dosen-pembimbing-1-field').removeClass('d-none');
                $('#modal .modal-body .dosen-pembimbing-2-field').removeClass('d-none');
                $('#modal .modal-body .dosen-penguji-1-field').removeClass('d-none');
                $('#modal .modal-body .dosen-penguji-2-field').removeClass('d-none');
                $('#modal .modal-body #dosen_pembimbing_1').text(dosenPembimbing1);
                $('#modal .modal-body #dosen_pembimbing_2').text(dosenPembimbing2);
                $('#modal .modal-body #dosen_penguji_1').text(dosenPenguji1);
                $('#modal .modal-body #dosen_penguji_2').text(dosenPenguji2);
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

        document.addEventListener('DOMContentLoaded', function () {
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
        });

    </script>
@endsection
