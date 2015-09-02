<?php echo $this->load->view('administration/dashboard/line_graph', '', TRUE);?>

<!-- Calendar and Logs -->
<div class="row">
    <div class="col-md-6">
    <?php echo $this->load->view('administration/dashboard/calender', '', TRUE);?>
    </div>
    <div class="col-md-6">
    <?php echo $this->load->view('patients/queue_summary', '', TRUE);?>
    </div>
</div>