<?php echo $this->load->view('search/search_drugs', '', TRUE);?>
 <div class="row">
	<div class="col-md-12">
        <a href="<?php echo site_url().'pharmacy/add_drug/'.$page;?>" class="btn btn-sm btn-success pull-right">Add Drug</a>
        <a href="<?php echo site_url().'pharmacy/import-drugs/'.$page;?>" class="btn btn-sm btn-primary pull-right">Import Drugs</a>
	</div>
</div>

<div class="row">
    <div class="col-md-12">
        <section class="panel">
            <header class="panel-heading">
                <h2 class="panel-title"><?php echo $title;?></h2>
            </header>             
            
            <div class="panel-body">
				<div class="center-align">
					<?php
						$search = $this->session->userdata('drugs_inventory_search');

						if(!empty($search))
						{
							echo '<a href="'.site_url().'pharmacy/close_inventory_search" class="btn btn-warning">Close Search</a>';
						}
						$result = '';
						$error = $this->session->userdata('error_message');
						$success = $this->session->userdata('success_message');
						
						if(!empty($error))
						{
							echo '<div class="alert alert-danger">'.$error.'</div>';
							$this->session->unset_userdata('error_message');
						}
						
						if(!empty($success))
						{
							echo '<div class="alert alert-success">'.$success.'</div>';
							$this->session->unset_userdata('success_message');
						}
					?>
				</div>
			  
				<div class="row">
					<div class="col-md-12">
						
						<div class="table-responsive">
							<table border="0" class="table table-hover table-condensed table-striped table-bordered">
								<thead> 
									<th>#</th>
									<th>Name</th>
									<!--<th>Dosage</th>
									<th>Type</th>-->
									<th>Cash paying</th>
									<th>Insurance</th>
									<th>Opening</th>
									<th>P</th>
									<th>S</th>
									<th>D</th>
									<th>Stock</th>
								</thead>
					
								<?php 
								//echo "current - ".$current_item."end - ".$end_item;
								$count = $page;
								$rs9 = $query->result();
								foreach ($rs9 as $rs10) :
									$product_name = $rs10->product_name;
									$unit_of_measure = $rs10->unit_of_measure;
									$drug_type_name = $rs10->drug_type_name;
									$product_unitprice = $rs10->product_unitprice;
									$product_unitprice_insurance = $rs10->product_unitprice_insurance;
									$quantity = $rs10->quantity;
									$product_deleted = $rs10->product_deleted;
									$product_id = $rs10->product_id;
									$purchases = $this->pharmacy_model->item_purchases($product_id);
									$sales = $this->pharmacy_model->get_drug_units_sold($product_id);
									$deductions = $this->pharmacy_model->item_deductions($product_id);
									$in_stock = ($quantity + $purchases) - $sales - $deductions;
									$count++;
									
									if($product_deleted == 0)
									{
										$button = '<a href="'.site_url().'pharmacy/activation/deactivate/'.$page.'/'.$product_id.'" class="btn btn-sm btn-default" onclick="return confirm(\'Do you want to disable '.$product_name.'?\');">Disable</a>';
									}
									
									else
									{
										$button = '<a href="'.site_url().'pharmacy/activation/activate/'.$page.'/'.$product_id.'" class="btn btn-sm btn-danger" onclick="return confirm(\'Do you want to enable '.$product_name.'?\');">Enable</a>';
									}
								?>
							   <tr>
									<td><?php echo $count;?></td>
									<td><?php echo $product_name;?></td>
									<!--<td><?php echo $unit_of_measure;?></td>		
									<td><?php echo $drug_type_name;?></td>-->
									<td><?php echo $product_unitprice;?></td>				         
									<td><?php echo $product_unitprice_insurance;?></td>		         
									<td><?php echo $quantity;?></td>
									<td><?php echo $purchases;?></td>
									<td><?php echo $sales;?></td>						         
									<td><?php echo $deductions;?></td>
									<td><?php echo $in_stock;?></td>
									<td><a href="<?php echo site_url().'inventory_management/edit_product/'.$product_id.'/a';?>" class="btn btn-sm btn-primary">Edit</a></td>
									<!--<td><a href="<?php echo site_url().'pharmacy/drug_purchases/'.$product_id;?>" class="btn btn-sm btn-warning">Purchases</a></td>
									<td><a href="<?php echo site_url().'pharmacy/drug_deductions/'.$product_id;?>" class="btn btn-sm btn-info">Deductions</a></td>
									<td><?php echo $button;?></td>-->
								</tr>
								<?php endforeach;?>
							</table>
						</div>
					</div>
				</div>
                
            </div>
                
            <div class="widget-foot">
            <?php
            if(isset($links)){echo $links;}
            ?>
            </div>
 		</section>
    </div>
</div>