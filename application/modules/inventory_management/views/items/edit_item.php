<?php
if($item > 0)
{
	$query = $this->items_model->get_item($item_id);
	$item = $query->result();
	
	$item_name = $item[0]->item_name;
}
?>
<section class="panel">

                <header class="panel-heading">
                	<div class="row">
	                	<div class="col-md-6">
		                    <h2 class="panel-title">Edit <?php echo $item_name; ?></h2>

		                </div>
		                <div class="col-md-6">
		                		<a href="<?php echo site_url().'inventory/item';?>" class="btn btn-sm btn-info pull-right">Back to Items</a>
		                </div>
	                </div>
                </header>
                <div class="panel-body">
                    <div class="row">
                    	<div class="col-md-12">
                        	<div class="tabs">
								<ul class="nav nav-tabs nav-justified">
									<li class="active">
										<a class="text-center" data-toggle="tab" href="#general"><i class="fa fa-user"></i> General Asset details</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#inventory">Inventory</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#purchases">Purchases</a>
									</li>
									<li>
										<a class="text-center" data-toggle="tab" href="#sales">Sales</a>
									</li>
								</ul>
								<div class="tab-content">
									<div class="tab-pane active" id="general">
										<?php echo $this->load->view('edit/about', '', TRUE);?>
									</div>
									<div class="tab-pane" id="inventory">
										<?php echo $this->load->view('edit/inventory', '', TRUE);?>
									</div>
									<div class="tab-pane" id="purchases">
										<?php echo $this->load->view('edit/purchases', '', TRUE);?>
									</div>
									<div class="tab-pane" id="sales">
										<?php echo $this->load->view('edit/sales', '', TRUE);?>
									</div>
								</div>
							</div>
                        </div>
                    </div>
                </div>
            </section>