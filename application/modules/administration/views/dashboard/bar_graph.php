
    <section class="panel panel-featured panel-featured-info">
        <header class="panel-heading">
            <h2 class="panel-title">Total Collected for <?php echo date('d/m/Y');?></h2>
        </header>    

          <!-- Widget content -->
                <div class="panel-body">
                    <!-- Bar chart (Blue color). jQuery Flot plugin used. -->
                    <div id="bar-chart"></div>
                    <hr />

        </div>
    
    </section>
<script type="text/javascript">
	
var config_url = $('#config_url').val();
/* Bar Chart starts */
$.ajax({
	type:'POST',
	url: config_url+"administration/charts/service_type_totals",
	cache:false,
	contentType: false,
	processData: false,
	dataType: "json",
	success:function(data){
		
		var result_bars = data.bars;
		var result_names = data.names;
		var total_services = data.total_services;
		
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
			for(r = 0; r < parseInt(total_services); r += 1)
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
					xaxis: {axisLabel: "Services", ticks: ticks},
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
		alert("XMLHttpRequest=" + xhr.responseText + "\ntextStatus=" + status + "\nerrorThrown=" + error);
	}
});
/* Bar chart ends */
</script>