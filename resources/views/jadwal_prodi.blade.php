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
                <h4>JADWAL PRODI</h4>
              </center>
              <br>
              <div class="table-responsive">
                <table id="scheduleTable" class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Hari/Tanggal</th>
                      <th>Waktu Mulai</th>
                      <th>Waktu Berakhir</th>
                      <th>Mata Kuliah</th>
                      <th>SKS</th>
                      <th>Kelas</th>
                      <th>Semester</th>
                      <th>Dosen</th>
                      <th>Ruangan</th>
                      <th>Keterangan</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach($groupedSchedules as $day => $schedulesForDay)
                    @foreach($schedulesForDay as $index => $schedule)
                    <tr data-schedule-id="{{ $schedule->id }}" data-day="{{ $day }}">
                      <td class="day-cell">{{ $day }}</td>
                      <td>{{ $schedule->start_time }}</td>
                      <td>{{ $schedule->end_time }}</td>
                      <td>{{ $schedule->course }}</td>
                      <td>{{ $schedule->credits }}</td>
                      <td>{{ $schedule->class }}</td>
                      <td>{{ $schedule->semester }}</td>
                      <td>{{ $schedule->lecturer }}</td>
                      <td>{{ $schedule->room }}</td>
                      <td>@linkify($schedule->description)</td>
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

      const columnNames = ['start_time', 'end_time', 'course', 'credits', 'class', 'semester', 'lecturer', 'room', 'description'];

      // Function to make non-day cells editable
      $('#scheduleTable tbody tr td:not(.day-cell)').on('dblclick', function() {
        makeEditable(this);
      });

      // Function to make a cell editable
      function makeEditable(td) {
        var originalText = td.innerText;
        var columnIndex = $(td).index() - 1; // Adjust for the day-cell being the first column
        var columnName = columnIndex >= 0 ? columnNames[columnIndex] : ''; // Ensure day-cell is not considered

        if (columnName) { // Proceed only if columnName is valid
          var input = document.createElement('input');
          input.type = 'text';
          input.value = originalText;
          input.style.width = '100%';
          td.innerHTML = '';
          td.appendChild(input);
          input.focus();

          input.onblur = function() {
            var scheduleId = $(td).closest('tr').data('schedule-id');
            saveChanges(td, input.value, originalText, scheduleId, columnName);
          };
        }
      }

      // Function to make day cells editable
      $('td.day-cell').dblclick(function() {
        var currentDay = $(this).text().trim();
        var dayInput = $('<input>', {
          'type': 'text',
          'class': 'form-control day-input',
          'value': currentDay
        }).keyup(function(e) {
          if (e.key === 'Enter') {
            $(this).blur();
          }
        });

        $(this).html(dayInput);
        dayInput.focus();
      });


      // Save changes function with AJAX call for non-day cells
      function saveChanges(td, newValue, originalValue, scheduleId, columnName) {
        if (newValue !== originalValue) {
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
            .then(response => response.json())
            .then(data => {
              if (data.success) {
                // Convert any URLs in newValue to clickable links before updating
                const linkedText = linkify(newValue);
                td.innerHTML = linkedText; // Use innerHTML to insert as HTML
              } else {
                throw new Error(data.message);
              }
            })
            .catch(error => {
              console.error('Error:', error);
              td.innerHTML = linkify(originalValue); // Convert back to clickable links if needed
            });
        } else {
          td.innerHTML = originalValue; // Revert if no change
        }
      }

      // AJAX request to update the day
      function updateDay(scheduleId, newDay, td) {
        $.ajax({
          url: `/schedule/${scheduleCategory}/updateDay`,
          type: 'POST',
          data: {
            id: scheduleId,
            newDay: newDay,
            _token: $('meta[name="csrf-token"]').attr('content'),
          },
          success: function(response) {
            td.empty().text(newDay);
          },
          error: function(xhr, status, error) {
            console.error("Error updating day: ", error);
            td.empty().text(td.data('originalDay')); // Revert to original day if update fails
          }
        });
      }

      // Function to convert plain URLs in text into clickable links
      function linkify(inputText) {
        const urlRegex = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;
        return inputText.replace(urlRegex, function(url) {
          return '<a href="' + url + '" target="_blank">' + url + '</a>';
        });
      }

      // Function to update the day on blur event of day-input
      $(document).on('blur', '.day-input', function() {
        var newDay = $(this).val().trim();
        var scheduleId = $(this).closest('tr').data('schedule-id');
        var td = $(this).closest('td');
        updateDay(scheduleId, newDay, td);
      });
    });
  </script>

</body>

</html>