<style>
.datepicker{z-index:1151 !important;}
</style>
<?php
$request_number = '';
$request_date = '';
$created = '';
$item_hiring_price = '';
$request_instructions = '';
$request_status_name = '';
$client_name = '';
$personnel_fname = '';
$personnel_onames = '';
$personnel_onames = '';
$date = '';
$start_time = '';
$end_time = '';

$request_approval_status = $this->requests_model->get_request_approval_status($request_id);

if(($request_approval_status == 0)&&($request_approval_status < 4))
{
	foreach($request_details->result() as $results)
		{
			$request_id = $results->request_id;
			$request_number = $results->request_number;
			$request_date = $results->request_date;
			$created = $results->created;
			$request_instructions = $results->request_instructions;
			$request_status_name = $results->request_status_name;
			$client_name = $results->client_name;
			$personnel_fname = $results->personnel_fname;
			$personnel_onames = $results->personnel_onames;
		
		}
	?>
    <!-- Modal -->
<div class="modal fade" id="add_event" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Event</h4>
			</div>
			<div class="modal-body">
            	<?php
				 echo form_open('inventory/add-request-event/'.$request_id.'/'.$request_number, array("class" => "form-horizontal", "role" => "form"));
				?>
							<div class="form-group">
								<label class="col-lg-12 ">Type</label>
								<div class="col-lg-12">
									<select class="form-control" name="event_id" id="event_id">
										<option>SELECT AN EVENT</option>
										<?php
										
										if($event_query->num_rows() > 0)
										{
											foreach ($event_query->result() as $key ) 
											{
												# code...
												$event_id = $key->event_id;
												$event_name = $key->event_name;

												echo '<option value="'.$event_id.'">'.$event_name.'</option>';
											}
										}
										?>

									</select>
									
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-12 ">Venue</label>
								<div class="col-lg-12">
									<input type="text" class="form-control" name="venue" placeholder="Venue">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-12 ">Name</label>
								<div class="col-lg-12">
									<input type="text" class="form-control" name="event_name" placeholder="Event Name" id="pax">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-12 ">PAX</label>
								<div class="col-lg-12">
								<input type="text" class="form-control" name="pax" placeholder="Event Pax" id="pax">
								</div>
							</div>
							<div class="form-group">
								<label class="col-lg-12 ">Start Date</label>
								<div class="col-lg-12">
									 <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="start_date" placeholder="Start Date" value="<?php echo set_value('start_date');?>" />
								</div>
							</div>
							<div class="form-group">

								<label class="col-lg-12 ">End Date</label>
									<div class="col-lg-12">
										 <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="end_date" placeholder="End Date" value="<?php echo set_value('end_date');?>" />
									</div>
							</div>
							<div class="form-group">
								<label class="col-lg-12 ">Budget</label>
								<div class="col-lg-12">
									<input type="text" class="form-control" name="budget" placeholder="Budget" id="pax">
								</div>
							</div>
						<div class="center-align">
							<button class="btn btn-primary btn-sm" type="submit">Add Request Event</button>
						</div>
							<?php echo form_close();?>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
		</div>
	</div>
</div>
	<!-- end add request event -->
	<?php
} 
?>
<!-- Event request details -->
<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title">Request<?php echo $request_number;?></h2>
    </header>
    <div class="panel-body">
	
    	<div class="row">
			<?php
				if(($request_approval_status == 0)&&($request_approval_status < 4)){
			?>
			<div class="col-md-offset-8 col-md-2">
            	<button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#add_event">
                	Add Event
                </button>
            </div>
			<?php }?>
			<div class="col-md-2">
            	<a href="<?php echo base_url();?>requests" class="btn btn-info btn-sm pull-right">Back to Requests</a>
            </div>
        </div>
	
    	<div class="row">
			<div class="col-md-12">
            	<!-- Button trigger modal -->
                
                  
						
				<?php
				$request_details=$this->requests_model->get_request_details($request_id);
				//var_dump($request_details); die();
				if($request_details->num_rows() > 0)
				{
					foreach($request_details->result() as $results)
					{
						$request_id = $results->request_id;
						$request_number = $results->request_number;
						$request_date = $results->request_date;
						$created = $results->created;
						$request_instructions = $results->request_instructions;
						$request_status_name = $results->request_status_name;
						$client_name = $results->client_name;
						$personnel_fname = $results->personnel_fname;
						$personnel_onames = $results->personnel_onames;
					
					}
				}
                ?>
				
				<!-- Request details -->
				<section class="panel panel-featured panel-featured-info">
					<table class="table table-hover table-striped table-bordered">
						<thead>
							<tr>
								<th>Request Number</th>
								<th>Requested On:</th>
								<th>Turnaround Days:</th>
								<th>Created On:</th>
								<th>Created By:</th>
								<th>Request Status Name</th>
								<th>Request Instructions</th>
								<th>Client Name</th>
							</tr>
						</thead>
						<?php
						$turn_around_time=$this->requests_model->get_turnaround_time($request_id);
						$turn_around_time=round ($turn_around_time);
						?>
						<tr>
							<td><?php echo $request_number; ?></td>
							<td><?php echo date('jS M Y',strtotime($request_date)); ?></td>
							<td><?php echo $turn_around_time; ?></td>
							<td><?php echo date('jS M Y H:i a',strtotime($created)); ?></td>
							<td><?php echo $personnel_fname;?>  <?php echo $personnel_onames; ?></td>
							<td><?php echo $request_status_name; ?></td>
							<td><?php echo $request_instructions; ?></td>
							<td><?php echo $client_name; ?></td>
						</tr>
					</table>
                    
				</section>
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
							<a class="btn btn-warning btn-sm" href="<?php echo base_url();?>inventory/send-request-for-approval/<?php echo $request_id;?>/<?php echo $next_order_status;?>" onclick="return confirm('Do you want to send request for next approval?');">Send Request for approval</a>
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
						<?php
					}
					else if($request_approval_status == 4 )
					{
						?>
							 <a class="btn btn-warning btn-sm fa fa-print" href="<?php echo base_url();?>inventory/generate-lpo/<?php echo $request_id;?>/<?php echo $request_number;?>" target="_blank"> View Quotation </a>
                            <?php
                            echo '<div class="alert alert-info">Your Request Has Been Approved</div>';
							?>
						<?php
					}

					else
					{
						
					}
				
					?>
	            	
	            </div>
			</div>
		</div>
		<br>
				<!-- End request details -->
                
        <?php
				$request_event_details = $this->events_model->get_request_event($request_id);
				//var_dump($request_event_details); die();
				if($request_event_details->num_rows()>0)
				{
					foreach($request_event_details->result() as $events)
					{
						//var_dump($request_event_details);
						$event_name = $events->request_event_name;
						$request_event_id =$events->request_event_id;
						$event_venue = $events->request_event_venue;
						$start_date = $events->request_event_start_date;
						$end_date = $events->request_event_end_date;
						$budget = $events->request_event_budget;
				
						?>
                        								    <!-- Modal -->
<div class="modal fade" id="add_event_item<?php echo $request_event_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Event Item</h4>
			</div>
			<div class="modal-body">
								<?php echo form_open('inventory/add-request-item/'.$request_id.'/'.$request_event_id, array("class" => "form-horizontal", "role" => "form","request_event_id"=>$request_event_id));?>
										<div class="form-group">
											<label class="col-lg-12 ">Item Name</label>
											<div class="col-lg-12">
												<select class="form-control" name="item_id" id="request_item_id" request_event_id="<?php echo $request_event_id;?>">
													<option>SELECT AN ITEM</option>
													<?php
													if($items_query->num_rows() > 0)
													{
														foreach ($items_query->result() as $key ) 
														{
															# code...
															$item_id = $key->item_id;
															$item_name = $key->item_name;
															$item_hiring_price=$key->item_hiring_price_kshs;

															echo '<option value="'.$item_id.'">'.$item_name.'</option>';
														}
													}
													?>

												</select>
									  		</div>
                                      </div>
											<div class="form-group">
												<label class="col-lg-12 ">Item Quantity</label>
												<div class="col-lg-12">
													 <input type="text" class="form-control" name="quantity" placeholder="Quantity">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-12 ">Days</label>
												<div class="col-lg-12">
													 <input type="text" class="form-control" name="days" placeholder="Days">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-12 ">Item Price</label>
												<div class="col-lg-12">
													 <input type="text" class="form-control" name="request_item_price" placeholder="Item Price" id="request_item_price<?php echo $request_event_id;?>">
													 <input type="hidden" name="minimum_hiring_price" id="minimum_hiring_price<?php echo $request_event_id;?>" />
												</div>
											</div>

									<div class="center-align">
										<button class="btn btn-primary btn-sm" type="submit">Add Event Item</button>
									</div>
								<?php echo form_close();?>
                                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
		</div>
	</div>
</div>
                                <!-- Modal -->
                                <div class="modal fade" id="add_event_logistics<?php echo $request_event_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel">Add Event Logistic</h4>
                                            </div>
                                            <div class="modal-body">
                                <?php echo form_open('inventory/add-event-logistic/'.$request_id.'/'.$request_event_id, array("class" => "form-horizontal", "role" => "form","request_event_id"=>$request_event_id));?>
										<div class="form-group">
											<label class="col-lg-12 ">Type</label>
											<div class="col-lg-12">
												<select class="form-control" name="logistic_id" id="logistic_id" request_event_id="<?php echo $request_event_id;?>">
                                                <?php 
												
		$logistic_query = $this->events_model->all_unselected_logistics($request_event_id);
		?>
													<option>SELECT LOGISTICS</option>
													<?php
													if($logistic_query->num_rows() > 0)
													{
														foreach ($logistic_query->result() as $logistics ) 
														{
															# code...
															$logistic_id = $logistics->logistic_id;
															$logistic_name = $logistics->logistic_name;
															echo '<option value="'.$logistic_id.'">'.$logistic_name.'</option>';
														}
													}
													?>

												</select>
											   
											</div>
										</div>
											<div class="form-group">
												<label class="col-lg-12 ">Qty</label>
												<div class="col-lg-12">
													 <input type="text" class="form-control" name="quantity" placeholder="Quantity">
												</div>
											</div>
                                            
											<div class="form-group">
												<label class="col-lg-12 ">Days</label>
												<div class="col-lg-12">
													 <input type="text" class="form-control" name="days" placeholder="Days">
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-12 ">Cost</label>
												<div class="col-lg-12">
													 <input type="text" class="form-control" name="logistic_cost" placeholder="Cost" id="logistic_cost<?php echo $request_event_id;?>">
													
												</div>
											</div>
									<div class="center-align">
										<button class="btn btn-primary btn-sm" type="submit">Add Event Logistics</button>
									</div>
								<?php echo form_close();?>
                                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
		</div>
	</div>
</div>
						<section class="panel panel-featured panel-featured-info">
							<header class="panel-heading">
								<h2 class="panel-title"><?php echo $event_name;?></h2>
                                
							</header>
							<div class="panel-body">
								<table class="table table-condensed table-hover table-striped">
									<tr>
										<th>Event Name</th>
										<th>Venue</th>
										<th>Start Date</th>
										<th>End Date</th>
										<th>Days</th>
										<th>Budget</th>
									</tr>
									<tr>
										<td><?php echo $event_name;?></td>
										<td><?php echo $event_venue;?></td>
										<td><?php echo $start_date;?></td>
										<td><?php echo $start_date;?></td>
										<td></td>
										<td><?php echo $budget?></td>
									</tr>
								</table>	
							</div>
						</section> 
                        
						<h2 class="panel-title"> Request Items for <?php echo $event_name;?> 
                        <?php
							if(($request_approval_status == 0)&&($request_approval_status < 4)){
						?>
						<button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#add_event_item<?php echo $request_event_id?>">
							Add Event Item
						</button>
						<?php }?>
                        </h2>
			<div class="panel-body">
			
			<?php
			$request_item_query = $this->requests_model->get_request_items($request_event_id);
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
					if($request_approval_status < 4){	
					$result .= 
					'
					<div class="row">
						<div class="col-md-12">
							<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
							  <thead>
								<tr>
								  <th>#</th>
								  <th>Item Name</th>
								  <th>Days</th>
								  <th>Quantity</th>
								  <th>Price Per Item (KES)</th>
								  <th>Total (KES)</th>
								  <th>Actions</th>
															  
								  '.$col.'
								</tr>
							  </thead>
					
							  <tbody>
							';
						}
						else
						{
							$result .= 
					'
					<div class="row">
						<div class="col-md-12">
							<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
							  <thead>
								<tr>
								  <th>#</th>
								  <th>Item Name</th>
								  <th>Days</th>
								  <th>Quantity</th>
								  <th>Price Per Item (KES)</th>
								  <th>Total (KES)</th>
															  
								  '.$col.'
								</tr>
							  </thead>
					
							  <tbody>
							';
						}
							$count = 0;
							$invoice_total = 0;
							$total= 0;
							foreach($request_item_query->result() as $res)
							{
								$request_id = $res->request_id;
								$item_name = $res->item_name;
								$days =$res->days;
								$request_item_quantity = $res->request_item_quantity;
								$request_item_id = $res->request_item_id;
								$supplier_unit_price = $res->supplier_unit_price;
								$item_hiring_price = $res->item_hiring_price_kshs;
								$request_item_price = $res->request_item_price;
								$total = $request_item_price*$request_item_quantity*$days;
								$invoice_total=$invoice_total+$total;
								$count++;
									if($request_approval_status == 0)
									{
										$result .= ' '.form_open('inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
													<tr>
														<td>'.$count.'</td>
														<td>'.$item_name.'</td>
														<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
														<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'"></td>
														<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'" ></td>
															<td>'.number_format($total,2).'</td>
														<td><a href="'.site_url().'inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Request</button></td>
														<td><a href="'.site_url().'inventory/delete-request-item/'.$request_item_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$item_name.'?\')" title="Delete '.$item_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
													</tr>
													'.form_close().'
													';
														
									}
									else if($request_approval_status == 1)
									{
										 $total_price = $request_item_price * $request_item_quantity*$days;
										$result .= ' '.form_open('inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
													<tr>
														<td>'.$count.'</td>
														<td>'.$item_name.'</td>
														<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
														<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'"></td>
														<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'" ></td>
															<td>'.number_format($total,2).'</td>
														<td><a href="'.site_url().'inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Request</button></td>
														<td><a href="'.site_url().'inventory/delete-request-item/'.$request_item_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$item_name.'?\')" title="Delete '.$item_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
													</tr>
													
													'.form_close().'
													';
									}
									else if($request_approval_status == 2)
									{
										 $total_price = $request_item_price * $request_item_quantity*$days;
										 $result .= ' '.form_open('inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
													<tr>
														<td>'.$count.'</td>
														<td>'.$item_name.'</td>
														<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
														<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'"></td>
														<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'" ></td>
															<td>'.number_format($total,2).'</td>
														<td><a href="'.site_url().'inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Request</button></td>
														<td><a href="'.site_url().'inventory/delete-request-item/'.$request_item_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$item_name.'?\')" title="Delete '.$item_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
													</tr>
													
													'.form_close().'
													';
									}
									else if($request_approval_status == 3)
									{
										 $total_price = $request_item_price * $request_item_quantity*$days;
										 $result .= ' '.form_open('inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
													<tr>
														<td>'.$count.'</td>
														<td>'.$item_name.'</td>
														<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
														<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'"></td>
														<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'" ></td>
															<td>'.number_format($total,2).'</td>
														<td><a href="'.site_url().'inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Request</button></td>
														<td><a href="'.site_url().'inventory/delete-request-item/'.$request_item_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$item_name.'?\')" title="Delete '.$item_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
													</tr>
													
													'.form_close().'
													';
									}
									else if($request_approval_status == 4)
									{
										 $total_price = $request_item_price * $request_item_quantity;
										 $result .= ' '.form_open('inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
													<tr>
														<td>'.$count.'</td>
														<td>'.$item_name.'</td>
														<td>'.$days.'</td>
														<td>'.$request_item_quantity.'</td>
														<td>'.$request_item_price.'</td>
															<td>'.number_format($total,2).'</td>
														
													</tr>
													
													'.form_close().'
													';
									}
									else if($request_approval_status == 5 OR $request_approval_status == 6)
									{
										 $total_price = $supplier_unit_price * $request_item_quantity * $days;

										 $invoice_total = $total_price + $invoice_total;
										 $result .= ' 
													<tr>
														<td>'.$count.'</td>
														<td>'.$item_name.'</td>
														<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
														<td><input type="text" class="form-control" name="quantity" value="'.$request_item_quantity.'"></td>
														<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'" ></td>
															<td>'.number_format($total,2).'</td>
														<td><a href="'.site_url().'inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Request</button></td>
														<td><a href="'.site_url().'inventory/delete-request-item/'.$request_item_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$item_name.'?\')" title="Delete '.$item_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
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
														<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'"></td>
															<td>'.number_format($total,2).'</td>
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
														<td></td>
														<td>TOTAL AMOUNT</td>
														<td> '.number_format($invoice_total,2).'</td>
													</tr>
													';
							}
							if($invoice_total > 0)
							{
								$result .= '<tr>
												<td colspan="4"></td>
												<th>TOTAL AMOUNT</th>
												<th>'.number_format($invoice_total,2).'</th>
											</tr>';
							}
							$result .= '
							
								</tbody>
							</table>
							';
							echo $result;
				}
					?>
				</div>
        	</div>
	<h2 class="panel-title"> Logistics for <?php echo $event_name;?>
	
	<?php
		if(($request_approval_status == 0)&&($request_approval_status < 4)){
	?>
	<button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#add_event_logistics<?php echo $request_event_id?>">
		Add Event Logistics
	</button>
	<?php }?>
	</h2>
	<div class="panel-body">
		
    	<?php
		$event_logistic_query = $this->events_model->get_event_logistics($request_event_id);
    		$result ='';
			if($event_logistic_query->num_rows() > 0)
			{
				$col = '';
				$message = '';
				
				if($request_approval_status == 0)
				{
					$col = '<th colspan="3">Actions</th>';

				}
				else if($request_approval_status == 4)
				{
					
							
			
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
				if($request_approval_status < 4)
				{	
				$result .= 
				'
				<div class="row">
					<div class="col-md-12">
						<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
						  <thead>
							<tr>
							  <th>#</th>
							  <th>Logistic</th>
							  <th>Days</th>
							  <th>Quantity</th>
							  <th>Price(KES)</th>
							  <th>Total (KES)</th>
							  <th>Actions</th>
							  '.$col.'
							</tr>
						  </thead>
						  <tbody>
						';
						}
						else
						{
							$result .= 
				'
				<div class="row">
					<div class="col-md-12">
						<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
						  <thead>
							<tr>
							  <th>#</th>
							  <th>Logistic</th>
							  <th>Days</th>
							  <th>Quantity</th>
							  <th>Price(KES)</th>
							  <th>Total (KES)</th>
							  '.$col.'
							</tr>
						  </thead>
						  <tbody>
						';
						}
						$count = 0;
						$invoice_total = 0;
						$total= 0;
						foreach($event_logistic_query->result() as $event_logistics)
						{
							$logistic_id = $event_logistics->logistic_id;
							$logistic_name = $event_logistics->logistic_name;
							//$logistic_name = $this->events_model->get_event_logistic_name($logistic_id );
							$days =$event_logistics->request_event_logistic_days;
							$event_logistic_quantity = $event_logistics->request_event_logistic_quantity;
							$event_logistic_price = $event_logistics->request_event_logistic_price;
							$request_event_id = $request_event_id;
							$total = $event_logistic_price*$event_logistic_quantity *$days;
							$invoice_total=$invoice_total+$total;
		                    $count++;
								
							

								if($request_approval_status == 0)

								{
				                    $result .= ' '.form_open('inventory/update-request-logistic/'.$logistic_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
												<tr>
													<td>'.$count.'</td>
													<td>'.$logistic_name.'</td>
													<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
													<td><input type="text" class="form-control" name="quantity" value="'.$event_logistic_quantity.'"></td>
													<td><input type="text" class="form-control" name="request_item_price" value="'.$event_logistic_price.'" ></td>
														<td>'.number_format($total,2).'</td>
													<td><a href="'.site_url().'inventory/update-request-logistics/'.$logistic_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Logistic</button></td>
													<td><a href="'.site_url().'inventory/delete-request-logistics/'.$logistic_id.'/'.$request_event_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$logistic_name.'?\')" title="Delete '.$logistic_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
												</tr>
												'.form_close().'
												';
													
								}
								else if($request_approval_status == 1)
								{
									 $total_price = $event_logistic_price * $event_logistic_quantity*$days;
									 $result .= ' '.form_open('inventory/update-request-logistic/'.$logistic_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
												<tr>
													<td>'.$count.'</td>
													<td>'.$logistic_name.'</td>
													<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
													<td><input type="text" class="form-control" name="quantity" value="'.$event_logistic_quantity.'"></td>
													<td><input type="text" class="form-control" name="request_item_price" value="'.number_format($event_logistic_price,2).'" ></td>
														<td>'.number_format($total,2).'</td>
													<td><a href="'.site_url().'inventory/update-request-logistics/'.$logistic_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Logistic</button></td>
													<td><a href="'.site_url().'inventory/delete-request-logistics/'.$logistic_id.'/'.$request_event_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$logistic_name.'?\')" title="Delete '.$logistic_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
												</tr>
												
												'.form_close().'
												';
								}
								else if($request_approval_status == 2)
								{
									 $total_price = $event_logistic_price * $event_logistic_quantity*$days;
									 $result .= ' '.form_open('inventory/update-request-logistic/'.$logistic_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
												<tr>
													<td>'.$count.'</td>
													<td>'.$logistic_name.'</td>
													<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
													<td><input type="text" class="form-control" name="quantity" value="'.$event_logistic_quantity.'"></td>
													<td><input type="text" class="form-control" name="request_item_price" value="'.number_format($event_logistic_price,2).'" ></td>
														<td>'.number_format($total,2).'</td>
													<td><a href="'.site_url().'inventory/update-request-logistics/'.$logistic_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Logistic</button></td>
													<td><a href="'.site_url().'inventory/delete-request-logistics/'.$logistic_id.'/'.$request_event_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$logistic_name.'?\')" title="Delete '.$logistic_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
												</tr>
												
												'.form_close().'
												';
								}
								else if($request_approval_status == 3)
								{
									 $total_price = $event_logistic_price * $event_logistic_quantity*$days;
									 $result .= ' '.form_open('inventory/update-request-logistic/'.$logistic_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
												<tr>
													<td>'.$count.'</td>

													<td>'.$logistic_name.'</td>
													<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
													<td><input type="text" class="form-control" name="quantity" value="'.$event_logistic_quantity.'"></td>
													<td><input type="text" class="form-control" name="request_item_price" value="'.number_format($event_logistic_price,2).'" ></td>
														<td>'.number_format($total,2).'</td>
													<td><a href="'.site_url().'inventory/update-request-logistics/'.$logistic_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Logistic</button></td>
													<td><a href="'.site_url().'inventory/delete-request-logistics/'.$logistic_id.'/'.$request_event_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$logistic_name.'?\')" title="Delete '.$logistic_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
												</tr>
												
												'.form_close().'
												';
								}
								else if($request_approval_status == 4)
								{
									 $total_price = $event_logistic_price * $event_logistic_quantity;
									$result .= ' '.form_open('inventory/update-request-logistic/'.$logistic_id.'/'.$request_event_id.'/'.$request_number.'/'.$request_id).'
												<tr>
													<td>'.$count.'</td>
													<td>'.$logistic_name.'</td>
													<td>'.$days.'</td>
													<td>'.$event_logistic_quantity.'</td>
													<td>'.number_format($event_logistic_price,2).'</td>
													<td>'.number_format($total,2).'</td>
												</tr>
												
												'.form_close().'
												';
								}
								else if($request_approval_status == 5 OR $request_approval_status == 6)
								{
									 $total_price = $event_logistic_price * $event_logistic_quantity * $days;

									 $invoice_total = $total_price + $invoice_total;
									 $result .= ' 
												<tr>
													<td>'.$count.'</td>
													<td>'.$logistic_name.'</td>
													<td><input type="text" class="form-control" name="days" value="'.$days.'"></td>
													<td><input type="text" class="form-control" name="quantity" value="'.$event_logistic_quantity.'"></td>
													<td><input type="text" class="form-control" name="request_item_price" value="'.number_format($event_logistic_price,2).'" ></td>
														<td>'.number_format($total,2).'</td>
													<td><a href="'.site_url().'inventory/update-request-item/'.$request_item_id.'/'.$request_event_id.'"><button class="btn btn-success btn-sm" type="submit"><i class="fa fa-pencil"></i> Edit Request</button></td>
													<td><a href="'.site_url().'inventory/delete-request-item/'.$request_item_id.'/'.$request_id.'/'.$request_number.'"  onclick="return confirm(\'Do you want to delete '.$item_name.'?\')" title="Delete '.$item_name.'" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i> Delete</a></td>
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
													<td><input type="text" class="form-control" name="request_item_price" value="'.$request_item_price.'"></td>
														<td>'.number_format($total).'</td>
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
													<td></td>
													<td>TOTAL AMOUNT</td>
													<td> '.number_format($invoice_total).'</td>
												</tr>
												';
						}
						if($invoice_total > 0)
						{
							$result .= '<tr>
											<td colspan="4"></td>
											<th>TOTAL AMOUNT</th>
											<th>'.number_format($invoice_total).'</th>
											
										</tr>';
						}
						$result .= '
						
							</tbody>
						</table>
						';
						echo $result;
								
				}
				?>
                <div class="modal fade" id="add_event_personnel<?php echo $request_event_id;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="myModalLabel">Add Event Personnel</h4>
			</div>
			<div class="modal-body">
								<?php echo form_open('inventory/add-request-personnel/'.$request_id.'/'.$request_event_id, array("class" => "form-horizontal", "role" => "form","request_event_id"=>$request_event_id));?>
										<div class="form-group">
											<label class="col-lg-12 ">Personnel Name</label>
											<div class="col-lg-12">
												<select class="form-control" name="personnel_id" id="personnel_id" request_event_id="<?php echo $request_event_id;?>">
													<option>SELECT PERSONNEL</option>
													<?php
													$personnel_query = $this->requests_model->get_personnel();
													if($personnel_query->num_rows() > 0)
													{
														foreach ($personnel_query->result() as $key ) 
														{
															# code...
															$personnel_id = $key->personnel_id;
															$personnel_fname = $key->personnel_fname;
															$personnel_onames = $key->personnel_onames;

															echo '<option value="'.$personnel_id.'">'.$personnel_fname. $personnel_onames.'</option>';
														}
													}
													?>

												</select>
									  			</div>
                                      		</div>
									   		<div class="form-group">
                                                <label class="col-lg-12 ">Date</label>
                                                <div class="col-lg-12">
                                                     <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="date" placeholder="Date" value="<?php echo set_value('date');?>" />
                                                </div>
                                        	</div>
											<div class="form-group">
												<label class="col-lg-12 ">Start Time</label>
												<div class="col-lg-12">
													 <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin-timepicker="" name="start_time">
                                                    </div>
												</div>
											</div>
											<div class="form-group">
												<label class="col-lg-12 ">End Time</label>
												<div class="col-lg-12">
													 <div class="input-group">
                                                        <span class="input-group-addon">
                                                            <i class="fa fa-clock-o"></i>
                                                        </span>
                                                        <input type="text" class="form-control" data-plugin-timepicker="" name="end_time">
                                                    </div>

												</div>
											</div>
									<div class="center-align">
										<button class="btn btn-primary btn-sm" type="submit">Add Event Pesonnel</button>
									</div>
								<?php echo form_close();?>
                                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
      		</div>
		</div>
	</div>
</div>
				<h2 class="panel-title"> Event Personnel for <?php echo $event_name;?> 
                        <?php
							if(($request_approval_status == 0)&&($request_approval_status < 4)){
						?>
						<button type="button" class="btn btn-primary btn-sm pull-right" data-toggle="modal" data-target="#add_event_personnel<?php echo $request_event_id?>">
							Add Event Personnel
						</button>
						<?php }?>
                        </h2>
			<div class="panel-body">
			
			<?php
			$request_event_personnel_query = $this->requests_model->get_request_personnel($request_event_id);
			//var_dump ($request_event_personnel_query);die();
				$result ='';
				if($request_event_personnel_query->num_rows() > 0)
				{	
					$result .= 
					'
					<div class="row">
						<div class="col-md-12">
							<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
							  <thead>
								<tr>
								  <th>Personnel Name</th>
								  <th>Date</th>
								  <th>Start Time</th>
								  <th>End Time</th>
								</tr>
							  </thead>
							  <tbody>
							';
									
					foreach($request_event_personnel_query->result() as $request_personnel)
					{
						$personnel_fname = $request_personnel->personnel_fname;
						$personnel_onames=$request_personnel->personnel_onames;
						$date = $request_personnel->personnel_event_date;
						$start_time = $request_personnel->start_time;
						$end_time = $request_personnel->end_time;
						
						$result .='
							<tr>
								<td>'.$personnel_fname.' '.$personnel_onames.'</td>
								<td>'.$date.'</td>
								<td>'.$start_time.'</td>
								<td>'.$end_time.'</td>
							</tr>
								';
					}
					$result .= '
								</tbody>
							</table>
							';
							echo $result;
				}
						?>
						</div>
		</div>
	
		<?php
					
					}
						?>
				<div class="row">
					<div class="col-md-12">
						<div class="center-align">
						<?php
			            	$request_approval_status = $this->requests_model->get_request_approval_status($request_id);

							if($request_approval_status >= 0 && $request_approval_status <=3)
							{
								echo '
									<div class="alert alert-info">Your Request is being processed</div>
								';
							}
							else
							{
								echo '
									<div class="alert alert-info">Your Request Has Been Approved</div>
								';
							}
							?>
			            </div>
					</div>
				</div>
                <?php
				}
					?>
	</div>
</section>
<!-- End event request details -->

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