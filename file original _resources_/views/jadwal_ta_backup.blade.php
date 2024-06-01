<!DOCTYPE html>
<html lang="en">

<head>
  @include('partials.top')
  <script>
    var scheduleCategory = "{{ $category }}";
  </script>
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
                <h4>JADWAL TA</h4>
              </center>
              <br>
              <div class="table-responsive">
                <table id="scheduleTable" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Hari/Tanggal</th>
                      <th>Jenis</th>
                      <th>Nama</th>
                      <th>NIM</th>
                      <th>Pembimbing 1</th>
                      <th>Pembimbing 2</th>
                      <th>Judul TA</th>
                      <th>Penguji 1</th>
                      <th>Penguji 2</th>
                      <th>Waktu Mulai</th>
                      <th>Waktu Berakhir</th>
                      <th>Ruangan</th>
                      <th>Link</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($groupedSchedules as $day => $schedulesForDay)
                    @foreach($schedulesForDay as $schedule)
                    <tr data-schedule-id="{{ $schedule->id }}">
                      <td>{{ $schedule->date }}</td> <!-- Display the textual date here -->
                      <td>{{ $schedule->type }}</td>
                      <td>{{ $schedule->name }}</td>
                      <td>{{ $schedule->nim }}</td>
                      <td>{{ $schedule->pembimbing1 }}</td>
                      <td>{{ $schedule->pembimbing2 }}</td>
                      <td>{{ $schedule->title }}</td>
                      <td>{{ $schedule->penguji1 }}</td>
                      <td>{{ $schedule->penguji2 }}</td>
                      <td>{{ $schedule->start_time }}</td>
                      <td>{{ $schedule->end_time }}</td>
                      <td>{{ $schedule->room }}</td>
                      <td>@linkify($schedule->link)</td>
                    </tr>
                    @endforeach
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
          element.classList.remove('fullscreen'); // Remove fullscreen class when exiting
          body.classList.remove('fullscreen-mode'); // Remove fullscreen-mode class from body
        }
      }

      $('#toggleFullscreen').click(function() {
        toggleFullscreen(document.getElementById('fullscreenContainer'));
      });

      document.addEventListener('fullscreenchange', () => {
        if (!document.fullscreenElement) {
          $('.fullscreen').removeClass('fullscreen');
          body.classList.remove('fullscreen-mode');
        }
      });

      const columnNames = ['date', 'type', 'name', 'nim', 'pembimbing1', 'pembimbing2', 'title', 'penguji1', 'penguji2', 'start_time', 'end_time', 'room', 'link'];

      $('#scheduleTable tbody tr td:not(:first-child)').on('dblclick', function() {
        makeEditable(this); // Enable cell editing on double click
      });

      function makeEditable(td) {
        var originalText = $(td).text();
        var columnIndex = $(td).parent().children().index(td);
        var scheduleId = $(td).closest('tr').data('schedule-id');

        // Adjust columnIndex to match your columnNames array, ensuring the first column is included
        var columnName = columnNames[columnIndex]; // Directly using columnIndex as it matches the array

        if (columnName) {
          var input = document.createElement('input');
          input.type = 'text';
          input.value = originalText;
          input.style.width = '100%'; // Ensure input covers cell width

          td.innerHTML = ''; // Clear cell for input
          td.appendChild(input);
          input.focus();

          input.onblur = function() {
            var newValue = input.value;

            if (newValue !== originalText) {
              saveChanges(td, newValue, scheduleId, columnName);
            } else {
              // Revert to original text if no change
              td.innerHTML = originalText;
            }
          };

          input.onkeydown = function(e) {
            if (e.key === 'Enter') {
              // Save changes when 'Enter' is pressed
              input.blur();
            }
          };
        }
      }

      function saveChanges(td, newValue, scheduleId, columnName) {
        console.log(`Attempting to save changes for scheduleId: ${scheduleId}, Column: ${columnName}, New Value: ${newValue}`);

        fetch(`/schedule/${scheduleCategory}/update`, {
            method: 'POST',
            headers: {
              'Content-Type': 'application/json',
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            body: JSON.stringify({
              id: scheduleId,
              newValue: newValue,
              columnName: columnName,
            })
          })
          .then(response => {
            if (!response.ok) {
              throw new Error('Network response was not ok');
            }
            return response.json();
          })
          .then(data => {
            if (data.success) {
              console.log('Update successful:', data.message);
              const linkedText = linkify(newValue);
              $(td).html(linkedText); // Update the cell content if successful
            } else {
              console.log('Update failed:', data.message);
              throw new Error(data.message);
            }
          })
          .catch(error => {
            console.error('Error during update:', error);
            $(td).text(originalValue); // Revert to original value on error
          });
      }


      // Function to convert plain URLs in text into clickable links
      function linkify(inputText) {
        const urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        return inputText.replace(urlRegex, function(url) {
          return '<a href="' + url + '" target="_blank">' + url + '</a>';
        });
      }

    });
  </script>
</body>

</html>