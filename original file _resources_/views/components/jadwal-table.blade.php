@php
    $routeName = explode('.', request()->route()->getName())[0];
@endphp

<table class="table table-bordered table-striped" id="table">
    <thead>
    <tr>
        <th>Aksi</th>
        @foreach($columns as $column)
            <th>{{ $column }}</th>
        @endforeach
        <!-- <th>Aksi</th> -->
    </tr>
    <tr> <!-- New row for filters -->
        <th></th> <!-- Leave blank for Actions column -->
        @foreach($columns as $column)
            <!-- @if(in_array($column, ['SpecificColumn1', 'SpecificColumn2'])) < Specify the columns here
                <th><select class="form-control filter-select"></select></th> -->
            <!-- @else --> 
            <th></th> <!-- Empty th for columns without filters -->
            <!-- @endif -->
        @endforeach
        <!-- <th></th> Leave blank for Actions column -->
    </tr>
    </thead>


    <tbody>
    @foreach($jadwals as $key => $jadwal)
        <tr>
            <td style="text-align: center;">
                @can($routeName . '.update')
                    <button type="button" class="btn btn-default btn-sm"
                            data-modal-title="Edit Jadwal"
                            data-url="{{ route($routeName. '.show', $jadwal['id']) }}"
                            data-action="{{ route($routeName. '.update', $jadwal['id']) }}"
                            onClick="openEditModal(this)"
                            title="Edit Jadwal"
                    >
                        <i class="fa fa-pencil"></i>
                    </button>
                @endcan

                @can($routeName. '.destroy')
                    <button type="button" class="btn btn-default btn-danger btn-sm"
                            data-action="{{ route($routeName. '.destroy', $jadwal['id']) }}"
                            onClick="openDeleteModal(this)"
                            title="Hapus Jadwal">
                        <i class="fa fa-trash"></i>
                    </button>
                @endcan

                @can('jadwal.ambil')
                    <form action="{{ route('jadwal.ambil', $jadwal['id']) }}" method="post">
                        @csrf
                        @method('PUT')
                        <button type="submit" class="btn btn-default btn-sm" title="Ambil Jadwal">
                            @if(auth()->user()->jadwalDiambil()->where('id', $jadwal['id'])->exists())
                                <i class="fa fa-bookmark"></i>
                            @else
                                <i class="fa fa-bookmark-o"></i>
                            @endif
                        </button>
                    </form>
                @endcan
            </td>
            @foreach($columns as $column => $value)
                @if($column == 'deskripsi' || 'tautan')
                    <td>{!! $jadwal[$column] !!}</td> {{-- Render unescaped HTML for deskripsi --}}
                @else
                    <td>{{ $jadwal[$column] }}</td> {{-- Escape HTML for other columns --}}
                @endif
            @endforeach
        </tr>
    @endforeach
    </tbody>
</table>

