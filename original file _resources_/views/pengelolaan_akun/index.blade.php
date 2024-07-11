@extends('layouts.app')

@section('content')
    @include('partials.alert')
    <style>
        #table-user {
            table-layout: fixed;
            width: 100%;
        }

        #table-user th:nth-child(2), #table-role th:nth-child(2) {
            min-width: 100px;
        }

        #table-user th:nth-child(3) {
            min-width: 300px;
        }

        #table-user th:nth-child(4) {
            min-width: 400px;
        }
    </style>
    @can('user.index')
        <section>
            <div class="d-flex justify-content-between m-2 p-3">
                <h4>Daftar Akun</h4>
                @can('user.store')
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modal-create-user">
                            <i class="fa fa-plus"></i> Tambah Akun
                        </button>
                    </div>
                @endcan
            </div>

            <table class="table-responsive table-bordered table-striped" id="table-user">
                <p>Menampilkan daftar semua akun pengguna yang terdaftar dalam sistem.</p>
                <thead>
                <tr>
                    <th>No</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Jenis Akun</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                        <td class="cell-edit text-center">
                            @can('user.update')
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                        data-target="#modal-edit-user"
                                        data-action="{{ route('user.update', $user->id) }}"
                                        data-url="{{ route('user.show', $user->id) }}">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            @endcan
                            @can('user.destroy')
                                <button type="button" class="btn btn-default btn-danger btn-sm" data-toggle="modal"
                                        data-target="#modal-delete"
                                        data-url="{{ route('user.destroy', $user->id) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    @endcan

    @can('role.index')
        <section>
            <div class="d-flex justify-content-between m-2 p-3">
                <h4>Daftar Role</h4>
                @can('role.store')
                    <div>
                        <button type="button" class="btn btn-primary" data-toggle="modal"
                                data-target="#modal-create-role">
                            <i class="fa fa-plus"></i> Tambah Role
                        </button>
                    </div>
                @endcan
            </div>
            <table class="table table-bordered table-striped" id="table-role">
                <p>Menyajikan informasi tentang berbagai jenis role yang tersedia, termasuk hak akses dan fitur
                    masing-masing jenis role.</p>
                <thead>
                <tr>
                    <th>No</th>
                    <th>Role</th>
                    <th style="width: 100px; text-align: center;">Aksi</th>
                </tr>
                </thead>
                <tbody>
                @foreach($roles as $role)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $role->name }}</td>
                        <td class="text-center">
                            @can('role.update')
                                <button type="button" class="btn btn-default btn-sm" data-toggle="modal"
                                        data-target="#modal-edit-role"
                                        data-action="{{ route('role.update', $role->id) }}"
                                        data-url="{{ route('role.show', $role->id) }}">
                                    <i class="fa fa-pencil"></i>
                                </button>
                            @endcan
                            @can('role.destroy')
                                <button type="button" class="btn btn-default btn-danger btn-sm" data-toggle="modal"
                                        data-target="#modal-delete"
                                        data-url="{{ route('role.destroy', $role->id) }}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            @endcan
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </section>
    @endcan

    @include('pengelolaan_akun.modal_role')
    @include('pengelolaan_akun.modal_user')

    {{--  Modal delete confirmation --}}
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
    @stack('modal-scripts')

    <script type="module">
        $(document).ready(function () {
            const tableProps = {
                dom: 'flrtip',
                columnDefs: [
                    {orderable: false, targets: -1}
                ],
                language: {
                    search: "Cari:",
                    lengthMenu: "Tampilkan _MENU_",
                    info: "Menampilkan _START_ sampai _END_ dari _TOTAL_",
                    infoEmpty: "Menampilkan 0 sampai 0 dari 0",
                    infoFiltered: "(difilter dari _MAX_ total)",
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
            };

            $('#table-user').dataTable($.extend(true, {}, tableProps, {
                language: $.extend(true, {}, tableProps.language, { search: "Cari Akun:", lengthMenu: "Tampilkan _MENU_ akun", info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ akun" })
            }));

            $('#table-role').dataTable($.extend(true, {}, tableProps, {
                language: $.extend(true, {}, tableProps.language, { search: "Cari Role:", lengthMenu: "Tampilkan _MENU_ role", info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ role" })
            }));
        });
    </script>
@endsection




