    <table class="table">
      <tr>
        <th>Title</th>
        <th>Speaker</th>
        <th>Time</th>
        <th>Date</th>
        <th>Place</th>
      </tr>
      <?php
      foreach ($events as $event) {
        $date = date_create($event['dt']);
            echo '<tr class="clickable-row" data-href="/events/view/' . $event['id'] . '" onclick="goToView(this)">';
            echo '<td>' . $event['title'] . '</td>';
            echo '<td>' . $event['speaker'] . '</td>';
            echo '<td>' . date_format($date, 'g:i A') . '</td>';
            echo '<td>' . date_format($date, 'm/d/y') . '</td>';
            echo '<td>' . $event['place'] . '</td></tr></a>';
      }
      ?>
    </table>
    <?php echo isset($create_button) ? $create_button : ''; ?>
  <script type="text/javascript">
  function goToView(e) {
    var link = e.getAttribute("data-href");
    window.document.location = link;
  }
  </script>
