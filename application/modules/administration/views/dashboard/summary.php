<?php 
	$queue_total = number_format($this->reports_model->get_queue_total(), 0, '.', ',');
	$daily_balance = number_format($this->reports_model->get_daily_balance(), 0, '.', ',');
	$patients_today = number_format($this->reports_model->get_patients_total(), 0, '.', ',');
 ?>
 <div class="row">
    <div class="col-md-4 col-lg-4 col-xl-4">
        <section class="panel panel-featured-left panel-featured-tertiary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-tertiary">
                            <i class="fa fa-shopping-cart"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Queue total</h4>
                            <div class="info">
                                <strong class="amount"><?php echo $queue_total;?></strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <!--<a class="text-muted text-uppercase">(statement)</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-4 col-lg-4 col-xl-4">
        <section class="panel panel-featured-left panel-featured-quartenary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-quartenary">
                            <i class="fa fa-user"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Patients today</h4>
                            <div class="info">
                                <strong class="amount"><?php echo $patients_today;?></strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <!--<a class="text-muted text-uppercase" href="<?php echo base_url()."data/reports/patients.php";?>">(report)</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
    <div class="col-md-4 col-lg-4 col-xl-4">
        <section class="panel panel-featured-left panel-featured-secondary">
            <div class="panel-body">
                <div class="widget-summary">
                    <div class="widget-summary-col widget-summary-col-icon">
                        <div class="summary-icon bg-secondary">
                            <i class="fa fa-usd"></i>
                        </div>
                    </div>
                    <div class="widget-summary-col">
                        <div class="summary">
                            <h4 class="title">Available cash</h4>
                            <div class="info">
                                <strong class="amount">KSH <?php echo $daily_balance;?></strong>
                            </div>
                        </div>
                        <div class="summary-footer">
                            <!--<a class="text-muted text-uppercase">(withdraw)</a>-->
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</div>
          
        <script type="text/javascript">
			var config_url = $('#config_url').val();

//Get patients per day for the last 7 days
$.ajax({
	type:'POST',
	url: config_url+"administration/charts/latest_patient_totals",
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
	url: config_url+"administration/charts/queue_total",
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
	url: config_url+"administration/charts/payment_methods",
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
});
		</script>