@extends('layouts.app')

@section('content')
<div class="box-body">
    <h4>UNGGAH JADWAL</h4>
    <p>Pastikan jadwal yang ingin diunggah sudah sesuai dengan format dan tujuan yang ada, format bisa diunduh melalui link ini: <a href="https://drive.google.com/drive/folders/1CkUmra_t2lXf00KNhVeUc4672elo00f_?usp=sharing" target="_blank">Download Format Jadwal</a></p>
    
    @if(session('error'))
    <div class="alert alert-danger">
        {{ session('error') }}
    </div>
    @endif
    @if(session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
    @endif
    
    <form action="{{ route('unggah-jadwal.store') }}" method="POST" enctype="multipart/form-data" class="form-horizontal">
        @csrf
        <div class="form-group">
            <div class="col-sm-5">
                <input class="form-control input-sm" name="file" type="file" id="document" accept=".xlsx, .xlsm" required aria-label="Unggah Dokumen"/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-3 control-label">Unggah ke jadwal</div>
            <div class="col-md-5">
                <select class="form-control input-sm" name="category" id="category" required>
                    <option value="" selected="selected">- Pilih Jenis Jadwal -</option>
                    @can('jadwal-tpb.store')
                        <option value="TPB">TPB</option>
                    @endcan
                    @can('jadwal-prodi.store')
                        <option value="Prodi">Prodi</option>
                    @endcan
                    @can('jadwal-ta.store')
                        <option value="TA">TA</option>
                    @endcan
                    @can('jadwal-lain.store')
                        <option value="Lain">Lain</option>
                    @endcan
                </select>
                <br/>
            </div>
        </div>
        <div class="form-group">
            <div class="col-md-10">
                <button type="submit" class="btn btn-success pull-left" id="confirm-btn" disabled><i class="fa fa-save"></i> Kirim Permintaan
                </button>
            </div>
        </div>
    </form>
    <!-- Success Message Modal -->
    <div class="modal fade" id="successModal" tabindex="-1" role="dialog" aria-labelledby="modalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalLabel">Success</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    {{ session('success') }}
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    @if(session('success'))
    <script>
        $(document).ready(function () {
            $('#successModal').modal('show');
        });
    </script>
    @endif
</div>

<script>
    function checkFormValidity() {
        const category = document.getElementById('category').value;
        const confirmBtn = document.getElementById('confirm-btn');
        
        if (category !== "") {
            confirmBtn.disabled = false;
        } else {
            confirmBtn.disabled = true;
        }
    }

    document.getElementById('category').addEventListener('change', checkFormValidity);
</script>

@endsection
