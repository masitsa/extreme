	<section class="panel panel-featured panel-featured-info">
        <header class="panel-heading">
            <h2 class="panel-title">Total visits for the last 7 days</h2>
        </header>   

          <!-- Widget content -->
                <div class="panel-body">
                    <!-- Curve chart -->

                    <div id="curve-chart"></div>

                    <div id="curve-chart2"></div>
                    <hr />
                    <!-- Hover location -->
                    <div id="hoverdata">Mouse hovers at
                    (<span id="x">0</span>, <span id="y">0</span>). <span id="clickdata"></span></div>          

                    <!-- Skil this line. <div class="uni"><input id="enableTooltip" type="checkbox">Enable tooltip</div> -->

        </div>
    
    </section>
<script type="text/javascript">
var config_url = $('#config_url').val();

function get_date(year, month, day) {
    return new Date(year, month - 1, day).getTime();
}

/* Patients chart starts */
//required variables
var highest_bar;
var inpatients = [], outpatients = [];
var current_date;

//get the current day
var d = new Date();
var current_timestamp = d.getTime();
//var current_timestamp = get_date(year, month, day);
var url = config_url+"administration/charts/patient_type_totals/"+current_timestamp;
	
//get data for the last 7 days
for(r = 0; r < 8; r++)
{
	$.ajax({
		type:'POST',
		url: url,
		cache:false,
		contentType: false,
		processData: false,
		async: false,
		dataType: "json",
		success:function(data){
			
			//add the data to the array
			inpatients.push([current_timestamp, data.inpatients]);
			outpatients.push([current_timestamp, data.outpatients]);
			
			current_timestamp = current_timestamp - 86400000;
			url = config_url+"administration/charts/patient_type_totals/"+current_timestamp;
		},
		error: function(xhr, status, error) {
			alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		}
	});
}

//Plot the graph
$(function () {
	
	var plot = $.plot($("#curve-chart"), 
		[
			{
				data: outpatients,
				label: "Outpatients"
			},
			{
				data: inpatients, 
				label: "Inpatients"
			}
		],
		
		{
		   series: {
			   lines: { show: true, 
						fill: true,
						fillColor: {
						  colors: [{
							opacity: 0.05
						  }, {
							opacity: 0.01
						  }]
					  }
			  },
			   points: { show: true }
		   },
			grid: { hoverable: true, clickable: true, borderWidth:0 },
			xaxis: {mode: "time",timeformat: "%d/%m/%y", axisLabel: "Day"},
			yaxis: {axisLabel: "Total Patients"},
			colors: ["#fa3031", "#54728C", "#94B86E", "#f0ad4e"]
		}
	);
	
	function showTooltip(x, y, contents) {
		$('<div id="tooltip">' + contents + '</div>').css( {
			position: 'absolute',
			display: 'none',
			top: y + 5,
			width: 100,
			left: x + 5,
			border: '1px solid #000',
			padding: '2px 8px',
			color: '#ccc',
			'background-color': '#000',
			opacity: 0.9
		}).appendTo("body").fadeIn(200);
	}

	var previousPoint = null;
	$("#curve-chart").bind("plothover", function (event, pos, item) {
		var myDate = new Date(Math.round(pos.x.toFixed(2)));
		var date_display = new Date(myDate.getFullYear(), myDate.getMonth(), myDate.getDate());
					
		$("#x").text(date_display.toString());
		$("#y").text(pos.y.toFixed(2));

			if (item) {
				if (previousPoint != item.dataIndex) {
					previousPoint = item.dataIndex;
					
					$("#tooltip").remove();
					var x = item.datapoint[0].toFixed(2),
						y = item.datapoint[1].toFixed(2);
					y = Math.round(y);
					var myDate = new Date(Math.round(x));
					var date_display = new Date(myDate.getFullYear(), myDate.getMonth(), myDate.getDate());
					
					showTooltip(item.pageX, item.pageY, item.series.label + " of " + date_display.toString() + " = " + y);
				}
			}
			else {
				$("#tooltip").remove();
				previousPoint = null;            
			}
	}); 

	$("#curve-chart").bind("plotclick", function (event, pos, item) {
		if (item) {
			var x = item.datapoint[0].toFixed(2),
				y = item.datapoint[1].toFixed(2);
			y = Math.round(y);
			var myDate = new Date(Math.round(x));
			var date_display = new Date(myDate.getFullYear(), myDate.getMonth(), myDate.getDate());
					
			$("#clickdata").text("You clicked " + y + " in " + item.series.label + ".");
			plot.highlight(item.series, item.datapoint);
		}
	});

});
/* Patients chart ends */
</script>