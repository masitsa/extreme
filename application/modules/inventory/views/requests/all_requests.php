<?php 
	$v_data['status_query'] = $status_query;
	echo $this->load->view('inventory/search/search_requests', $v_data , TRUE); ?>
<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
         <h2 class="panel-title pull-left"><?php echo $title;?></h2>
         <div class="widget-icons pull-right">
            	<a href="<?php echo base_url();?>inventory/add-requests" class="btn btn-success btn-sm">Add Request</a>
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
    <?php
    $search = $this->session->userdata('request_search');
		
	if(!empty($search))
	{
		echo '
		<a href="'.site_url().'requests/close-request-search" class="btn btn-warning btn-sm ">Close Search</a>
		';
	}
	
	?>
			<?php echo $this->load->view('views/request_level', '' , TRUE); ?>
    </div>
</section>