@pushonce('jadwal-form-script')
    <script type="module">
        $(document).ready(function() {
            var weekdayOrder = {
                'Senin': 1, 'Selasa': 2, 'Rabu': 3, 'Kamis': 4,
                'Jumat': 5, 'Sabtu': 6, 'Minggu': 7
            };

            var chronologicalOrder = function(data) {
                var dateParts = data.split(' ');
                var day = dateParts[0];
                var date = dateParts[1];
                var month = dateParts[2];
                var year = dateParts[3];

                var months = {
                    'Januari': '01', 'Februari': '02', 'Maret': '03', 'April': '04',
                    'Mei': '05', 'Juni': '06', 'Juli': '07', 'Agustus': '08',
                    'September': '09', 'Oktober': '10', 'November': '11', 'Desember': '12'
                };

                var days = {
                    'Senin': '1', 'Selasa': '2', 'Rabu': '3', 'Kamis': '4',
                    'Jumat': '5', 'Sabtu': '6', 'Minggu': '7'
                };

                return year + months[month] + date.padStart(2, '0') + days[day];
            };

            jQuery.fn.dataTable.ext.type.order['weekday-pre'] = function (data) {
                return weekdayOrder[data] || 999; // Default to a high value for unexpected entries
            };

            jQuery.fn.dataTable.ext.type.order['chronological-pre'] = function (data) {
                return chronologicalOrder(data) || '9999999999'; // Default to a high value for unexpected entries
            };

            var currentRoute = '{{ $routeName }}';
            var columnDefs = [];

            // Apply custom sorting based on the route
            if (currentRoute === 'jadwal-ta' || currentRoute === 'jadwal-lain') {
                columnDefs.push({ targets: 1, type: 'chronological' }); // Apply chronological sorting to the second column (index 1)
            } else if (currentRoute === 'jadwal-tpb' || currentRoute === 'jadwal-prodi') {
                columnDefs.push({ targets: 1, type: 'weekday' }); // Apply weekday sorting to the second column (index 1)
            }

            var table = $('#table').DataTable({
                dom: 'flrtip',
                columnDefs: columnDefs,
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

            // Apply custom filter for 'jadwal-ta' route
            if (currentRoute === 'jadwal-ta') {
                table.columns().every(function() {
                    var column = this;
                    var columnIndex = column.index();

                    if (columnIndex === 2) { // Add specific options for the 'tipe' column (index 2)
                        var select = $('<select class="form-control" style="width: max-content;"><option value=""></option><option value="Seminar Proposal">Seminar Proposal</option><option value="Sidang Akhir">Sidang Akhir</option></select>')
                            .appendTo($("thead tr:eq(1) th").eq(column.index()).empty())
                            .on('mousedown', function (e) {
                                e.stopPropagation(); // Prevent mousedown event from triggering sorting
                            })
                            .on('click', function(e) {
                                e.stopPropagation(); // Prevent click event from reaching the table headers
                            })
                            .on('change', function(e) {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });
                    }
                });
            } else if (currentRoute === 'jadwal-lain') {
                table.columns().every(function() {
                    var column = this;
                    var columnIndex = column.index();
                    var filterColumns = [1]; // Specify columns that should have dropdowns

                    if (filterColumns.includes(columnIndex)) {
                        var select = $('<select class="form-control" style="width: max-content;"><option value=""></option></select>')
                            .appendTo($("thead tr:eq(1) th").eq(column.index()).empty())
                            .on('mousedown', function (e) {
                                e.stopPropagation(); // Prevent mousedown event from triggering sorting
                            })
                            .on('click', function(e) {
                                e.stopPropagation(); // Prevent click event from reaching the table headers
                            })
                            .on('change', function(e) {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                    }
                });
            } else if (currentRoute === 'jadwal-tpb' || currentRoute === 'jadwal-prodi') {
                table.columns().every(function() {
                    var column = this;
                    var columnIndex = column.index();
                    var filterColumns = [1, 7]; // Specify columns that should have dropdowns

                    if (filterColumns.includes(columnIndex)) {
                        var select = $('<select class="form-control" style="width: max-content;"><option value=""></option></select>')
                            .appendTo($("thead tr:eq(1) th").eq(column.index()).empty())
                            .on('mousedown', function (e) {
                                e.stopPropagation(); // Prevent mousedown event from triggering sorting
                            })
                            .on('click', function(e) {
                                e.stopPropagation(); // Prevent click event from reaching the table headers
                            })
                            .on('change', function(e) {
                                var val = $.fn.dataTable.util.escapeRegex($(this).val());
                                column.search(val ? '^' + val + '$' : '', true, false).draw();
                            });

                        column.data().unique().sort().each(function(d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>');
                        });
                    }
                });
            }
        });

        window.jadwal = null;

        let modalId = '#modal';

        window.openCreateModal = (element) => {
            parent.jadwal = null;
            $(modalId).find('form').trigger('reset');
            $(modalId).modal('show');
            $(modalId).find('.modal-title').html('Tambah Jadwal');
            $(modalId).find('form').attr('action', element.getAttribute('data-action'));
            $(modalId).find('input[name="_method"]').val('POST');
        }

        window.openEditModal = async (element) => {
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
    </script>
@endpushonce
