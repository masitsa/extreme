<?php
	$error = $this->session->userdata('error_message');
	$success = $this->session->userdata('success_message');
	$search_result ='';
	$search_result2  ='';
	if(!empty($error))
	{
		$search_result2 = '<div class="alert alert-danger">'.$error.'</div>';
		$this->session->unset_userdata('error_message');
	}
	
	if(!empty($success))
	{
		$search_result2 ='<div class="alert alert-success">'.$success.'</div>';
		$this->session->unset_userdata('success_message');
	}
	
			
	$search = $this->session->userdata('requests_search');
	
	if(!empty($search))
	{
		$search_result = '<a href="'.site_url().'inventory/close-requests-search" class="btn btn-danger">Close Search</a>';
	}

	$result = '<div class="padd">';
	$result .= ''.$search_result2.'';
	$result .= '';
	
	//if users exist display them
	if ($query->num_rows() > 0)
	{
		$count = $page;
		
		$result .= 
		'
	
						
		<div class="row">
					<div class="col-md-12">
				<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-brequested " id="TABLE_2">
				  <thead>
					<tr>
					  <th >#</th>
					    <th><a href="'.site_url().'inventory/requests/created/'.$order_method.'/'.$page.'">Date Created</a></th>
					  <th><a href="'.site_url().'inventory/requests/client_name/'.$order_method.'/'.$page.'">Client Name</a></th>
					  <th><a href="'.site_url().'inventory/requests/request_number/'.$order_method.'/'.$page.'">Request Number</a></th>
					  <th class="table-sortable:default table-sortable" title="Click to sort">Turnaround Time (Days)</th>
					  <th class="table-sortable:default table-sortable" title="Click to sort">Status</th>
					  <th colspan="2">Actions</th>
					</tr>
				  </thead>
				  <tbody>
				';
		
				
					//get all administrators
					$personnel_query = $this->personnel_model->get_all_personnel();
					
					foreach ($query->result() as $row)
					{
						$request_id = $row->request_id;
						$request_number = $row->request_number;
						$request_status = $row->request_status_id;
						$request_instructions = $row->request_instructions;
						$request_status_name = $row->inventory_level_status_name;
						$created_by = $row->created_by;
						$created = $row->created;
						$client_name = $row->client_name;
						$modified_by = $row->modified_by;
						$last_modified = $row->last_modified;
						$request_approval_status = $row->request_approval_status;
						$inventory_level_status_id = $row->inventory_level_status_id;

						$request_details = $this->requests_model->get_request_items($request_id);
						//echo $request_details->num_rows();die();
						$total_price = 0;
						$total_items = 0;
						$turn_around_time=$this->requests_model->get_turnaround_time($request_id);
						$turn_around_time=round ($turn_around_time);

						//creators & editors
						
						
						
						if($personnel_query->num_rows() > 0)
						{
							$personnel_result = $personnel_query->result();
							
							foreach($personnel_result as $adm)
							{
								$personnel_id2 = $adm->personnel_id;
								
								if($created_by == $personnel_id2 ||  $modified_by == $personnel_id2 )
								{
									$created_by = $adm->personnel_fname;
									break;
								}
								
								else
								{
									$created_by = '-';
								}
							}
						}
						
						else
						{
							$created_by = '-';
						}
						
						if($request_details->num_rows() > 0)
						{

							$items = '
							<p><strong>Last Modified:</strong> '.date('jS M Y H:i a',strtotime($last_modified)).'<br/>
							<strong>Modified by:</strong> '.$created_by.'</strong></br>
							<strong>Instructions:</strong> '.$request_instructions.'</strong></p>
							
							<table class="table table-striped table-condensed table-hover">
							<tr>
								<th>Item</th>
								<th>Quantity</th>
							</tr>';
							$request_items = $request_details->result();
							$total_price = 0;
							$total_items = 0;
							
							foreach($request_items as $res)
							{
								$request_item_id = $res->request_item_id;
								$item_name = $res->item_name;
								$quantity = $res->request_item_quantity;
								
								$items .= '
								<tr>
									<td>
									'.$item_name.'
									</td>
									<td>'.$quantity.'</td>
								</tr>
								';
							}
								
							$items .= '
								<tr>
									<td></td>
									<td></td>
								</tr>
							</table>
							';
						}
						
						else
						{
							$items = 'This request has no items';
						}
						
						$button = '';

						$approval_levels = $this->requests_model->check_if_can_access($inventory_level_status_id,$personnel_approval_query); 
						
						if($approval_levels == TRUE)
						{
							$next_request_status = $request_approval_status+1;

							$status_name = $this->requests_model->get_next_approval_status_name($next_request_status);
							//pending request
							if($request_approval_status == 0)
							{
								$status = '<span class="label label-warning ">Wainting for Order Creation</span>';
								$button = '<td><a href="'.site_url().'vendor/cancel-request/'.$request_number.'" class="btn btn-danger btn-sm pull-right" onclick="return confirm(\'Do you really want to cancel this request '.$request_number.'?\');">Cancel</a></td>';
								$button2 = '';
							}
							else if($request_approval_status == 1)
							{
								$status = '<span class="label label-danger"> Waiting for First Approval</span>';
								$button = '';
								$button2 = '';
							}
							else if($request_approval_status == 2)
							{
								$status = '<span class="label label-info"> Wainting for Second Approval</span>';
								$button = '';
							}
							else if($request_approval_status == 3)
							{
								$status = '<span class="label label-info"> Waiting for Final Approval</span>';
								$button = '';
				 			}
							else if($request_approval_status == 4)
							{
								$status = '<span class="label label-success"> Approved </span>';
								$button = '';
								$button2 = '<a href="'.site_url().'vendor/print-invoice/'.$request_id.'" class="btn btn-primary  btn-sm fa fa-print" onclick="return confirm(\'Do you really want to print the quote '.$request_number.'?\');"> Print Quotation</a>';
							}
							
							// just to mark for the next two stages
						

							$count++;
							$button2='';
							$result .= 
							'
								<tr>
								
									<td>'.$count.'</td>
									<td>'.date('jS M Y H:i a',strtotime($created)).'</td>
									<td>'.$client_name.'</td>
									<td>'.$request_number.'</td>
									<td>'.$turn_around_time.'</td>
									<td>'.$status.'</td>
									<td><a href="'.site_url().'inventory/add-request-event/'.$request_id.'/'.$request_number.'" class="btn btn-success  btn-sm fa fa-folder"> Request Items</a></td>
									<td><a href="'.site_url().'inventory/duplicate-request/'.$request_id.'/'.$request_number.'" class="btn btn-warning  btn-sm fa fa-folder"> Duplicate Request</a></td>
									<td>'.$button2.'</td>
									
									
								</tr> 
							';
						}
						
						else
						{
							$result .= '
							<tr>
								<td colspan="6">You do not have the access rights to view this item</td>
							</tr>';
						}
					}
			
				$result .= 
				'
						  </tbody>
						</table>
						
					</div>
				
				</div>
				';
				
			}
			
			else
			{
				$result .= "There are no requests";
			}
			$result .= '</div>';
			
			echo $result;
			if (isset($links))
			{
				echo $links;
			}
?>
