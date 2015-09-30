<?php 
	$v_data['order_status_query'] = $order_status_query;
	echo $this->load->view('inventory/search/search_orders', '' , TRUE); ?>
<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
         <h2 class="panel-title pull-left"><?php echo $title;?></h2>
         <div class="widget-icons pull-right">
            	<a href="<?php echo base_url();?>inventory/add-order" class="btn btn-success btn-sm">Add Order</a>
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
			<?php echo $this->load->view('views/low_level', '' , TRUE); ?>
    </div>
</section>