<?php

class Services_model extends CI_Model 
{	
	/*
	*	Retrieve all services
	*
	*/
	public function all_services()
	{
		$this->db->where('service_status_id = 1 AND service_deleted=0');
		$query = $this->db->get('services');
		
		return $query;
	}
	

	
	public function all_unselected_services($request_id){
		
		$query = $this->db->query('select * from services where service_status_id = 1 and service_id not in(select service_id from request_service where request_id='.$request_id.')');
		
		return $query;
	}
	public function get_all_services($table, $where, $per_page, $page, $order, $order_method)
	{
		$this->db->from($table);
		$this->db->select('*');
		$this->db->where($where);
		$this->db->order_by($order, $order_method);
		
		if(isset($limit))
		{
			$query = $this->db->get('', $limit);
		}
		
		else
		{
			$query = $this->db->get('', $per_page, $page);
		}
		
		return $query;
	}

	
	public function add_service()
	{
		
		//$code = $this->create_service_code($this->input->post('category_id'));
		
		$data = array(
				'service_name'=>ucwords(strtolower($this->input->post('service_name'))),
				'preffered_vendor'=>$this->input->post('store_id'),
				'quantity_on_hand'=>$this->input->post('quantity_on_hand'),
				'service_cost'=>$this->input->post('service_cost'),
				'service_price'=>$this->input->post('service_price'),
				'reorder_point'=>$this->input->post('reorder_point'),
				'quantity_on_sales_order'=>$this->input->post('quantity_on_sales_order'),
				'quantity_on_purchase_order'=>$this->input->post('quantity_on_purchase_order'),
				'service_description'=>$this->input->post('service_description'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'created'=>date('Y-m-d H:i:s'),
				'last_modified'=>date('Y-m-d H:i:s'),
				'service_deleted'=>0,
				'service_status_id'=>1,
				'created_by'=>$this->session->userdata('personnel_id'),
				
			);
			
		if($this->db->insert('services', $data))
		{
			
			return TRUE;
		}
		else{
			return FALSE;
		}
		
	}
	/*
	*	Update an existing service
	*	@param string $image_name
	*	@param int $service_id
	*
	*/
	public function update_service($service_id)
	{
		$data = array(
				'service_name'=>ucwords(strtolower($this->input->post('service_name'))),
				'preffered_vendor'=>$this->input->post('store_id'),
				'quantity_on_hand'=>$this->input->post('quantity_on_hand'),
				'service_cost'=>$this->input->post('service_cost'),
				'service_price'=>$this->input->post('service_price'),
				'reorder_point'=>$this->input->post('reorder_point'),
				'quantity_on_sales_order'=>$this->input->post('quantity_on_sales_order'),
				'quantity_on_purchase_order'=>$this->input->post('quantity_on_purchase_order'),
				'service_description'=>$this->input->post('service_description'),
				'modified_by'=>$this->session->userdata('personnel_id'),
				'created'=>date('Y-m-d H:i:s'),
				'last_modified'=>date('Y-m-d H:i:s'),
				'service_deleted'=>0,
				'service_status_id'=>1,
				'created_by'=>$this->session->userdata('personnel_id'),
			);
			
		$this->db->where('service_id', $service_id);
		if($this->db->update('services', $data))
		{
			//save locations
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	
	
	/*
	*	get a single service's details
	*	@param int $service_id
	*
	*/
	public function get_service($service_id)
	{
		//retrieve all users
		$this->db->from('services');
		$this->db->select('services.*');
		
		if($service_id != NULL)
		{
			$this->db->where('service_id = '.$service_id);
		}
		
		else
		{
			$this->db->where('service_id = '.$service_id);
		}
		$query = $this->db->get();
		
		return $query;
	}
	
	/*
	*	get a single service's details
	*	@param int $service_id
	*
	*/
	public function get_service_shipping($service_id, $personnel_id = NULL)
	{
		//retrieve all users
		$this->db->from('services');
		
		$this->db->where('service_id = '.$service_id.' AND service.created_by = '.$personnel_id);
		$query = $this->db->get();
		
		return $query;
	}
	public function recently_viewed_services()
	{
		//retrieve all users
		$this->db->from('services, service_category');
		$this->db->select('service.*, category.category_name');
		$this->db->where('service.category_id = service_category.cservice_ategory_id  AND service.service_status_id = 1');
		$this->db->order_by('service.last_viewed_date','desc');
		$query = $this->db->get('', 10);
		 
		return $query;
	}
	
	/*
	*	Delete an existing service
	*	@param int $service_id
	*
	*/
	public function delete_service($service_id)
	{
		$data = array(
			'service_deleted'=>1,
			'deleted_on'=>date('Y-m-d H:i:s'),
			'deleted_by'=>$this->session->userdata('personnel_id'),
		);
		$this->db->where('service_id', $service_id);	
			if($this->db->update('services',$data))
			{
				return TRUE;
			}
			else{
				return FALSE;
			}
	}
	
	/*
	*	Activate a deactivated service
	*	@param int $service_id
	*
	*/
	public function activate_service($service_id)
	{
		$data = array(
				'service_status_id' => 1
			);
		$this->db->where('service_id', $service_id);
		
		if($this->db->update('services', $data))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
	
	/*
	*	Deactivate an activated service
	*	@param int $service_id
	*
	*/
	public function deactivate_service($service_id)
	{
		$data = array(
				'service_status_id' => 0
			);
		$this->db->where('service_id', $service_id);
		
		if($this->db->update('services', $data))
		{
			return TRUE;
		}
		else{
			return FALSE;
		}
	}
	
	public function get_category_name($service_category_id)
	{
		$this->db->from('service_category');
		$this->db->select('category_name');
		$this->db->where('service_category_id ='.$service_category_id);
		$query = $this->db->get('');
		$category = $query->result();
		if($category > 0)
		{
			foreach($category as $category_details)
			{
				$category_name = $category_details->category_name;
			}
		}
		return $category_name;
	}
		
	public function check_current_number_exisits($service_name)
	{
		$this->db->from('services');
		$this->db->where('service_name', $service_name);
		
		$query = $this->db->get('');
		
		if($query->num_rows() > 0)
		{
			return TRUE;
		}
		
		else
		{
			return FALSE;
		}
	}
		
	public function create_service_code($category_id)
	{
		//get category_details
		$query = $this->services_categories_model->get_category($category_id);
		
		if($query->num_rows() > 0)
		{
			$result = $query->result();
			$category_preffix =  $result[0]->category_preffix;
			
			//select service code
			$this->db->from('service');
			$this->db->select('MAX(service_code) AS number');
			$this->db->where("service_code LIKE '".$category_preffix."%'");
			$query = $this->db->get();
			
			if($query->num_rows() > 0)
			{
				$result = $query->result();
				$number =  $result[0]->number;
				$number++;//go to the next number
				
				if($number == 1)
				{
					$number = $category_preffix."001";
				}
			}
			else
			{//start generating receipt numbers
				$number = $category_preffix."001";
			}
		}
		
		else
		{
			$number = '001';
		}
		
		return $number;
	}
	function import_template()
	{
		$this->load->library('Excel');
		
		$title = 'Extreme Services Import Template';
		$count=1;
		$r = 0;
		
		$array[$r][0] = 'Service Name';
		$array[$r][1] = 'Description';
		$array[$r][2] = 'Cost';
		$array[$r][3] = 'Price';
		$array[$r][4] = 'Preferred Vendor';
		$array[$r][5] = 'Quantity On Hand';
		$array[$r][6] = 'Quantity on Sales Order';
		$array[$r][7] = 'Quantity on Purchase Order';
		$array[$r][8] = 'Reorder Point';

		$r++;
		
		//create the excel document
		$this->excel->addArray ($array);
		$this->excel->generateXML ($title);
	}
	public function import_csv_services($upload_path)
	{
		//load the file model
		$this->load->model('admin/file_model');
		/*
			-----------------------------------------------------------------------------------------
			Upload csv
			-----------------------------------------------------------------------------------------
		*/
		$response = $this->file_model->upload_csv($upload_path, 'import_csv');
		
		if($response['check'])
		{
			$file_name = $response['file_name'];
			
			$array = $this->file_model->get_array_from_csv($upload_path.'/'.$file_name);
			//var_dump($array); die();
			$response2 = $this->sort_csv_data($array);
		
			if($this->file_model->delete_file($upload_path."\\".$file_name, $upload_path))
			{
			}
			
			return $response2;
		}
		
		else
		{
			$this->session->set_userdata('error_message', $response['error']);
			return FALSE;
		}
	}
	
	//sort the imported data
	public function sort_csv_data($array)
	{
		//count total rows
		$total_rows = count($array);
		$total_columns = count($array[0]);//var_dump($array);die();
		
		//if products exist in array
		if($total_rows > 0)
		{
			$response = '
				<table class="table table-hover table-bordered ">
					  <thead>
						<tr>
						  <th>#</th>
						  <th>Service Name</th>
						  <th>Service Cost</th>
						  <th>Service Price</th>
						  <th>Description</th>
						  <th>Comment</th>
						</tr>
					  </thead>
					  <tbody>
			';
			
			//retrieve the data from array
			for($r = 1; $r < $total_rows; $r++)
			{
				$service_name = $array[$r][0];
				$service_description = $array[$r][1];
				$service_cost = $array[$r][2];
				$service_price = $array[$r][3];
				$preffered_vendor = $array[$r][4];
				$quantity_on_hand = $array[$r][5];
				$quantity_on_sales_order = $array[$r][7];
				$quantity_on_purchase_order = $array[$r][8];
				$reorder_point = $array[$r][6];
				
				
				$services['service_name'] = $service_name;
				$services['service_description'] = $service_description;
				$services['service_cost'] = $service_cost;
				$services['service_price'] = $service_price;
				$services['preffered_vendor'] = $preffered_vendor;
				$services['quantity_on_hand'] = $quantity_on_hand;
				$services['quantity_on_sales_order'] = $quantity_on_sales_order;
				$services['quantity_on_purchase_order'] = $quantity_on_purchase_order;
				$services['reorder_point'] = $reorder_point;
				$services['service_status_id'] = 1;
				$services['created_by'] = $this->session_userdata('personnel_id');
				
				// check if service exist
				$comment ='';
				if(!empty($services['service_name']))
				{
					if(!empty($services['service_name']))
					{
						// check if the number already exists
						if($this->check_current_number_exisits($service_name))
						{
							//number exists
							$comment = '<br/>Duplicate service entered';
							$class = 'danger';
						}
						else
						{
							// number does not exisit
							//save product in the db
							if($this->db->insert('services', $services))
							{
								$service_id = $this->db->insert_id();
							
								$comment = '<br/>Service successfully added to the database';
								$class = 'success';
							}
							
							else
							{
								$comment = '<br/>Internal error. Could not add service to the database. Please contact the site administrator. Service code '.$services['service_name'];
								$class = 'warning';
							}
						}
					}
					else{
						$comment = '<br/>Internal error. Could not add service to the database. Please contact the site administrator. Service code '.$services['service_name'];
								$class = 'warning';
					}
				}
				else
				{
					$comment = '<br/>Not saved ensure you have a service number entered'.$services['service_name'];
					$class = 'danger';
				}
				
				
				$response .= '
					
						<tr class="'.$class.'">
							<td>'.$r.'</td>
							<td>'.$services['service_name'].'</td>
							<td>'.$services['service_cost'].'</td>
							<td>'.$services['service_price'].'</td>
							<td>'.$services['service_description'].'</td>
							<td>'.$comment.'</td>
						</tr> 
				';
				
			}
			
			$response .= '</table>';
			
			$return['response'] = $response;
			$return['check'] = TRUE;
		}
		
		//if no products exist
		else
		{
			$return['response'] = 'Service data not found';
			$return['check'] = FALSE;
		}
		
		return $return;
	}
	
	
	public function get_category_id($category_name)
	{
		$this->db->where('category_name = \''.$category_name.'\'');
		$query = $this->db->get('category');
		
		if($query->num_rows() > 0)
		{
			$row = $query->row();
			$category_id = $row->category_id;
		}
		
		else
		{
			$category_id = '';
		}
		
		return $category_id;
	}
	
	
}
?>