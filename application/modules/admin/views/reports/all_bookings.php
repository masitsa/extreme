<?php
		$result ='';
		// $result = '<a href="'.site_url().'airline/add-flight" class="btn btn-success pull-right">Add Flight</a>';
            
		$success = $this->session->userdata('success_message');
		
		if(!empty($success))
		{
			echo '<div class="alert alert-success"> <strong>Success!</strong> '.$success.' </div>';
			$this->session->unset_userdata('success_message');
		}
		
		$error = $this->session->userdata('error_message');
		
		if(!empty($error))
		{
			echo '<div class="alert alert-danger"> <strong>Oh snap!</strong> '.$error.' </div>';
			$this->session->unset_userdata('error_message');
		}

						//if users exist display them
						if ($query->num_rows() > 0)
						{
							$count = $page;
							
							$result .= 
							'
								<table class="table table-hover table-bordered ">
								  <thead>
									<tr>
									  <th>#</th>
									  <th>Customer</th>
									  <th>Airline</th>
									  <th>Airport</th>
									  <th>Booking Date</th>
									  <th>Departure Day</th>
									  <th>Departure Time</th>
									  <th>Total Payment ($)</th>
									  <th>Status</th>
									  <th colspan="5">Actions</th>
									</tr>
								  </thead>
								  <tbody>
							';
							
							foreach ($query->result() as $row)
							{
								$airline_name = $row->airline_name;
								$booking_id = $row->booking_id;
								$flight_id = $row->flight_id;
								$flight_date = $row->flight_date;
								$flight_departure_time = $row->flight_departure_time;
								$booking_amount = $row->booking_amount;
								$booking_units = $row->booking_units;
								$transaction_tracking_id = $row->transaction_tracking_id;
								$visitor_first_name = $row->visitor_first_name;
								$visitor_last_name = $row->visitor_last_name;
								$booking_status = $row->booking_status;
								$source = $row->source;
								$destination = $row->destination;
								$flight_departure_time = $row->flight_departure_time;
								$flight_arrival_time = $row->flight_arrival_time;
								$booking_date = $row->booking_date;
								
								$flight_departure_time = date('H:i a',strtotime($flight_departure_time));
								$flight_arrival_time = date('H:i a',strtotime($flight_arrival_time));
								
								//get source & destination names
								if($airports_query->num_rows() > 0)
								{
									foreach($airports_query->result() as $res)
									{
										$airport_id = $res->airport_id;
										
										if($airport_id == $source)
										{
											$source = $res->airport_name;
										}
										
										if($airport_id == $destination)
										{
											$destination = $res->airport_name;
										}
									}
								}
								
								//create deactivated status display
								if($booking_status == 0)
								{
									$status = '<span class="label label-danger">Unpaid</span>';
								}
								//create activated status display
								else if($booking_status == 1)
								{
									$status = '<span class="label label-success">Paid</span>';
								}
								
								$count++;
								$result .= 
								'
									<tr>
										<td>'.$count.'</td>
										<td>'.$visitor_first_name.' '.$visitor_last_name.'</td>
										<td>'.$airline_name.'</td>
										<td>'.$source.'</td>
										<td>'.date('jS M Y H:i:s',strtotime($booking_date)).'</td>
										<td>'.date('jS M Y',strtotime($flight_date)).'</td>
										<td>'.$flight_departure_time.'</td>
										<td>'.number_format(($booking_amount * $booking_units), 2).'</td>
										<td>'.$status.'</td>
										<td>
											
											<!-- Button to trigger modal -->
											<a href="#user'.$flight_id.'" class="btn btn-sm btn-primary" data-toggle="modal">View</a>
											
											<!-- Modal -->
											<div id="user'.$flight_id.'" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
												<div class="modal-dialog">
													<div class="modal-content">
														<div class="modal-header">
															<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
															<h4 class="modal-title">Booking Details</h4>
														</div>
														
														<div class="modal-body">
															<!-- Nav tabs -->
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#home" data-toggle="tab">Customer Details</a></li>
                                <li><a href="#profile" data-toggle="tab">Flight Details</a></li>
                                <li><a href="#messages" data-toggle="tab">Payment Details</a></li>
                            </ul>

                            <!-- Tab panes -->
                            <div class="tab-content">
                                <div class="tab-pane fade in active" id="home">
                                    <h4>Customer Details</h4>
                                    <table class="table table-stripped table-condensed table-hover">
										<tr>
											<th>First Name</th>
											<td>'.$visitor_first_name.'</td>
										</tr>
										<tr>
											<th>Last Name</th>
											<td>'.$visitor_last_name.'</td>
										</tr>
										<tr >
											<th>Email</th>
											<td>'.$row->visitor_email .'</td>
										</tr>
										<tr>
											<th>Phone</th>
											<td>'.$row->visitor_phone.'</td>
										</tr>
									</table>
                                </div>
                                <div class="tab-pane fade" id="profile">
                                    <h4>Flight Details</h4>
                                    <table class="table table-stripped table-condensed table-hover">
										<tr>
											<th>Source</th>
											<td>'.$source.'</td>
										</tr>
										<tr>
											<th>Destination</th>
											<td>'.$destination.'</td>
										</tr>
										<tr >
											<th>Departure Date</th>
											<td>'.date('jS M Y',strtotime($flight_date)).' '.$flight_departure_time.'</td>
										</tr>
										<tr>
											<th>Arrival Date</th>
											<td>'.date('jS M Y',strtotime($flight_date)).' '.$flight_arrival_time.'</td>
										</tr>
										<tr>
											<th>Seats on Sale</th>
											<td>'.$row->flight_seats.'</td>
										</tr>
									</table>
                            	</div>
                                <div class="tab-pane fade" id="messages">
                                    <h4>Payment Details</h4>
                                    <table class="table table-stripped table-condensed table-hover">
										<tr>
											<th>Amount</th>
											<td>$'.$booking_amount.'</td>
										</tr>
										<tr>
											<th>Seats Booked</th>
											<td>'.$booking_units.'</td>
										</tr>
										<tr >
											<th>Total</th>
											<td>$'.number_format(($booking_amount * $booking_units), 2).'</td>
										</tr>
									</table>
                            	</div>
														</div>
														<div class="modal-footer">
															<button type="button" class="btn btn-sm btn-default" data-dismiss="modal" aria-hidden="true">Close</button>
															
															
														</div>
													</div>
												</div>
											</div>
										
										</td>
										
										</tr> 
								';
							}
							
							$result .= 
							'
										  </tbody>
										</table>
							';
						}
						
						else
						{
							$result .= "There are no bookings";
						}

		
		echo $result;
?>