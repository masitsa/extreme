<div class="row">
    <div class="col-md-6">
    <?php echo $this->load->view('dashboard/queue_chart', '', TRUE);?>
    </div>
    <div class="col-md-6">
    <?php echo $this->load->view('dashboard/queue_summary', '', TRUE);?>
    </div>
</div>

<!-- Calendar and Logs -->
<div class="row">
    <div class="col-md-12">
    <?php 
	if(!isset($doctor_appointments))
	{
		echo $this->load->view('administration/dashboard/calender', '', TRUE);
	}
	
	else
	{
		echo $this->load->view('doctor/doctor_appointments', '', TRUE);
	}
	?>
    </div>
</div>