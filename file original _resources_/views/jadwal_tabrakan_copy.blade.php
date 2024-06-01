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
              <h4>JADWAL BERTABRAKAN</h4>
            </center>
            <br />
            <br />
            <div class="row">
              <div class="col-md-12">
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
                  </tbody>
                </table>
              </div>
            </div>
            <hr />
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

  <script>
    $('#openTambah').click(function() {
      $('#myModalTambah').modal({
        show: true
      })
    });
  </script>
</body>

</html>
