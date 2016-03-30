<?php
$request_approval_status = $this->requests_model->get_request_approval_status($request_id);

if($request_approval_status == 0)
{
?>	
	<section class="panel">
	    <header class="panel-heading">
	        <h2 class="panel-title pull-left">Add Request Item</h2>
	        <div class="widget-icons pull-right">
	            	<a href="<?php echo base_url();?>requests" class="btn btn-success btn-sm">Back to Requests</a>
	          </div>
	          <div class="clearfix"></div>
	    </header>
	    <div class="panel-body">
	    	<?php
				$success = $this->session->userdata('success_message');
				$error = $this->session->userdata('error_message');
				
				if(!empty($success))
				{
					echo '
						<div class="alert alert-success">'.$success.'</div>
					';
					
					$this->session->unset_userdata('success_message');
				}
				
				if(!empty($error))
				{
					echo '
						<div class="alert alert-danger">'.$error.'</div>
					';
					
					$this->session->unset_userdata('error_message');
				}
				
			?>
			
				    	<?php echo form_open($this->uri->uri_string(), array("class" => "form-horizontal", "role" => "form"));?>
				        <div class="row">
				        	<div class="col-md-4">
				                <div class="form-group">
				                	<label class="col-lg-2 control-label">Item Name</label>
				                    <div class="col-lg-8">
				                    	<select class="form-control" name="item_id" id="request_item_id">
				                    		<option>SELECT AN ITEM</option>
				                    		<?php
				                    		if($items_query->num_rows() > 0)
				                    		{
				                    			foreach ($items_query->result() as $key ) {
				                    				# code...
				                    				$item_id = $key->item_id;
				                    				$item_name = $key->item_name;
													$item_hiring_price=$key->item_hiring_price;

				                    				echo '<option value="'.$item_id.'">'.$item_name.'</option>';
				                    			}
				                    		}
				                    		?>

				                    	</select>
				                       
				                    </div>
				                </div>
				              </div>
				              <div class="col-md-4">
					                <div class="form-group">
					                	<label class="col-lg-2 control-label">Item Quantity</label>
					                    <div class="col-lg-8">
					                    	 <input type="text" class="form-control" name="quantity" placeholder="Quantity">
					                    </div>
					                </div>
					            </div>
				            
                            <div class="col-md-4">
					                <div class="form-group">
					                	<label class="col-lg-2 control-label">Item Price</label>
					                    <div class="col-lg-8">
					                    	 <input type="text" class="form-control" name="request_item_price" placeholder="Item Price" id="request_item_price">
                                             <input type="hidden" name="minimum_hiring_price" id="minimum_hiring_price" />
					                    </div>
					                </div>
					            </div>
				            </div>
				            <div class="row">
					              <div class="center-align">
					            	<button class="btn btn-primary btn-sm" type="submit">Add Request Item</button>
					            </div>
				        	</div>
				        
				        <?php echo form_close();?>
				    
	  	</div>
	</section>
<?php
} 

else if($request_approval_status == 2 || $request_approval_status == 3)
{
	
	
 }

