<div class="container">
    <div class="row">
        <div class="col-md-12">

            <h1><?php echo $this->lang->line('title'); ?></h1>
            <!-- Display Our Calendar -->
            <div id="calendar"></div>

        </div>
    </div>
</div>

<!-- View Event -->
<div id="calendarModal" class="modal fade">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle" class="modal-title"></h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only"><?php echo $this->lang->line('close'); ?></span></button>
          </div>
          <div id="modalBody" class="modal-body"> </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $this->lang->line('remove'); ?></button>
            <button type="button" class="btn btn-warning" data-dismiss="modal" data-toggle="modal" href="#edit"><?php echo $this->lang->line('edit'); ?></button>
            <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
          </div>
      </div>
  </div>
</div>

<!-- Edit Event -->
<div id="edit" class="modal fade">
  <div class="modal-dialog">
      <div class="modal-content">
          <div class="modal-header">
            <h4 id="modalTitle" class="modal-title"><?php echo $this->lang->line('edit'); ?></h4>
            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span> <span class="sr-only"><?php echo $this->lang->line('close'); ?></span></button>
          </div>
          <div id="modalBody" class="modal-body"> </div>
          <div class="modal-footer">
          <button type="button" class="btn btn-danger" data-dismiss="modal"><?php echo $this->lang->line('remove'); ?></button>
            <button type="button" class="btn btn-warning" data-dismiss="modal" data-toggle="modal" href="#edit"><?php echo $this->lang->line('edit'); ?></button>
            <button type="button" class="btn btn-primary" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
          </div>
      </div>
  </div>
</div>

<!-- Add event -->
<div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title" id="myModalLabel"><?php echo $this->lang->line('add_event'); ?></h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      </div>
      <div class="modal-body">
        <?php 
          $attributes = array('class' => 'form-horizontal'); 
          echo form_open('calendar/add_event', $attributes); 
        ?>
        <div class="form-group row">
          <label for="name" class="col-4 col-form-label"><?php echo $this->lang->line('name'); ?></label> 
          <div class="col-8">
            <input id="name" name="name" type="text" class="form-control" value="">
          </div>
        </div>
        <div class="form-group row">
          <label for="description" class="col-4 col-form-label"><?php echo $this->lang->line('description'); ?></label> 
          <div class="col-8">
            <textarea id="description" name="description" cols="40" rows="5" class="form-control"></textarea>
          </div>
        </div>
        <div class="form-group row">
          <label for="start_date" class="col-4 col-form-label"><?php echo $this->lang->line('dateandtime'); ?></label> 
          <div class="col-8">
          <input id="datetimes" type="text" name="datetimes" class="form-control" value=""/>          
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('close'); ?></button>
        <input type="submit" class="btn btn-primary" value="<?php echo $this->lang->line('add_event'); ?>">
        <?php echo form_close() ?>
      </div>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
  var calendarEl = document.getElementById('calendar');
  var calendar = new FullCalendar.Calendar(calendarEl, {
    plugins: [ 'interaction', 'dayGrid', 'timeGrid' ],
    defaultView: 'dayGridMonth',
    selectable: true,
    height: 600,
    header: {
      left: 'prev,next today',
      center: 'title',
      right: 'dayGridMonth,timeGridWeek,timeGridDay'
    },

    eventClick: function(info) {
        $('#modalTitle').html(info.event.title);
        console.log(info.event.description);
        $('#modalBody').html(info.event.extendedProps.description);
        $('#calendarModal').modal();
    },

    dateClick: function(info) {
        var datum = info.dateStr.toString();

        // Herschik datums
        if(!datum.includes("T"))
        {
          var d = new Date();
          var hours = d.getHours();
          var eindHours = hours + 1;
          var aanvangsDatum = datum + " " + hours + ":00:00";
          var eindDatum = datum + " " + eindHours + ":00:00";
        }
        else
        {
          var datumSplitTijd = datum.split("T");
          var uurSplit = datumSplitTijd['1'].split(":");
          if(uurSplit['0'] < 23)
          {
            uurSplit['0'] = parseInt(uurSplit['0']) + 1
            if(uurSplit['0'] < 10)
            {
              uurSplit['0'] = "0" + uurSplit['0']
            }
          }
          var uurJoin = uurSplit.join(":");
          var newDatum = datumSplitTijd['0'] + "T" + uurJoin;
          var aanvangsDatum = datum;
          var eindDatum = newDatum;
        }

        $(function() {
            $('#datetimes').daterangepicker({
              timePicker: true,
              timePicker24Hour: true,
              timePickerIncrement: 30,
              startDate: aanvangsDatum,
              endDate: eindDatum,
              locale: {
                format: 'YYYY-MM-DD H:mm'
              }
            });
          });

        $('#addModal').modal();
    },

    eventSources: [
    {
      events: function(fetchInfo, successCallback, failureCallback) {
          $.ajax({
            url: '<?php echo base_url('calendar/get_events') ?>',
            dataType: 'json',
            data: {
              "start":fetchInfo.start.toDateString(),
              "end":fetchInfo.end.toDateString()
            },
            success: function(response) {
              successCallback(response);
            }
          });
      },
    }
    ],
  });
  calendar.setOption('locale', 'nl');
  calendar.render();
});
</script>