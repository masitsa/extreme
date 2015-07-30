var config_url = $('#config_url').val();

function get_date(year, month, day) {
    return new Date(year, month - 1, day).getTime();
}

//Get patients per day for the last 7 days
/*$.ajax({
	type:'POST',
	url: config_url+"/charts/latest_patient_totals",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var bars = data.bars;
		var days_total = bars.split(',').map(function(item) {
			return parseInt(item, 10);
		});
		
		$("#patients_per_day").sparkline(days_total, {
			type: 'bar',
			height: data.highest_bar,
			barWidth: 4,
			barColor: '#fff'});
	},
	error: function(xhr, status, error) {
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});

//Get Revenue for the individual revenue types
$.ajax({
	type:'POST',
	url: config_url+"/charts/queue_total",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var bars = data.bars;
		var queue_total = bars.split(',').map(function(item) {
			return parseInt(item, 10);
		});
		
		$("#queue_total").sparkline(queue_total, {
			type: 'bar',
			height: data.highest_bar,
			barWidth: 4,
    		barColor: '#E25856'});
	},
	error: function(xhr, status, error) {
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});

//Get payment methods
$.ajax({
	type:'POST',
	url: config_url+"/charts/payment_methods",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var bars = data.bars;
		var queue_total = bars.split(',').map(function(item) {
			return parseInt(item, 10);
		});
		
		$("#payment_methods").sparkline(queue_total, {
			type: 'bar',
			height: data.highest_bar,
			barWidth: 4,
    		barColor: '#94B86E'});
	},
	error: function(xhr, status, error) {
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});*/

/* Orders chart starts */
//required variables
var highest_bar;
var pending = [], completed = [], cancelled = [];
var current_date;

//get the current day
var curr = new Date();
var day = curr.getDate();
var month = curr.getMonth()+1;
var year = curr.getFullYear();
var current_timestamp = get_date(year, month, day);
var url = config_url+"admin/charts/orders_totals/"+current_timestamp;//alert(url);
	
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
			pending.push([current_timestamp, data.pending]);
			completed.push([current_timestamp, data.completed]);
			cancelled.push([current_timestamp, data.cancelled]);
			
			current_timestamp = current_timestamp - 86400000;
			url = config_url+"admin/charts/orders_totals/"+current_timestamp;
		},
		error: function(xhr, status, error) {
			//alert(url);
			alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
		}
	});
}

//Plot the graph
$(function () {
	
	var plot = $.plot($("#curve-chart"), 
		[
			{
				data: pending,
				label: "Pending"
			},
			{
				data: completed, 
				label: "Completed"
			} ,
			{
				data: cancelled, 
				label: "Cancelled"
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
			yaxis: {axisLabel: "Total Orders"},
			colors: ["#fa3031", "#54728C", "#f0ad4e"]
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



/* Bar Chart starts */
$.ajax({
	type:'POST',
	url: config_url+"admin/charts/products_totals",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var result_bars = data.bars;
		var result_names = data.names;
		var total_categories = data.total_categories;
		
		var result2 = result_bars.split(',').map(function(item) {
			return parseInt(item, 10);
		});
		
		var names = result_names.split(',');
		
		$(function () {
		
			/* Bar Chart starts */
		
			var d1 = [];
			for (var i = 0; i <= 30; i += 1)
				d1.push([i, parseInt(Math.random() * 30)]);
		
			var d2 = [];
			for (var i = 0; i <= 30; i += 1)
				d2.push([i, parseInt(Math.random() * 30)]);
			
			var d3 = [];
			var ticks = [];
			for(r = 0; r < parseInt(total_categories); r += 1)
			{
				d3.push([r, parseInt(result2[r])]);
				ticks.push([r, names[r]]);
			}
		
			var curr = new Date();
			var day = curr.getDate();
			var month = curr.getMonth()+1;
			var year = curr.getFullYear();

			var stack = 0, bars = true, lines = false, steps = false;
			
			function plotWithOptions() {
				$.plot($("#bar-chart"), [ d3 ], {
					series: {
						stack: stack,
						lines: { show: lines, fill: true, steps: steps },
						bars: { show: bars, barWidth: 0.8 }
					},
					grid: {
						borderWidth: 0, hoverable: true, color: "#777"
					},
					colors: ["#52b9e9", "#0aa4eb"],
					bars: {
						  show: true,
						  lineWidth: 0,
						  fill: true,
						  fillColor: { colors: [ { opacity: 0.9 }, { opacity: 0.8 } ] }
					},
					xaxis: {axisLabel: "Categories", ticks: ticks},
					yaxis: {axisLabel: "Total Collected", 
						tickFormatter: function (v, axis) {
							return "KSH "+v;
						}
					}

				});
			}
		
			plotWithOptions();
			
			$(".stackControls input").click(function (e) {
				e.preventDefault();
				stack = $(this).val() == "With stacking" ? true : null;
				plotWithOptions();
			});
			$(".graphControls input").click(function (e) {
				e.preventDefault();
				bars = $(this).val().indexOf("Bars") != -1;
				lines = $(this).val().indexOf("Lines") != -1;
				steps = $(this).val().indexOf("steps") != -1;
				plotWithOptions();
			});
		
			/* Bar chart ends */
		
		});
	},
	error: function(xhr, status, error) {
		alert(config_url+"admin/charts/products_totals");
		//alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});
/* Bar chart ends */


/* Realtime chart starts */

/*$(function () {
    var data = [], totalPoints = 300;
    function getRandomData() {

        // zip the generated y values with the x values
        var res = [];
        for (var i = 0; i < totalPoints; ++i)
		{	
			var url = config_url+"admin/charts/usage_total";
			
			$.ajax({
				type:'POST',
				url: url,
				cache:false,
				contentType: false,
				processData: false,
				async: false,
				dataType: "json",
				success:function(data){
					
					var usage = parseInt(data.usage_total);
					
					res.push([i, usage]);
				},
				error: function(xhr, status, error) {
					//alert(url);
					alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
				}
			});
		}
        return res;
    }

    // setup control widget
    var updateInterval = 300;
    $("#updateInterval").val(updateInterval).change(function () {
        var v = $(this).val();
        if (v && !isNaN(+v)) {
            updateInterval = +v;
            if (updateInterval < 1)
                updateInterval = 1;
            if (updateInterval > 2000)
                updateInterval = 2000;
            $(this).val("" + updateInterval);
        }
    });

    // setup plot
    var options = {
        series: { shadowSize: 0 }, // drawing is faster without shadows
        lines: {fill: true},
        grid: {borderWidth:0 },
        yaxis: { min: 0, max: 100 },
        colors: ["#ff2424"]
    };
    var plot = $.plot($("#live-chart"), [ getRandomData() ], options);

    function update() {
        plot.setData([ getRandomData() ]);
        // since the axes don't change, we don't need to call plot.setupGrid()
        plot.draw();
        
        setTimeout(update, updateInterval);
    }

    update();
});*/

/* Realtime charts ends */