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
            <!-- Button to toggle fullscreen mode -->
            <button id="toggleFullscreen" class="btn btn-primary">Toggle Fullscreen</button>
            <!-- Fullscreen Container -->
            <div id="fullscreenContainer">
              <center>
                <h4>JADWAL LAINNYA</h4>
              </center>
              <br>
              <div class="table-responsive">
                <table id="scheduleTable" class="table table-bordered">
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
                    @foreach($schedules as $index => $schedule)
                    <tr>
                      <td>{{ $index + 1 }}</td>
                      <td>{{ $schedule->type }}</td>
                      <td>{{ $schedule->date }}</td>
                      <td>{{ $schedule->waktu_mulai }}</td>
                      <td>{{ $schedule->waktu_akhir }}</td>
                      <td>{{ $schedule->dosen }}</td>
                      <td>{{ $schedule->room }}</td>
                      <td>{{ $schedule->description }}</td>
                    </tr>
                    @endforeach
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
    $(document).ready(function() {
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
          $('.fullscreen').removeClass('fullscreen'); // Ensure to remove class when exiting fullscreen
          body.classList.remove('fullscreen-mode'); // Remove fullscreen-mode class from body
        }
      }

      $('#toggleFullscreen').click(function() {
        toggleFullscreen(document.getElementById('fullscreenContainer')); // Now targets the container including the heading
      });

      document.addEventListener('fullscreenchange', () => {
        if (!document.fullscreenElement) {
          $('.fullscreen').removeClass('fullscreen'); // Ensure to remove class when exiting fullscreen
          body.classList.remove('fullscreen-mode'); // Remove fullscreen-mode class from body
        }
      });
    });
  </script>
</body>
</html>
