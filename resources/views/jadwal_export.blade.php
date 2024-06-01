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

    .switch_box {
      display: -webkit-box;
      display: -ms-flexbox;
      display: flex;
      max-width: 200px;
      min-width: 200px;
      height: 200px;
      -webkit-box-pack: center;
      -ms-flex-pack: center;
      justify-content: center;
      -webkit-box-align: center;
      -ms-flex-align: center;
      align-items: center;
      -webkit-box-flex: 1;
      -ms-flex: 1;
      flex: 1;
    }


    input[type="checkbox"].switch_1 {
      font-size: 14px;
      -webkit-appearance: none;
      -moz-appearance: none;
      appearance: none;
      width: 3.5em;
      height: 1.5em;
      background: #ddd;
      border-radius: 3em;
      position: relative;
      cursor: pointer;
      outline: none;
      -webkit-transition: all .2s ease-in-out;
      transition: all .2s ease-in-out;
    }

    input[type="checkbox"].switch_1:checked {
      background: #007dea;
    }

    input[type="checkbox"].switch_1:after {
      position: absolute;
      content: "";
      width: 1.5em;
      height: 1.5em;
      border-radius: 50%;
      background: #fff;
      -webkit-box-shadow: 0 0 .25em rgba(0, 0, 0, .3);
      box-shadow: 0 0 .25em rgba(0, 0, 0, .3);
      -webkit-transform: scale(.7);
      transform: scale(.7);
      left: 0;
      -webkit-transition: all .2s ease-in-out;
      transition: all .2s ease-in-out;
    }

    input[type="checkbox"].switch_1:checked:after {
      left: calc(100% - 1.5em);
    }

    .is-invalid .select2-selection,
    .needs-validation~span>.select2-dropdown {
      border-color: red !important;
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
              <h4>UNDUH JADWAL</h4>
            </center>
            <br>
            <p>Silahkan unduh jadwal sesuai jenis yang anda inginkan dan juga tanggal mulai dan berakhirnya.</p>

            <form action="{{ route('jadwal.export') }}" method="post" novalidate>
              @csrf
              <div class="row">
                <div class="col">
                  <div class="form-horizontal">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="jenis-jadwal">Jenis Jadwal</label>
                        <select name="jenis_jadwal" id="jenis-jadwal" class="form-control select2" required>
                            <option value="">--Pilih Jadwal--</option>
                            @can('jadwal-prodi.store')
                            <option value="0" {{ 0 === old('jenis_jadwal') ? 'selected' : '' }}>Jadwal Prodi</option>
                            @endcan
                            @can('jadwal-tpb.store')
                            <option value="1" {{ 1 === old('jenis_jadwal') ? 'selected' : '' }}>Jadwal TPB</option>
                            @endcan
                            @can('jadwal-ta.store')
                            <option value="2" {{ 2 === old('jenis_jadwal') ? 'selected' : '' }}>Jadwal TA</option>
                            @endcan
                            @can('jadwal-lain.store')
                            <option value="3" {{ 3 === old('jenis_jadwal') ? 'selected' : '' }}>Jadwal Lain</option>
                            @endcan
                        </select>
                        @error('jenis_jadwal')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="dari-tanggal">Dari Tanggal</label>
                        <input type="date" name="dari_tanggal" id="dari-tanggal" class="form-control @error('dari_tanggal') is-invalid @enderror" min="{{ $minDate }}" max="{{ $maxDate }}" required>
                        @error('dari_tanggal')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                    </div>

                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="sampai-tanggal">Sampai Tanggal</label>
                        <input type="date" name="sampai_tanggal" id="sampai-tanggal" class="form-control @error('sampai_tanggal') is-invalid @enderror" min="{{ $minDate }}" max="{{ $maxDate }}" required>
                        @error('sampai_tanggal')
                        <small class="text-danger">{{ $message }}</small>
                        @enderror
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div class="row">
                <div class="col">
                  <div class="form-group">
                    <button type="submit" class="btn btn-success pull-left">
                      <i class="fa fa-save"></i>
                      Unduh Jadwal
                    </button>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <hr />
        </div>
      </div>
    </div>
  </div>

  <!-- <div id="myModalTambah" class="modal fade" tabindex="-1" role="dialog">
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
  </div> -->

  @include('partials.bottom')

</body>

</html>