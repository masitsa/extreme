<?php 
	//$v_data['request_status_query'] = $request_status_query;
	echo $this->load->view('inventory/search/search_requests', '' , TRUE); ?>
<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
         <h2 class="panel-title pull-left"><?php echo $title;?></h2>
         <div class="widget-icons pull-right">
            	<a href="<?php echo base_url();?>inventory/add-requests" class="btn btn-success btn-sm">Add request</a>
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
			<?php echo $this->load->view('views/request_level', '' , TRUE); ?>
    </div>
</section>