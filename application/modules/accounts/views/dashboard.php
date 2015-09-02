<!-- Today status. jQuery Sparkline plugin used. -->
<div class="row">
	<div class="col-md-12">
		<?php echo $this->load->view('administration/dashboard/summary', '', TRUE);?>
	</div>
</div>
<!-- Today status ends -->
<!-- Dashboard Graph starts -->
<div class="row">
	<div class="col-md-12">
	<?php echo $this->load->view('administration/dashboard/bar_graph', '', TRUE);?>
	</div>
</div>
<!-- Dashboard graph ends -->
<div class="row">
	<div class="col-md-12">
	<?php echo $this->load->view('administration/dashboard/line_graph', '', TRUE);?>
	</div>
</div>  



