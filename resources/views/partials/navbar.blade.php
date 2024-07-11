
@php
    $routeName = request()->route()->getName();
@endphp

<div class="col-md-2">
    <div class="list-group">
        <a class="list-group-item list-group-item-header">
            Menu Utama
        </a>

        @can('jadwal-saya')
            <a href="{{ route('jadwal-saya') }}"
               class="list-group-item {{ in_array($routeName, ['jadwal-saya', 'kalender']) ? 'active' : '' }}">
                <i class="fa fa-bookmark tab10" aria-hidden="true"></i>Jadwal Saya
            </a>
        @endcan

        @can('jadwal-tabrakan')
            <a href="{{ route('jadwal-tabrakan') }}"
               class="list-group-item {{ $routeName == 'jadwal-tabrakan' ? 'active' : '' }}">
                <i class="fa fa-warning tab10" aria-hidden="true"></i>Jadwal Bertabrakan
                @if(isset($collidingScheduleCount) && $collidingScheduleCount > 0)
                    <span class="badge badge-danger">{{ $collidingScheduleCount }}</span>
                @endif
            </a>
        @endcan

        @can('unggah-jadwal')
            <a href="{{ route('unggah-jadwal') }}"
               class="list-group-item {{ $routeName == 'unggah-jadwal' ? 'active' : '' }}">
                <i class="fa fa-upload tab10" aria-hidden="true"></i>Unggah Jadwal
            </a>
        @endcan

        @can('jadwal.export')
            <a href="{{ route('jadwal.export') }}" class="list-group-item {{ $routeName == 'jadwal.export' ? 'active' : '' }}">
                <i class="fa fa-download tab10" aria-hidden="true"></i>Unduh Jadwal
            </a>
        @endcan

        @can('jadwal-prodi.index')
            <a href="{{ route('jadwal-prodi.index') }}"
               class="list-group-item {{ $routeName == 'jadwal-prodi.index' ? 'active' : '' }}">
                <i class="fa fa-calendar tab10" aria-hidden="true"></i>Jadwal Prodi
            </a>
        @endcan

        @can('jadwal-tpb.index')
            <a href="{{ route('jadwal-tpb.index') }}"
               class="list-group-item {{ $routeName == 'jadwal-tpb.index' ? 'active' : '' }}">
                <i class="fa fa-calendar tab10" aria-hidden="true"></i>Jadwal TPB
            </a>
        @endcan

        @can('jadwal-ta.index')
            <a href="{{ route('jadwal-ta.index') }}"
               class="list-group-item {{ $routeName == 'jadwal-ta.index' ? 'active' : '' }}">
                <i class="fa fa-calendar tab10" aria-hidden="true"></i>Jadwal TA
            </a>
        @endcan

        @can('jadwal-lain.index')
            <a href="{{ route('jadwal-lain.index') }}"
               class="list-group-item {{ $routeName == 'jadwal-lain.index' ? 'active' : '' }}">
                <i class="fa fa-calendar tab10" aria-hidden="true"></i>Jadwal Lainnya
            </a>
        @endcan

        @can('pengelolaan-akun')
            <a href="{{ route('pengelolaan-akun') }}"
               class="list-group-item {{ $routeName == 'pengelolaan-akun' ? 'active' : '' }}">
                <i class="fa fa-users tab10" aria-hidden="true"></i>Pengelolaan Akun
            </a>
        @endcan

        @can('perubahan-jadwal.create')
            <a href="{{ route('perubahan-jadwal.create') }}"
               class="list-group-item {{ $routeName == 'perubahan-jadwal.create' ? 'active' : '' }}">
                <i class="fa fa-send tab10" aria-hidden="true"></i>Permintaan Perubahan Jadwal
            </a>
        @endcan

        @can('perubahan-jadwal.index')
            <a href="{{ route('perubahan-jadwal.index') }}"
            class="list-group-item {{ $routeName == 'perubahan-jadwal.index' ? 'active' : '' }}">
                <i class="fa fa-reorder tab10" aria-hidden="true"></i>Daftar Permintaan Perubahan Jadwal
                @if(isset($pengajuanCount) && $pengajuanCount > 0)
                    <span class="badge badge-danger">{{ $pengajuanCount }}</span>
                @endif
            </a>
        @endcan

        @can('pengaturan.index')
            <a href="{{ route('pengaturan.index') }}"
               class="list-group-item {{ $routeName == 'pengaturan.index' ? 'active' : '' }}">
                <i class="fa fa-cog tab10" aria-hidden="true"></i>Pengaturan
            </a>
        @endcan

        @can('password.edit')
            <a href="{{ route('password.edit') }}"
               class="list-group-item {{ $routeName == 'password.edit' ? 'active' : '' }}">
                <i class="fa fa-key tab10" aria-hidden="true"></i>Ubah Password
            </a>
        @endcan
    </div>
</div>
