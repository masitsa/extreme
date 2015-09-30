
    <section class="panel panel-featured panel-featured-info">
        <header class="panel-heading">
            <h2 class="panel-title">Appointments</h2>
        </header>            

        <!-- Widget content -->
        <div class="panel-body">
            <!-- Below line produces calendar. I am using FullCalendar plugin. -->
            <div id="appointments"></div>
        </div>
    
    </section>

<script type="text/javascript">
$(document).ready(function() {
	var config_url = $('#config_url').val();
	var date = new Date();
    var d = date.getDate();
    var m = date.getMonth();
    var y = date.getFullYear();
  $.ajax({
	type:'POST',
	url: config_url+"reception/get_appointments",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var appointments = [];
		var total_events = parseInt(data.total_events, 10);

		for(i = 0; i < total_events; i++)
		{
			var data_array = [];
			
			data_title = data.title[i];
			data_start = data.start[i];
			data_end = data.end[i];
			data_backgroundColor = data.backgroundColor[i];
			data_borderColor = data.borderColor[i];
			data_allDay = data.allDay[i];
			data_url = data.url[i];
			
			//add the items to an array
			data_array.title = data_title;
			data_array.start = data_start;
			data_array.end = data_end;
			data_array.backgroundColor = data_backgroundColor;
			data_array.borderColor = data_borderColor;
			data_array.allDay = data_allDay;
			data_array.url = data_url;
			//console.log(data_array);
			appointments.push(data_array);
		}
		console.log(appointments);
		/*for(var i in data){
			appointments.push([i, data [i]]);alert(data[i]);
		}*/
		
		$('#appointments').fullCalendar({
			  header: {
				left: 'prev',
				center: 'title',
				right: 'month,agendaWeek,agendaDay,next'
			  },
			  editable: true,
			  events: appointments
			});
	},
	error: function(xhr, status, error) {
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});

});
</script>