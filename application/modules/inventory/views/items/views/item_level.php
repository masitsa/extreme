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
			
	$search = $this->session->userdata('items_search');
	
	if(!empty($search))
	{
		$search_result = '<a href="'.site_url().'inventory/close-items-search" class="btn btn-danger">Close Search</a>';
	}


	$result = '<div class="padd">';	
	$result .= ''.$search_result2.'';
	$result .= '
			';
	//var_dump($query->num_rows());die();
	//if users exist display them
	if ($query->num_rows() > 0)
	{
		$count = $page;
		
		$result .= 
		'
		<div class="row">
			<div class="col-md-12">
				<table class="example table-autosort:0 table-stripeclass:alternate table table-hover table-bitemed " id="TABLE_2">
				  <thead>
					<tr>
					  <th >#</th>
					  <th class="table-sortable:default table-sortable" title="Click to sort">Date Created</th>
					  <th class="table-sortable:default table-sortable" title="Click to sort">Client Name</th>
					  <th class="table-sortable:default table-sortable" title="Click to sort">item Number</th>
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
						$item_id = $row->item_id;
						$item_number = $row->item_code;
						$item_status = $row->item_status_id;
						$item_instructions = $row->item_instructions;
						$item_status_name = $row->item_status_name;
						$created_by = $row->created_by;
						$created = $row->created;
						$client_name = $row->client_name;
						$modified_by = $row->modified_by;
						$last_modified = $row->last_modified;
						$item_approval_status = $row->item_approval_status;

						$item_details = $this->items_model->get_item_items($item_id);
						$total_price = 0;
						$total_items = 0;
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

						if($item_details->num_rows() > 0)
						{
							$items = '
							<p><strong>Last Modified:</strong> '.date('jS M Y H:i a',strtotime($last_modified)).'<br/>
							<strong>Modified by:</strong> '.$created_by.'</strong></br>
							<strong>Instructions:</strong> '.$item_instructions.'</strong></p>
							
							<table class="table table-striped table-condensed table-hover">
							<tr>
								<th>Item</th>
								<th>Quantity</th>
							</tr>';
							$item_items = $item_details->result();
							$total_price = 0;
							$total_items = 0;
							
							foreach($item_items as $res)
							{
								$item_item_id = $res->item_item_id;
								$product = $res->product_name;
								$quantity = $res->item_item_quantity;
								
								$items .= '
								<tr>
									<td>
									'.$product.'
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
							$items = 'This item has no items';
						}
						
						$button = '';


						

							$next_item_status = $item_approval_status+1;

							$status_name = $this->items_model->get_next_approval_status_name($next_item_status);
							//pending item
							if($item_approval_status == 0)
							{
								$status = '<span class="label label-info ">Wainting for '.$status_name.'</span>';
								$button = '<td><a href="'.site_url().'vendor/cancel-item/'.$item_number.'" class="btn btn-danger btn-sm pull-right" onclick="return confirm(\'Do you really want to cancel this item '.$item_number.'?\');">Cancel</a></td>';
								$button2 = '';
							}
							else if($item_approval_status == 1)
							{
								$status = '<span class="label label-info"> Waiting for '.$status_name.'</span>';
								$button = '';
								$button2 = '';
							}
							else if($item_approval_status == 2)
							{
								$status = '<span class="label label-info"> Waiting for '.$status_name.'</span>';
								$button = '';
							}
							else if($item_approval_status == 3)
							{
								$status = '<span class="label label-info"> Waiting for '.$status_name.'</span>';
								$button = '';
							}
							else if($item_approval_status == 4)
							{
								$status = '<span class="label label-info"> Waiting for '.$status_name.'</span>';
								$button = '';
							}
							else if($item_approval_status == 5)
							{
								$status = '<span class="label label-info"> Waiting for '.$status_name.'</span>';
								$button = '';
							}
							else if($item_approval_status == 6)
							{
								$status = '<span class="label label-danger">Waiting for '.$status_name.'</span>';
								$button = '<a href="'.site_url().'vendor/cancel-item/'.$item_id.'" class="btn btn-danger  btn-sm" onclick="return confirm(\'Do you really want to cancel this item '.$item_number.'?\');">Cancel</a>';
								$button2 = '';
							}

							// just to mark for the next two stages
						

							$count++;
							$result .= 
							'
								<tr>
									<td>'.$count.'</td>
									<td>'.date('jS M Y H:i a',strtotime($created)).'</td>
									<td>'.$client_name.'</td>
									<td>'.$item_number.'</td>
									<td>'.$status.'</td>
									<td><a href="'.site_url().'inventory/add-item-item/'.$item_id.'/'.$item_number.'" class="btn btn-success  btn-sm fa fa-folder"> item Items</a></td>
									
									
								</tr> 
							';
						
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
		$result .= "There are no items";
	}
	$result .= '</div>';
	echo $result;
?>
