@extends('layouts.app')

@section('content')
    <div class="container mb-">
        @include('partials.alert')
        <div class="d-flex justify-content-between align-items-center">
            <h3>Pengaturan Aplikasi</h3>
        </div>

        <form action="{{ route('pengaturan.update') }}" method="POST">
            @csrf
            @method('PATCH')
            @foreach($pengaturans as $pengaturan)
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="form-group">
                            <label
                                class="form-check-label">{{ ucwords(str_replace("_", " ", $pengaturan->key)) }} | Format (YYYYMMDD)</label>
                            <input type="text" name="{{ $pengaturan->key }}"
                                   class="form-control @error($pengaturan->key) is-invalid @enderror"
                                   value="{{ $pengaturan->value }}">
                            <small>{{ $pengaturan->description }}</small>
                            @error($pengaturan->key)
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                            @enderror
                        </div>
                    </div>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        </form>
    </div>

    <div class="container mt-5">
        <h3>Kosongkan Data Jadwal</h3>
        <p><strong>PERINGATAN:</strong> Hal ini akan mengapus semua jadwal yang ada pada sistem.</p>
        <button data-toggle="modal" data-target="#modal-konfirmasi" class="btn btn-danger">Kosongkan Data Jadwal
        </button>
    </div>

    <div class="modal" id="modal-konfirmasi" tabindex="-1" aria-labelledby="exampleModalLabel"
         aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{route('pengaturan.clear-data')}}" method="GET">
                    <div class="modal-header">
                        <h5 class="modal-title">Konfirmasi Pengkosongan Data</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        Apakah anda yakin akan <strong>mengosongkan semua data jadwal?</strong>
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