?>
<section class="panel">
    <header class="panel-heading">
        <h2 class="panel-title pull-left">Request Items for <?php echo $request_number;?></h2>
         <div class="widget-icons pull-right">
            	<a href="<?php echo base_url();?>requests" class="btn btn-primary btn-sm">Back to Requests</a>
          </div>
          <div class="clearfix"></div>
    </header>
    <div class="panel-body">
	
    	<div class="row">
			<div class="col-md-12">
				<div class="center-align">
					<?php
					$request_approval_status = $this->requests_model->get_request_approval_status($request_id);
					$rank = 2;
					$next_order_status = $request_approval_status+1;
						
					// check if assigned the next level 
					$check_level_approval = $this->requests_model->check_assigned_next_approval($request_approval_status);

					if($request_approval_status == 0)
					{
						?>
							<a class="btn btn-success btn-sm" href="<?php echo base_url();?>inventory/send-request-for-approval/<?php echo $request_id;?>/<?php echo $next_order_status;?>" onclick="return confirm('Do you want to send request for next approval?');">Send Request for approval</a>
						<?php
					}

					else if($request_approval_status == 1 AND $check_level_approval == TRUE )
					{
						?>
							<a class="btn btn-warning btn-sm" href="<?php echo base_url();?>inventory/send-for-correction/<?php echo $request_id;?>" onclick="return confirm('Do you want to send request for review / correction?');">Send request for correction</a>
		            		<a class="btn btn-success btn-sm" href="<?php echo base_url();?>inventory/send-for-approval/<?php echo $request_id;?>/<?php echo $next_order_status;?>" onclick="return confirm('Do you want to send order for next approval?');">Send Request for next approval</a>
						<?php
					}
					else if($request_approval_status == 2 AND $check_level_approval == TRUE )
					{
						?>
							<a class="btn btn-warning btn-sm" href="<?php echo base_url();?>inventory/send-for-correction/<?php echo $request_id;?>" onclick="return confirm('Do you want to send request for review / correction?');">Send request for correction</a>
		            		<a class="btn btn-success btn-sm" href="<?php echo base_url();?>inventory/send-for-approval/<?php echo $request_id;?>/<?php echo $next_order_status;?>" onclick="return confirm('Do you want to send request for next approval?');">Send Request for next approval</a>
						<?php
					}
					
					else if(($request_approval_status == 3 AND $check_level_approval == TRUE ))
					{
						?>
		            		<a class="btn btn-success btn-sm" href="<?php echo base_url();?>inventory/send-for-approval/<?php echo $request_id;?>/<?php echo $next_order_status;?>" onclick="return confirm('Do you want to send request for next approval?');">Approve Qoute</a>
                            <a class="btn btn-warning btn-sm fa fa-print" href="<?php echo base_url();?>inventory/generate-lpo/<?php echo $request_id;?>/<?php echo $request_number;?>" target="_blank"> View Qoutation </a>
						<?php
					}
					else if($request_approval_status == 4 )
					{
						?>
							<a class="btn btn-warning btn-sm fa fa-print" href="<?php echo base_url();?>inventory/generate-lpo/<?php echo $request_id;?>" target="_blank"> View Qoutation </a>
						<?php
					}

					else
					{
						echo '<div class="alert alert-info">Your Request is waiting for the next approval</div>';
					}
				
					?>
	            	
	            </div>
			</div>
		</div>
		<br>
    	<?php
    		$result ='';
			if($request_item_query->num_rows() > 0)
			{
				$col = '';
				$message = '';
				
				if($request_approval_status == 0)
				{
					$col = '<th colspan="3">Actions</th>';

				}
				else if($request_approval_status == 4)
				{
					$col .= '
							<th>Unit Price (KES)</th>
							<th>Total Price (KES) </th>
							<th colspan="1">Actions</th>';

				}
				else if($request_approval_status == 5 OR $request_approval_status == 6)
				{
					$col .= '
							<th>Unit Price (KES)</th>
							<th>Total Price (KES) </th>';

				}

				else
				{
					$col = '';
				}
					
				$result .= 
				'
				<div class="row">
					<div class="col-md-12">
						<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
						  <thead>
							<tr>
							  <th class="table-sortable:default table-sortable" title="Click to sort">#</th>
							  <th class="table-sortable:default table-sortable" title="Click to sort">Item Name</th>
							  <th class="table-sortable:default table-sortable" title="Click to sort">Quantity</th>
							  							  <th class="table-sortable:default table-sortable" title="Click to sort">Price Per Item</th>
														  <th class="table-sortable:default table-sortable" title="Click to sort">Total</th>
														  
							  '.$col.'
							</tr>
						  </thead>
						  <tbody>
						';
						$count = 0;
						$invoice_total = 0;
						$total= 0;
						foreach($request_item_query->result() as $res)
						{
							$request_id = $res->request_id;
							$item_name = $res->item_name;
							$request_item_quantity = $res->request_item_quantity;
							$request_item_id = $res->request_item_id;
							$supplier_unit_price = $res->supplier_unit_price;
							$item_hiring_price = $res->item_hiring_price;
							$request_item_price = $res->request_item_price;
							$total = $request_item_price*$request_item_quantity;
							$invoice_total=$invoice_total+$total;
		                    $count++;
								
							

								if($request_approval_status == 0)
								{
				                    $result .= ' '.form_open('inventory/update-request-item/'.$request_id.'/'.$request_number.'/'.$request_item_id).'
												<tr>
													<td>'.$count.'</td>
													<td>'.$item_name.'</td>
													<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'"></td>
													<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'"></td>
													<td><input type="text" class="form-control" name="total" value="'.$total.'"></td>
													<td><a href="'.site_url().'inventory/update-request-item/'.$request_item_id.'/'.$request_id.'/'.$request_number.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Request</button></td>
													<td><a href="'.site_url().'inventory/delete-request-item/'.$request_item_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm("Do you want to delete '.$item_name.'?")" title="Delete '.$item_name.' class="btn btn-danger btn-sm">Delete</a></td>
												</tr>
												'.form_close().'
												';
													
								}
								else if($request_approval_status == 4)
								{
									 $total_price = $item_unit_price * $request_item_quantity;
									 $result .= ' '.form_open('inventory/update-supplier-prices/'.$request_id.'/'.$request_number.'/'.$request_item_id).'
												<tr>
													<td>'.$count.'</td>
													<td>'.$item_name.'</td>
													<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'" readonly></td>
													<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'"></td>
													<td>'.number_format($total_price,2).'</td>
													<td><button class="btn btn-warning btn-sm" type="submit"><i class="fa fa-pencil"></i> Update Price</button></td>
												</tr>
												
												'.form_close().'
												';
								}
								else if($request_approval_status == 5 OR $request_approval_status == 6)
								{
									 $total_price = $supplier_unit_price * $request_item_quantity;

									 $invoice_total = $total_price + $invoice_total;
									 $result .= ' 
												<tr>
													<td>'.$count.'</td>
													<td>'.$item_name.'</td>
													<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'" readonly></td>
													<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'" readonly></td>
													<td>'.number_format($total_price,2).'</td>
												</tr>
												';
									
								}
								else
								{
									 $result .= '
												<tr>
													<td>'.$count.'</td>
													<td>'.$item_name.'</td>
													<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'" readonly></td>
												</tr>
												';
								}
						
							
		                    ?>
		                    <?php
							$total_item_price=$total+$total;
						}
						if($request_approval_status == 5 || $request_approval_status == 6)
						{
							$result .= ' 
												<tr>
													<td></td>
													<td></td>
													<td></td>
													<td>TOTAL AMOUNT</td>
													<td>KES '.number_format($invoice_total,2).'</td>
												</tr>
												';
						}
						if($invoice_total > 0)
						{
							$result .= '<tr>
											<td colspan="3"></td>
											<td>TOTAL AMOUNT</td>
											<td>KES '.number_format($invoice_total,2).'</td>
											<td colspan="2"></td>
										</tr>';
						}
						$result .= '
						
							</tbody>
						</table>
						';

						echo $result;
					}
				?>

				<div class="row">
					<div class="col-md-12">
						<div class="center-align">
						<?php
			            	$request_approval_status = $this->requests_model->get_request_approval_status($request_id);

							if($request_approval_status > 0)
							{
								echo '
									<div class="alert alert-info">Your Request is being processed</div>
								';
							}
							else
							{
								?>
								<a class="btn btn-success btn-sm" href="<?php echo base_url();?>inventory/send-for-approval/<?php echo $request_id;?>">Send Request for approval</a>
								<?php
							}
							?>
			            </div>
					</div>
				</div>
    </div>
</section>


<script type="text/javascript">

function get_visit_trail(visit_id){

	var myTarget2 = document.getElementById("visit_trail"+visit_id);
	var button = document.getElementById("open_visit"+visit_id);
	var button2 = document.getElementById("close_visit"+visit_id);

	myTarget2.style.display = '';
	button.style.display = 'none';
	button2.style.display = '';
}
function close_visit_trail(visit_id){

	var myTarget2 = document.getElementById("visit_trail"+visit_id);
	var button = document.getElementById("open_visit"+visit_id);
	var button2 = document.getElementById("close_visit"+visit_id);

	myTarget2.style.display = 'none';
	button.style.display = '';
	button2.style.display = 'none';
}
</script>