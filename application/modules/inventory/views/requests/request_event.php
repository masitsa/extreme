<?php
$request_number = '';
$request_date = '';
$created = '';
$request_instructions = '';
$request_status_name = '';
$client_name = '';
$personnel_fname = '';
$personnel_onames = '';

$request_approval_status = $this->requests_model->get_request_approval_status($request_id);

if($request_approval_status == 0)
{
	$request_details=$this->requests_model->get_request_details($request_id);
	//var_dump ($request_details);
	//die();
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
	<!-- Add request event -->
	<section class="panel panel-featured panel-featured-info">
		<header class="panel-heading">
			<h2 class="panel-title pull-left">Add Event</h2>
			<div class="widget-icons pull-right">
				<a href="<?php echo base_url();?>requests" class="btn btn-primary btn-sm">Back to Requests</a>
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
				
				echo form_open('inventory/add-request-event/'.$request_id.'/'.$request_number, array("class" => "form-horizontal", "role" => "form"));
			?>
				<div class="row">
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-lg-2 control-label">Type</label>
							<div class="col-lg-8">
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
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-lg-2 control-label">Venue</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="venue" placeholder="Venue">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-lg-2 control-label">Name</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="event_name" placeholder="Event Name" id="pax">
							</div>
						</div>
					</div>
					<div class="col-md-3">
						<div class="form-group">
							<label class="col-lg-2 control-label">PAX</label>
							<div class="col-lg-8">
							<input type="text" class="form-control" name="pax" placeholder="Event Pax" id="pax">
							</div>
						</div>
					</div>
				</div>
				
				<br/>
				
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-lg-2 control-label">Start Date</label>
							<div class="col-lg-8">
								 <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="start_date" placeholder="Start Date" value="<?php echo set_value('start_date');?>" />
							</div>
						</div>
					</div>
						
					<div class="col-md-4">
						<div class="form-group">

							<label class="col-lg-2 control-label">End Date</label>
								<div class="col-lg-8">
									 <input data-format="yyyy-MM-dd" type="text" data-plugin-datepicker class="form-control" name="end_date" placeholder="End Date" value="<?php echo set_value('end_date');?>" />
								</div>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="col-lg-2 control-label">Budget</label>
							<div class="col-lg-8">
								<input type="text" class="form-control" name="budget" placeholder="Budget" id="pax">
							</div>
						</div>
					</div>
				</div>
				<div class="row">
					<div class="center-align">
						<button class="btn btn-primary btn-sm" type="submit">Add Request Event</button>
					</div>
				</div>
			<?php echo form_close();?>
		</div>
	</section>
	<!-- end add request event -->
	<?php
} 

?>
	
<!-- Event request details -->
<section class="panel panel-featured panel-featured-info">
    <header class="panel-heading">
        <h2 class="panel-title">Event Requests for <?php echo $request_number;?></h2>
    </header>
    <div class="panel-body">
	
    	<div class="row">
			<div class="col-md-12">
						
				<?php
				$request_details=$this->requests_model->get_request_details($request_id);
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
					<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bordered " id="TABLE_2">
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
				<!-- End request details -->
				
				<?php
				$request_event_details = $this->events_model->get_request_event($request_id);
				//var_dump($request_event_details); die();
				if($request_event_details->num_rows()>0)
				{
					foreach($request_event_details->result() as $events)
					{
						//var_dump($request_event_details); die();
						$event_name = $events->request_event_name;
						$event_venue = $events->request_event_venue;
						$start_date = $events->request_event_start_date;
						$end_date = $events->request_event_end_date;
						$budget = $events->request_event_budget;
				
						?>
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
									<div class="col-md-3">
										<div class="form-group">
											<label class="col-lg-2 control-label">Item Name</label>
											<div class="col-lg-8">
												<select class="form-control" name="item_id" id="request_item_id">
													<option>SELECT AN ITEM</option>
													<?php
													if($items_query->num_rows() > 0)
													{
														foreach ($items_query->result() as $key ) 
														{
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
									  <div class="col-md-3">
											<div class="form-group">
												<label class="col-lg-2 control-label">Item Quantity</label>
												<div class="col-lg-8">
													 <input type="text" class="form-control" name="quantity" placeholder="Quantity">
												</div>
											</div>
										</div>
										<div class="col-md-3">
											<div class="form-group">
												<label class="col-lg-2 control-label">Days</label>
												<div class="col-lg-8">
													 <input type="text" class="form-control" name="days" placeholder="Days">
												</div>
											</div>
										</div>
									
									<div class="col-md-3">
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
				}
				?>
		
			</div>
		</div>
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