<!-- Today status. jQuery Sparkline plugin used. -->
<?php echo $this->load->view('dashboard/summary', '', TRUE);?>
<!-- Today status ends -->

<div class="row">
	<div class="col-md-12">
	<?php echo $this->load->view('dashboard/line_graph', '', TRUE);?>
	</div>
</div>  

<!-- Dashboard Graph starts -->
<?php echo $this->load->view('dashboard/bar_graph', '', TRUE);?>
<!-- Dashboard graph ends -->

<!-- Calendar and Logs -->
<div class="row">
	<div class="col-md-6">
	<?php echo $this->load->view('dashboard/calender', '', TRUE);?>
	</div>
	<div class="col-md-6">
	<?php echo $this->load->view('dashboard/logs', '', TRUE);?>
	</div>
</div>